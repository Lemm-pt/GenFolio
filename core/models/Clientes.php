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
use core\classes\Store;

class Clientes
{
    private $db;

    // 24 security questions with 7 possible answers each
    private $questions = [
        1  => ["text" => "Qual a sua cor preferida?", "answers" => ["Vermelho", "Azul", "Verde", "Amarelo", "Preto", "Branco", "Roxo"]],
        2  => ["text" => "Qual o seu prato preferido?", "answers" => ["Pizza", "Sushi", "Massa", "Hambúrguer", "Salada", "Frango", "Peixe"]],
        3  => ["text" => "Qual a sua estação do ano?", "answers" => ["Primavera", "Verão", "Outono", "Inverno", "🌸", "☀️", "❄️"]],
        4  => ["text" => "Qual o seu animal preferido?", "answers" => ["Cão", "Gato", "Leão", "Águia", "Golfinho", "Lobo", "Cavalo"]],
        5  => ["text" => "Qual o planeta que mais se associa a si?", "answers" => ["Marte", "Vénus", "Júpiter", "Saturno", "Mercúrio", "Terra", "Neptuno"]],
        6  => ["text" => "Qual o seu elemento?", "answers" => ["Fogo", "Água", "Terra", "Ar", "Madeira", "Metal", "Éter"]],
        7  => ["text" => "Qual o seu signo?", "answers" => ["Áries", "Touro", "Gémeos", "Câncer", "Leão", "Virgem", "Libra"]],
        8  => ["text" => "Qual a lua que mais gosta?", "answers" => ["Lua Cheia", "Lua Nova", "Quarto Crescente", "Quarto Minguante", "Lua Azul", "Lua de Sangue", "Lua Negra"]],
        9  => ["text" => "Qual o seu destino de sonho?", "answers" => ["Praia", "Montanha", "Cidade", "Deserto", "Floresta", "Neve", "Campo"]],
        10 => ["text" => "Qual o meio de transporte preferido?", "answers" => ["Carro", "Avião", "Comboio", "Barco", "Bicicleta", "Moto", "A pé"]],
        11 => ["text" => "Qual o seu hobby favorito?", "answers" => ["Ler", "Viajar", "Desporto", "Música", "Cinema", "Jogar", "Cozinhar"]],
        12 => ["text" => "Qual a sua bebida favorita?", "answers" => ["Café", "Chá", "Sumo", "Água", "Refrigerante", "Vinho", "Cerveja"]],
        13 => ["text" => "Qual o seu número da sorte?", "answers" => ["1", "2", "3", "4", "5", "6", "7"]],
        14 => ["text" => "Qual a forma geométrica que prefere?", "answers" => ["Círculo", "Quadrado", "Triângulo", "Espiral", "Estrela", "Hexágono", "Coração"]],
        15 => ["text" => "Qual o seu herói favorito?", "answers" => ["Superman", "Batman", "Mulher Maravilha", "Homem-Aranha", "Capitão América", "Iron Man", "Thor"]],
        16 => ["text" => "Qual a sua disciplina favorita?", "answers" => ["Matemática", "Ciências", "História", "Arte", "Música", "Desporto", "Literatura"]],
        17 => ["text" => "Se fosse uma árvore, qual seria?", "answers" => ["Carvalho", "Oliveira", "Salgueiro", "Pinheiro", "Macieira", "Choupo", "Cerejeira"]],
        18 => ["text" => "Se fosse um instrumento musical?", "answers" => ["Piano", "Guitarra", "Bateria", "Violino", "Flauta", "Saxofone", "Harpa"]],
        19 => ["text" => "Qual a sua energia predominante?", "answers" => ["Calma", "Fogo", "Brisa", "Ondas", "Montanha", "Floresta", "Estrela"]],
        20 => ["text" => "Qual o seu arquétipo?", "answers" => ["Herói", "Sábio", "Rebelde", "Amante", "Mago", "Explorador", "Cuidador"]],
        21 => ["text" => "Nome da sua primeira escola?", "answers" => ["Sol", "Lua", "Estrela", "Mar", "Monte", "Vale", "Fonte"]],
        22 => ["text" => "Marca do seu primeiro carro?", "answers" => ["Fiat", "Ford", "VW", "Toyota", "Honda", "Renault", "Peugeot"]],
        23 => ["text" => "Nome do seu melhor amigo de infância?", "answers" => ["Ana", "João", "Maria", "Pedro", "Sofia", "Lucas", "Beatriz"]],
        24 => ["text" => "Cidade onde nasceu?", "answers" => ["Lisboa", "Porto", "Coimbra", "Braga", "Faro", "Évora", "Funchal"]]
    ];

