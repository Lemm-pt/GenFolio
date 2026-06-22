<?php
/**
 * Clientes Model
 * 
 * Handles client (tenant) registration, authentication via digit codes,
 * security questions, failed attempt tracking, and account recovery.
 * 
 * @package SevenLux
 */

namespace core\models;

use core\classes\Database;
use core\classes\EnviarEmail;
use core\classes\Store;

class Clientes
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

  /**
 * Generates a unique salt for password-equivalent digit hashing.
 * Agora usa o slug do cliente para maior entropia e unicidade.
 *
 * @param string $slug
 * @return string
 */
private function generateSalt($slug = null)
{
    $slugPart = $slug ?? ($_SESSION['cliente_slug'] ?? 'vitrine-demo');
    return hash('sha256', microtime(true) . rand(1000, 9999) . $slugPart . date('YmdHis'));
}

    /**
     * Hashes the digit code with the given salt.
     *
     * @param string $salt
     * @param string $digits
     * @return string
     */
    private function hashDigits($salt, $digits)
    {
        return hash('sha256', $salt . $digits);
    }

    /**
     * Logs a warning event for a client.
     *
     * @param string $slug
     * @param string $reason
     * @param string $ip
     */
    private function addWarning($slug, $reason, $ip)
    {
        $this->db->insert(
            "INSERT INTO sevenlux_warnings (slug, motivo, ip) VALUES (:slug, :motivo, :ip)",
            [':slug' => $slug, ':motivo' => $reason, ':ip' => $ip]
        );
    }

    // ============================================================
    // CLIENT REGISTRATION
    // ============================================================

    /**
     * Registers a new client with email, slug, digit code, and security question.
     *
     * @param string $email
     * @param string $slug
     * @param string $digits
     * @param int $questionId
     * @param int $answerId
     * @return string|false The confirmation PURL or false on failure.
     */

    


public function registar_cliente($email, $slug, $digits, $cidade = null, $pais = null, $categoria = null)
{
    $purl = Store::criarHash();
    $salt = $this->generateSalt($slug);
    $hash = $this->hashDigits($salt, $digits);

    $params = [
        ':email'        => strtolower(trim($email)),
        ':slug'         => $slug,
        ':salt'         => $salt,
        ':hash_digitos' => $hash,
        ':purl'         => $purl,
        ':activo'       => 0,
        ':cidade'       => $cidade ?? null,
        ':pais'         => $pais ?? null,
        ':categoria'    => $categoria ?? null
    ];

    $result = $this->db->insert("
        INSERT INTO sevenlux_clientes 
        (email, slug, salt, hash_digitos, purl, activo, cidade, pais, categoria, created_at, updated_at) 
        VALUES 
        (:email, :slug, :salt, :hash_digitos, :purl, :activo, :cidade, :pais, :categoria, NOW(), NOW())
    ", $params);

    // Verificar se a inserção foi bem sucedida
    if ($result === false) {
        error_log("Erro ao inserir cliente: " . print_r($params, true));
        return false;
    }

    return $purl;
}

    // ============================================================
    // LOGIN VALIDATION (slug + digit code)
    // ============================================================

    /**
     * Validates a login attempt using slug and digit code.
     * Handles failed attempt counting and account blocking.
     *
     * @param string $slug
     * @param string $digits
     * @return object|false Client object on success, false on failure.
     */
    public function validar_login($slug, $digits)
    {
        $results = $this->db->select(
            "SELECT * FROM sevenlux_clientes  WHERE slug = :slug AND activo = 1 AND deleted_at IS NULL",
            [':slug' => $slug]
        );

        if (count($results) != 1) {
            return false;
        }

        $client = $results[0];
        $attemptHash = $this->hashDigits($client->salt, $digits);

        if ($attemptHash === $client->hash_digitos) {
            $this->resetAttempts($slug);
            return $client;
        } else {
            $this->incrementAttempts($slug, $client->tentativas_falhas);
            return false;
        }
    }

    // ============================================================
    // FAILED ATTEMPT TRACKING & BLOCKING
    // ============================================================

    /**
     * Resets failed attempt counters for a client.
     *
     * @param string $slug
     */
    private function resetAttempts($slug)
    {
        $this->db->update(
            "UPDATE sevenlux_clientes  SET tentativas_falhas = 0, bloqueio_ate = 0 WHERE slug = :slug",
            [':slug' => $slug]
        );
    }

    /**
     * Increments the failed attempt counter and sets a block time if thresholds are reached.
     * Exponential backoff after 3 attempts, fixed 5‑minute block after 7 attempts.
     *
     * @param string $slug
     * @param int $currentAttempts
     */
    private function incrementAttempts($slug, $currentAttempts)
    {
        $newAttempts = $currentAttempts + 1;
        $blockUntil = 0;

        if ($newAttempts >= 3) {
            $delay = pow(2, $newAttempts - 3);
            $blockUntil = time() + $delay;
        }
        if ($newAttempts >= 7) {
            $blockUntil = time() + 300;
            $this->addWarning($slug, "Limite de 7 tentativas atingido", $_SERVER['REMOTE_ADDR']);
        }

        $this->db->update(
            "UPDATE sevenlux_clientes  SET tentativas_falhas = :novas, bloqueio_ate = :bloqueio WHERE slug = :slug",
            [':novas' => $newAttempts, ':bloqueio' => $blockUntil, ':slug' => $slug]
        );
    }

  





    // ============================================================
    // EXISTENCE CHECKS
    // ============================================================

    /**
     * Checks if an email is already registered.
     *
     * @param string $email
     * @return bool
     */
    public function verificar_email_existe($email)
    {
        $results = $this->db->select("SELECT id_cliente FROM sevenlux_clientes  WHERE email = :email", [':email' => $email]);
        return count($results) != 0;
    }

    /**
     * Checks if a slug is already in use.
     *
     * @param string $slug
     * @return bool
     */
    public function verificar_slug_existe($slug)
    {
        $results = $this->db->select("SELECT id_cliente FROM sevenlux_clientes  WHERE slug = :slug", [':slug' => $slug]);
        return count($results) != 0;
    }

    // ============================================================
    // EMAIL CONFIRMATION
    // ============================================================

    /**
     * Confirms a client's email address using the PURL from the registration link.
     *
     * @param string $purl
     * @return bool
     */
    public function confirmar_email($purl)
    {
        $results = $this->db->select("SELECT id_cliente FROM sevenlux_clientes  WHERE purl = :purl", [':purl' => $purl]);
        if (count($results) != 1) {
            return false;
        }
        $clientId = $results[0]->id_cliente;
        $this->db->update(
            "UPDATE sevenlux_clientes  SET purl = NULL, activo = 1, updated_at = NOW() WHERE id_cliente = :id_cliente",
            [':id_cliente' => $clientId]
        );
        $this->createDefaultConfigurations($clientId);
        return true;
    }

    /**
     * Creates default configuration entries for a newly confirmed client.
     *
     * @param int $clientId
     */
    private function createDefaultConfigurations($clientId)
    {
        $exists = $this->db->select(
            "SELECT id FROM sevenlux_configuracoes_site WHERE cliente_id = :cliente_id LIMIT 1",
            [':cliente_id' => $clientId]
        );
        if ($exists) {
            return;
        }

        $configs = [
            ['cliente_id' => $clientId, 'chave' => 'logo_parte1',      'valor' => 'Meu'],
            ['cliente_id' => $clientId, 'chave' => 'logo_parte2',      'valor' => 'Negócio'],
            ['cliente_id' => $clientId, 'chave' => 'logo_imagem',      'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'slogan',           'valor' => 'Soluções Personalizadas'],
            ['cliente_id' => $clientId, 'chave' => 'texto_descritivo', 'valor' => 'Bem-vindo ao seu novo site!'],
            ['cliente_id' => $clientId, 'chave' => 'email_contacto',   'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'telefone',         'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'endereco',         'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'meta_description', 'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'meta_keywords',    'valor' => '']
        ];

        foreach ($configs as $config) {
            $this->db->insert(
                "INSERT INTO sevenlux_configuracoes_site (cliente_id, chave, valor) VALUES (:cliente_id, :chave, :valor)",
                $config
            );
        }
    }

    /**
     * Retrieves a client by its PURL (used during email confirmation).
     *
     * @param string $purl
     * @return object|null
     */
    public function buscarPorPurl($purl)
    {
        $res = $this->db->select("SELECT id_cliente, slug FROM sevenlux_clientes  WHERE purl = :purl", [':purl' => $purl]);
        return $res ? $res[0] : null;
    }

    // ============================================================
    // PASSWORD RECOVERY (legacy – kept for compatibility)
    // ============================================================

    /**
     * Generates a token for email‑based password recovery.
     *
     * @param string $email
     * @return string
     */
    public function gerarTokenRecuperacao($email)
    {
        $token = Store::criarHash(32);
        $this->db->update(
            "UPDATE sevenlux_clientes  SET purl = :token WHERE email = :email",
            [':token' => $token, ':email' => $email]
        );
        return $token;
    }

    /**
     * Validates a password recovery token.
     *
     * @param string $token
     * @return object|false
     */
    public function validarTokenRecuperacao($token)
    {
        $results = $this->db->select(
            "SELECT id_cliente, email FROM sevenlux_clientes  WHERE purl = :token AND activo = 1",
            [':token' => $token]
        );
        return count($results) == 1 ? $results[0] : false;
    }

    /**
     * Updates a client's password (legacy method – not used with digit‑code auth).
     *
     * @param int $clientId
     * @param string $newPassword
     */
    public function atualizarPassword($clientId, $newPassword)
    {
        $this->db->update(
            "UPDATE sevenlux_clientes  SET senha = :senha, purl = NULL, updated_at = NOW() WHERE id_cliente = :id_cliente",
            [
                ':senha'       => password_hash($newPassword, PASSWORD_DEFAULT),
                ':id_cliente'  => $clientId
            ]
        );
    }



 

/**
 * Valida token de recuperação de código
 * 
 * @param string $token
 * @return object|false Cliente ou false
 */
public function validarTokenRecuperacaoCodigo($token)
{
    $client = $this->db->select(
        "SELECT id_cliente, slug, email FROM sevenlux_clientes WHERE purl = :token AND activo = 1",
        [':token' => $token]
    );
    
    if (!$client || empty($client)) {
        error_log("❌ Token inválido: $token");
        return false;
    }
    
    error_log("✅ Token válido para slug: {$client[0]->slug}");
    return $client[0];
}



/**
 * Gera token para recuperação de código (via email)
 */
public function gerarTokenRecuperacaoCodigo($slug)
{
    $client = $this->db->select(
        "SELECT id_cliente, email, slug FROM sevenlux_clientes WHERE slug = :slug AND activo = 1",
        [':slug' => $slug]
    );
    
    if (!$client || empty($client)) {
        return false;
    }
    
    $client = $client[0];
    $token = Store::criarHash(32);
    
    // Guardar token na BD (usa a mesma coluna purl que já existe)
    $this->db->update(
        "UPDATE sevenlux_clientes SET purl = :token WHERE id_cliente = :id",
        [':token' => $token, ':id' => $client->id_cliente]
    );
    
    return [
        'token' => $token,
        'email' => $client->email,
        'slug' => $client->slug
    ];
}

/**
 * Valida token e redefinir código
 */
public function redefinirCodigoPorToken($token, $newDigits)
{
    $client = $this->db->select(
        "SELECT id_cliente, slug FROM sevenlux_clientes WHERE purl = :token AND activo = 1",
        [':token' => $token]
    );
    
    if (!$client || empty($client)) {
        return false;
    }
    
    $client = $client[0];
    
    // Validar novo código
    if (strlen($newDigits) < 1 || strlen($newDigits) > 7 || !ctype_digit($newDigits)) {
        return false;
    }
    
    $newSalt = $this->generateSalt($client->slug);
    $newHash = $this->hashDigits($newSalt, $newDigits);
    
    $this->db->update(
        "UPDATE sevenlux_clientes SET salt = :salt, hash_digitos = :hash, purl = NULL, updated_at = NOW() WHERE id_cliente = :id",
        [
            ':salt' => $newSalt,
            ':hash' => $newHash,
            ':id' => $client->id_cliente
        ]
    );
    
    return true;
}

/**
 * Mostra formulário para recuperar código (apenas pede email/slug)
 */
public function recuperar_codigo()
{
    $config = new Configuracao();
    Store::Layout([
        'layouts/html_header',
        'layouts/header',
        'recuperar_codigo',
        'layouts/footer',
        'layouts/html_footer'
    ], ['config' => $config]);
}

/**
 * Envia email com link de recuperação
 */
public function recuperar_codigo_submit()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Store::redirect('recuperar_codigo');
        return;
    }
    
    $slug = trim($_POST['text_slug'] ?? '');
    
    if (empty($slug)) {
        $_SESSION['erro'] = "Por favor, insira o slug do seu site.";
        Store::redirect('recuperar_codigo');
        return;
    }
    
    $clientModel = new Clientes();
    $result = $clientModel->gerarTokenRecuperacaoCodigo($slug);
    
    if ($result && isset($result['email'])) {
        $mailer = new EnviarEmail();
        $mailer->enviar_recuperacao_codigo($result['email'], $result['token'], $slug);
        $_SESSION['sucesso'] = "✅ Enviamos um email com as instruções para recuperar o seu código de acesso.";
    } else {
        // Por segurança, não revelar se o slug existe
        $_SESSION['sucesso'] = "✅ Se o slug existir, enviamos um email com as instruções.";
    }
    
    Store::redirect('recuperar_codigo');
}