    public function __construct()
    {
        $this->db = new Database();
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Generates a unique salt for password-equivalent digit hashing.
     *
     * @return string
     */
    private function generateSalt()
    {
        return hash('sha256', microtime(true) . rand(1000, 9999) . 'SEGREDO_VITRINE');
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
            "INSERT INTO warnings (slug, motivo, ip) VALUES (:slug, :motivo, :ip)",
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
    public function registar_cliente($email, $slug, $digits, $questionId, $answerId)
    {
        $purl = Store::criarHash();
        $salt = $this->generateSalt();
        $hash = $this->hashDigits($salt, $digits);

        $params = [
            ':email'        => strtolower(trim($email)),
            ':slug'         => $slug,
            ':salt'         => $salt,
            ':hash_digitos' => $hash,
            ':pergunta_id'  => $questionId,
            ':resposta_id'  => $answerId,
            ':purl'         => $purl,
            ':activo'       => 0
        ];

        $this->db->insert("
            INSERT INTO clientes 
            (email, slug, salt, hash_digitos, pergunta_id, resposta_id, purl, activo, created_at, updated_at) 
            VALUES 
            (:email, :slug, :salt, :hash_digitos, :pergunta_id, :resposta_id, :purl, :activo, NOW(), NOW())
        ", $params);

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
            "SELECT * FROM clientes WHERE slug = :slug AND activo = 1 AND deleted_at IS NULL",
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
            "UPDATE clientes SET tentativas_falhas = 0, bloqueio_ate = 0 WHERE slug = :slug",
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
            "UPDATE clientes SET tentativas_falhas = :novas, bloqueio_ate = :bloqueio WHERE slug = :slug",
            [':novas' => $newAttempts, ':bloqueio' => $blockUntil, ':slug' => $slug]
        );
    }

    // ============================================================
    // DIGIT CODE RECOVERY (via security question)
    // ============================================================

    /**
     * Resets the client's digit code after verifying the security answer.
     *
     * @param string $slug
     * @param int $answerId
     * @param string $newDigits
     * @return bool
     */
    public function recuperarCodigo($slug, $answerId, $newDigits)
    {
        $client = $this->db->select("SELECT * FROM clientes WHERE slug = :slug", [':slug' => $slug]);
        if (!$client) {
            return false;
        }
        $client = $client[0];

        if ($answerId != $client->resposta_id) {
            return false;
        }

        $newSalt = $this->generateSalt();
        $newHash = $this->hashDigits($newSalt, $newDigits);
        $this->db->update(
            "UPDATE clientes SET salt = :salt, hash_digitos = :hash, tentativas_falhas = 0, bloqueio_ate = 0 WHERE slug = :slug",
            [':salt' => $newSalt, ':hash' => $newHash, ':slug' => $slug]
        );
        $this->addWarning($slug, "Recuperação de código realizada", $_SERVER['REMOTE_ADDR']);
        return true;
    }

    // ============================================================
    // DEVICE MANAGEMENT (optional, currently unused)
    // ============================================================

    /**
     * Authorizes a new device for a client (multi‑device limit).
     *
     * @param string $slug
     * @param string $deviceId
     * @param string $fingerprint
     * @param string $ip
     * @return bool
     */
    public function autorizarDispositivo($slug, $deviceId, $fingerprint, $ip)
    {
        $count = $this->db->select("SELECT COUNT(*) as total FROM dispositivos WHERE slug = :slug", [':slug' => $slug]);
        $max = $this->db->select("SELECT max_dispositivos FROM clientes WHERE slug = :slug", [':slug' => $slug]);
        if ($count[0]->total >= $max[0]->max_dispositivos) {
            return false;
        }

        $this->db->insert(
            "INSERT INTO dispositivos (slug, device_id, fingerprint_hash, ip_registo, ultimo_acesso) 
             VALUES (:slug, :device_id, :fingerprint, :ip, NOW())",
            [':slug' => $slug, ':device_id' => $deviceId, ':fingerprint' => $fingerprint, ':ip' => $ip]
        );
        return true;
    }

    // ============================================================
    // SECURITY QUESTIONS (getters)
    // ============================================================

    /**
     * Returns a random security question with its possible answers.
     *
     * @return array
     */
    public function getPerguntaAleatoria()
    {
        $ids = array_keys($this->questions);
        $id = $ids[array_rand($ids)];
        return [
            'id'       => $id,
            'texto'    => $this->questions[$id]['text'],
            'respostas' => $this->questions[$id]['answers']
        ];
    }

    /**
     * Returns a security question by its ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getPerguntaById($id)
    {
        return $this->questions[$id] ?? null;
    }

    /**
     * Returns the security question for a given client slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function getPerguntaBySlug($slug)
    {
        $results = $this->db->select(
            "SELECT pergunta_id FROM clientes WHERE slug = :slug AND activo = 1",
            [':slug' => $slug]
        );
        if (count($results) == 0) {
            return null;
        }
        $questionId = $results[0]->pergunta_id;
        return $this->getPerguntaById($questionId);
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
        $results = $this->db->select("SELECT id_cliente FROM clientes WHERE email = :email", [':email' => $email]);
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
        $results = $this->db->select("SELECT id_cliente FROM clientes WHERE slug = :slug", [':slug' => $slug]);
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
        $results = $this->db->select("SELECT id_cliente FROM clientes WHERE purl = :purl", [':purl' => $purl]);
        if (count($results) != 1) {
            return false;
        }
        $clientId = $results[0]->id_cliente;
        $this->db->update(
            "UPDATE clientes SET purl = NULL, activo = 1, updated_at = NOW() WHERE id_cliente = :id_cliente",
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
            "SELECT id FROM configuracoes_site WHERE cliente_id = :cliente_id LIMIT 1",
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
                "INSERT INTO configuracoes_site (cliente_id, chave, valor) VALUES (:cliente_id, :chave, :valor)",
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
        $res = $this->db->select("SELECT id_cliente, slug FROM clientes WHERE purl = :purl", [':purl' => $purl]);
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
            "UPDATE clientes SET purl = :token WHERE email = :email",
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
            "SELECT id_cliente, email FROM clientes WHERE purl = :token AND activo = 1",
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
            "UPDATE clientes SET senha = :senha, purl = NULL, updated_at = NOW() WHERE id_cliente = :id_cliente",
            [
                ':senha'       => password_hash($newPassword, PASSWORD_DEFAULT),
                ':id_cliente'  => $clientId
            ]
        );
    }
}