/**
 * Formulário para definir novo código (após clicar no link do email)
 */
public function recuperar_codigo_confirmar()
{
    $token = $_GET['token'] ?? '';
    
    if (empty($token)) {
        $_SESSION['erro'] = "Link inválido.";
        Store::redirect('recuperar_codigo');
        return;
    }
    
    // Verificar se token existe na BD
    $clientModel = new Clientes();
    $client = $clientModel->db->select(
        "SELECT id_cliente, slug FROM sevenlux_clientes WHERE purl = :token AND activo = 1",
        [':token' => $token]
    );
    
    if (!$client || empty($client)) {
        $_SESSION['erro'] = "Link inválido ou expirado.";
        Store::redirect('recuperar_codigo');
        return;
    }
    
    $_SESSION['recovery_token'] = $token;
    
    Store::Layout([
        'layouts/html_header',
        'layouts/header',
        'recuperar_codigo_novo',
        'layouts/footer',
        'layouts/html_footer'
    ]);
}

/**
 * Processa o novo código
 */
public function recuperar_codigo_novo_submit()
{
    $token = $_SESSION['recovery_token'] ?? '';
    $newDigits = $_POST['novos_digitos'] ?? '';
    
    if (empty($token)) {
        $_SESSION['erro'] = "Sessão inválida.";
        Store::redirect('recuperar_codigo');
        return;
    }
    
    if (strlen($newDigits) < 1 || strlen($newDigits) > 7 || !ctype_digit($newDigits)) {
        $_SESSION['erro'] = "O código deve ter entre 1 e 7 dígitos.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo_confirmar&token=" . urlencode($token));
        return;
    }
    
    $clientModel = new Clientes();
    $result = $clientModel->redefinirCodigoPorToken($token, $newDigits);
    
    if ($result) {
        unset($_SESSION['recovery_token']);
        $_SESSION['sucesso'] = "✅ Código redefinido com sucesso! Agora pode fazer login.";
        Store::redirect('admin_login');
    } else {
        $_SESSION['erro'] = "❌ Erro ao redefinir código. Tente novamente.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo_confirmar&token=" . urlencode($token));
    }
}

    
}