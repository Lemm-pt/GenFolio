<?php
/**
 * Clientes Model - VERSÃO COM ARGON2ID
 * 
 * Handles client (tenant) registration, authentication via digit codes,
 * failed attempt tracking, and account recovery.
 * 
 * @package SevenLux
 */

namespace core\models;

use core\classes\Database;
use core\classes\Store;

class Clientes
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // ============================================================
    // 🔐 MÉTODOS DE HASH COM ARGON2ID
    // ============================================================
    
    private function hashDigits($digits)
    {
        return password_hash($digits, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost'   => 4,
            'threads'     => 2
        ]);
    }

    private function verifyDigits($digits, $hash)
    {
        return password_verify($digits, $hash);
    }

    // ============================================================
    // REGISTO DE CLIENTE
    // ============================================================

    public function registar_cliente($email, $slug, $digits, $cidade = null, $pais = null, $categoria = null)
    {
        if (strlen($digits) < 1 || strlen($digits) > 7 || !ctype_digit($digits)) {
            error_log("❌ Código inválido: deve ter entre 1 e 7 dígitos");
            return false;
        }

        $token = Store::criarHash();
        $hash = $this->hashDigits($digits);
        $expiresAt = date('Y-m-d H:i:s', time() + 86400);

        // 🔥 Detetar idioma e moeda com base no país
        $locale = $this->getLocaleFromCountry($pais);
        $currency = $this->getCurrencyFromCountry($pais);

        $params = [
            ':email'        => strtolower(trim($email)),
            ':slug'         => $slug,
            ':hash_digitos' => $hash,
            ':email_confirmation_token' => $token,
            ':token_expires_at' => $expiresAt,
            ':activo'       => 0,
            ':cidade'       => $cidade ?? null,
            ':pais'         => $pais ?? null,
            ':categoria'    => $categoria ?? null,
            ':locale'       => $locale,
            ':currency'     => $currency
        ];

        $result = $this->db->insert("
            INSERT INTO sevenlux_clientes 
            (email, slug, hash_digitos, email_confirmation_token, token_expires_at, activo, cidade, pais, categoria, locale, currency, created_at, updated_at) 
            VALUES 
            (:email, :slug, :hash_digitos, :email_confirmation_token, :token_expires_at, :activo, :cidade, :pais, :categoria, :locale, :currency, NOW(), NOW())
        ", $params);

        if ($result === false) {
            error_log("❌ Erro ao inserir cliente: " . print_r($params, true));
            return false;
        }

        error_log("✅ Cliente registado com sucesso: $slug (locale: $locale, currency: $currency)");
        return $token;
    }

    
/**
 * Deteta o idioma com base no país (VERSÃO CORRIGIDA)
 */
private function getLocaleFromCountry($pais)
{
    if (empty($pais)) {
        return 'pt';
    }

    // 🔥 NORMALIZAR: remover acentos, converter para minúsculas, remover espaços extras
    $paisLower = strtolower(trim($pais));
    $paisLower = iconv('utf-8', 'ascii//TRANSLIT', $paisLower);
    $paisLower = preg_replace('/[^a-z\s-]/', '', $paisLower);
    $paisLower = trim($paisLower);

    error_log("🔍 getLocaleFromCountry - País original: '$pais'");
    error_log("🔍 getLocaleFromCountry - País normalizado: '$paisLower'");

    // Mapeamento completo país -> locale
    $map = [
        'portugal' => 'pt',
        'brasil' => 'pt-br',
        'angola' => 'pt',
        'mocambique' => 'pt',
        'cabo verde' => 'pt',
        'guine-bissau' => 'pt',
        'sao tome' => 'pt',
        'timor-leste' => 'pt',
        'espanha' => 'es',
        'franca' => 'fr',
        'reino unido' => 'en',
        'inglaterra' => 'en',
        'estados unidos' => 'en',
        'eua' => 'en',
        'alemanha' => 'de',
        'italia' => 'it',
        'holanda' => 'nl',
        'belgica' => 'nl',
        'suica' => 'de',
        'austria' => 'de',
        'irlanda' => 'en',
        'canada' => 'en',
        'australia' => 'en',
        'japao' => 'ja',
        'china' => 'zh',
        'russia' => 'ru',
        'mexico' => 'es',
        'argentina' => 'es',
        'colombia' => 'es',
        'peru' => 'es',
        'chile' => 'es',
        'venezuela' => 'es',
        'india' => 'hi',
        'africa do sul' => 'en',
        'egito' => 'ar',
        'israel' => 'he',
        'coreia do sul' => 'ko',
        'singapura' => 'en',
        'malasia' => 'ms',
        'indonesia' => 'id',
        'turquia' => 'tr',
        'grecia' => 'el',
        'polonia' => 'pl',
        'suecia' => 'sv',
        'noruega' => 'no',
        'dinamarca' => 'da',
        'finlandia' => 'fi',
        'ucrania' => 'uk',
        'romenia' => 'ro',
        'bulgaria' => 'bg',
        'hungria' => 'hu',
        'republica checa' => 'cs',
        'eslovaquia' => 'sk',
        'eslovenia' => 'sl',
        'croacia' => 'hr',
        'servia' => 'sr',
        'marrocos' => 'ar',
        'emirados arabes' => 'ar',
        'arabia saudita' => 'ar',
        'tailandia' => 'th',
        'vietname' => 'vi',
        'filipinas' => 'tl',
        'paquistao' => 'ur',
        'bangladesh' => 'bn',
        'nigeria' => 'en',
        'quenia' => 'sw',
        'nova zelandia' => 'en'
    ];

    // Verificar correspondência exata
    foreach ($map as $key => $locale) {
        if ($paisLower === $key) {
            error_log("✅ Locale encontrado (exato): $locale para '$pais'");
            return $locale;
        }
    }

    // Verificar correspondência contém
    foreach ($map as $key => $locale) {
        if (strpos($paisLower, $key) !== false || strpos($key, $paisLower) !== false) {
            error_log("✅ Locale encontrado (parcial): $locale para '$pais'");
            return $locale;
        }
    }

    error_log("⚠️ Locale não encontrado, usando fallback 'pt' para '$pais'");
    return 'pt';
}

/**
 * Deteta a moeda com base no país (VERSÃO CORRIGIDA)
 */
private function getCurrencyFromCountry($pais)
{
    if (empty($pais)) {
        return 'EUR';
    }

    // 🔥 NORMALIZAR
    $paisLower = strtolower(trim($pais));
    $paisLower = iconv('utf-8', 'ascii//TRANSLIT', $paisLower);
    $paisLower = preg_replace('/[^a-z\s-]/', '', $paisLower);
    $paisLower = trim($paisLower);

    $map = [
        'portugal' => 'EUR',
        'brasil' => 'BRL',
        'angola' => 'AOA',
        'mocambique' => 'MZN',
        'cabo verde' => 'CVE',
        'guine-bissau' => 'XOF',
        'sao tome' => 'STN',
        'timor-leste' => 'USD',
        'espanha' => 'EUR',
        'franca' => 'EUR',
        'alemanha' => 'EUR',
        'italia' => 'EUR',
        'holanda' => 'EUR',
        'belgica' => 'EUR',
        'irlanda' => 'EUR',
        'estados unidos' => 'USD',
        'reino unido' => 'GBP',
        'suica' => 'CHF',
        'austria' => 'EUR',
        'canada' => 'CAD',
        'australia' => 'AUD',
        'japao' => 'JPY',
        'china' => 'CNY',
        'russia' => 'RUB',
        'mexico' => 'MXN',
        'argentina' => 'ARS',
        'colombia' => 'COP',
        'peru' => 'PEN',
        'chile' => 'CLP',
        'venezuela' => 'VES',
        'india' => 'INR',
        'africa do sul' => 'ZAR',
        'egito' => 'EGP',
        'israel' => 'ILS',
        'coreia do sul' => 'KRW',
        'singapura' => 'SGD',
        'malasia' => 'MYR',
        'indonesia' => 'IDR',
        'turquia' => 'TRY',
        'grecia' => 'EUR',
        'polonia' => 'PLN',
        'suecia' => 'SEK',
        'noruega' => 'NOK',
        'dinamarca' => 'DKK',
        'finlandia' => 'EUR',
        'ucrania' => 'UAH',
        'romenia' => 'RON',
        'bulgaria' => 'BGN',
        'hungria' => 'HUF',
        'republica checa' => 'CZK',
        'eslovaquia' => 'EUR',
        'eslovenia' => 'EUR',
        'croacia' => 'EUR',
        'servia' => 'RSD',
        'marrocos' => 'MAD',
        'emirados arabes' => 'AED',
        'arabia saudita' => 'SAR',
        'tailandia' => 'THB',
        'vietname' => 'VND',
        'filipinas' => 'PHP',
        'paquistao' => 'PKR',
        'bangladesh' => 'BDT',
        'nigeria' => 'NGN',
        'quenia' => 'KES',
        'nova zelandia' => 'NZD'
    ];

    foreach ($map as $key => $currency) {
        if ($paisLower === $key || strpos($paisLower, $key) !== false || strpos($key, $paisLower) !== false) {
            return $currency;
        }
    }

    return 'EUR';
}

   
 // ============================================================
    // LOGIN VALIDATION
    // ============================================================

    public function validar_login($slug, $digits)
    {
        $results = $this->db->select(
            "SELECT * FROM sevenlux_clientes WHERE slug = :slug AND activo = 1 AND deleted_at IS NULL",
            [':slug' => $slug]
        );

        if (count($results) != 1) {
            error_log("⚠️ Cliente não encontrado ou inativo: $slug");
            return false;
        }

        $client = $results[0];
        $result = $this->verifyDigits($digits, $client->hash_digitos);
        
        if ($result) {
            error_log("✅ Login bem-sucedido: $slug");
            $this->resetAttempts($slug);
            return $client;
        } else {
            error_log("❌ Falha de login: $slug (código incorreto)");
            $this->incrementAttempts($slug, $client->tentativas_falhas);
            return false;
        }
    }

    // ============================================================
    // RECUPERAÇÃO DE CÓDIGO
    // ============================================================

 public function gerarTokenRecuperacaoCodigo($slug)
{
    $client = $this->db->select(
        "SELECT id_cliente, email, slug FROM sevenlux_clientes WHERE slug = :slug AND activo = 1",
        [':slug' => $slug]
    );
    
    if (!$client || empty($client)) {
        error_log("⚠️ Tentativa de recuperação para slug inexistente: $slug");
        return false;
    }
    
    $client = $client[0];
    $token = Store::criarHash(32);
    $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hora
    
    $this->db->update(
        "UPDATE sevenlux_clientes 
         SET recovery_token = :token, recovery_token_expires = :expires 
         WHERE id_cliente = :id",
        [
            ':token' => $token,
            ':expires' => $expiresAt,
            ':id' => $client->id_cliente
        ]
    );
    
    error_log("✅ Token de recuperação gerado para: $slug (expira em 1 hora)");
    
    return [
        'token' => $token,
        'email' => $client->email,
        'slug' => $client->slug
    ];
}

public function validarTokenRecuperacaoCodigo($token)
{
    $client = $this->db->select(
        "SELECT id_cliente, slug, email FROM sevenlux_clientes 
         WHERE recovery_token = :token AND activo = 1 
         AND (recovery_token_expires IS NULL OR recovery_token_expires > NOW())",
        [':token' => $token]
    );
    
    if (!$client || empty($client)) {
        error_log("❌ Token de recuperação inválido ou expirado: $token");
        return false;
    }
    
    error_log("✅ Token de recuperação válido para slug: {$client[0]->slug}");
    return $client[0];
}

 public function redefinirCodigoPorToken($token, $newDigits)
{
    if (strlen($newDigits) < 1 || strlen($newDigits) > 7 || !ctype_digit($newDigits)) {
        error_log("❌ Novo código inválido: deve ter entre 1 e 7 dígitos");
        return false;
    }

    $client = $this->db->select(
        "SELECT id_cliente, slug FROM sevenlux_clientes 
         WHERE recovery_token = :token AND activo = 1 
         AND (recovery_token_expires IS NULL OR recovery_token_expires > NOW())",
        [':token' => $token]
    );
    
    if (!$client || empty($client)) {
        error_log("❌ Token de recuperação inválido para redefinição: $token");
        return false;
    }
    
    $client = $client[0];
    $newHash = $this->hashDigits($newDigits);
    
    $this->db->update(
        "UPDATE sevenlux_clientes 
         SET hash_digitos = :hash, recovery_token = NULL, recovery_token_expires = NULL, updated_at = NOW() 
         WHERE id_cliente = :id",
        [
            ':hash' => $newHash,
            ':id' => $client->id_cliente
        ]
    );
    
    $this->addWarning($client->slug, "Código redefinido via email", $_SERVER['REMOTE_ADDR']);
    error_log("✅ Código redefinido com sucesso para: {$client->slug}");
    return true;
}

    // ============================================================
    // CONFIRMAÇÃO DE EMAIL
    // ============================================================

  public function confirmar_email($token)
{
    // 🔥 VERIFICAR SE O TOKEN NÃO EXPIROU
    $results = $this->db->select(
        "SELECT id_cliente FROM sevenlux_clientes 
         WHERE email_confirmation_token = :token AND activo = 0 
         AND (token_expires_at IS NULL OR token_expires_at > NOW())",
        [':token' => $token]
    );
    
    if (count($results) != 1) {
        error_log("❌ Tentativa de confirmar email com token inválido ou expirado: $token");
        return false;
    }
    
    $clientId = $results[0]->id_cliente;
    
    $this->db->update(
        "UPDATE sevenlux_clientes 
         SET email_confirmation_token = NULL, token_expires_at = NULL, email_confirmed_at = NOW(), activo = 1, updated_at = NOW() 
         WHERE id_cliente = :id_cliente",
        [':id_cliente' => $clientId]
    );
    
    error_log("✅ Email confirmado para cliente ID: $clientId");
    $this->createDefaultConfigurations($clientId);
    return true;
}


public function buscarPorTokenConfirmacao($token)
{
    $res = $this->db->select(
        "SELECT id_cliente, slug FROM sevenlux_clientes 
         WHERE email_confirmation_token = :token AND activo = 0 
         AND (token_expires_at IS NULL OR token_expires_at > NOW())",
        [':token' => $token]
    );
    return $res ? $res[0] : null;
}

 
    // ============================================================
    // VERIFICAÇÕES DE EXISTÊNCIA
    // ============================================================

    public function verificar_email_existe($email)
    {
        $results = $this->db->select(
            "SELECT id_cliente FROM sevenlux_clientes WHERE email = :email",
            [':email' => $email]
        );
        return count($results) != 0;
    }

    public function verificar_slug_existe($slug)
    {
        $results = $this->db->select(
            "SELECT id_cliente FROM sevenlux_clientes WHERE slug = :slug",
            [':slug' => $slug]
        );
        return count($results) != 0;
    }

    // ============================================================
    // CONTROLO DE TENTATIVAS
    // ============================================================

    private function resetAttempts($slug)
    {
        $this->db->update(
            "UPDATE sevenlux_clientes SET tentativas_falhas = 0, bloqueio_ate = 0 WHERE slug = :slug",
            [':slug' => $slug]
        );
    }

    private function incrementAttempts($slug, $currentAttempts)
    {
        $newAttempts = $currentAttempts + 1;
        $blockUntil = 0;

        if ($newAttempts >= 3) {
            $delay = pow(2, $newAttempts - 3);
            $blockUntil = time() + $delay;
            \core\classes\Logger::log('bloqueio_tentativa', "Bloqueio progressivo para $slug: $delay segundos");
        }
        if ($newAttempts >= 7) {
            $blockUntil = time() + 300;
            $this->addWarning($slug, "Limite de 7 tentativas atingido", $_SERVER['REMOTE_ADDR']);
             \core\classes\Logger::log('bloqueio_limite', "Limite de 7 tentativas atingido para $slug");
        }

        $this->db->update(
            "UPDATE sevenlux_clientes SET tentativas_falhas = :novas, bloqueio_ate = :bloqueio WHERE slug = :slug",
            [':novas' => $newAttempts, ':bloqueio' => $blockUntil, ':slug' => $slug]
        );
    }

    // ============================================================
    // WARNINGS / LOGS
    // ============================================================

    private function addWarning($slug, $reason, $ip)
    {
        $this->db->insert(
            "INSERT INTO sevenlux_warnings (slug, motivo, ip) VALUES (:slug, :motivo, :ip)",
            [':slug' => $slug, ':motivo' => $reason, ':ip' => $ip]
        );
    }

    // ============================================================
    // CONFIGURAÇÕES INICIAIS
    // ============================================================

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
            ['cliente_id' => $clientId, 'chave' => 'logo_parte1', 'valor' => 'Meu'],
            ['cliente_id' => $clientId, 'chave' => 'logo_parte2', 'valor' => 'Negócio'],
            ['cliente_id' => $clientId, 'chave' => 'logo_imagem', 'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'slogan', 'valor' => 'Soluções Personalizadas'],
            ['cliente_id' => $clientId, 'chave' => 'texto_descritivo', 'valor' => 'Bem-vindo ao seu novo site!'],
            ['cliente_id' => $clientId, 'chave' => 'email_contacto', 'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'telefone', 'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'endereco', 'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'meta_description', 'valor' => ''],
            ['cliente_id' => $clientId, 'chave' => 'meta_keywords', 'valor' => '']
        ];

        foreach ($configs as $config) {
            $this->db->insert(
                "INSERT INTO sevenlux_configuracoes_site (cliente_id, chave, valor) VALUES (:cliente_id, :chave, :valor)",
                $config
            );
        }
    }



    /**
     * Obtém o status da conta de um cliente
     * 
     * @param int $cliente_id
     * @return array
     */
    public function getStatusConta($cliente_id)
    {
        $result = $this->db->select(
            "SELECT status_conta, status_updated_at, eliminacao_solicitada_em, 
                    eliminacao_agendada_para, motivo_desativacao 
             FROM sevenlux_clientes 
             WHERE id_cliente = :cliente_id",
            [':cliente_id' => $cliente_id]
        );
        
        if (!$result || empty($result)) {
            return ['status' => 'ativa', 'mensagem' => 'Conta ativa'];
        }
        
        $data = $result[0];
        $status = $data->status_conta;
        $mensagens = [
            'ativa' => 'Conta ativa e visível ao público',
            'pausada' => 'Conta pausada. O site está offline temporariamente.',
            'desativada' => 'Conta desativada. O site está offline.',
            'pendente_eliminacao' => 'Eliminação pendente. A conta será apagada em breve.'
        ];
        
        $retorno = [
            'status' => $status,
            'mensagem' => $mensagens[$status] ?? 'Status desconhecido',
            'updated_at' => $data->status_updated_at
        ];
        
        if ($status === 'pendente_eliminacao' && $data->eliminacao_agendada_para) {
            $retorno['eliminacao_em'] = $data->eliminacao_agendada_para;
            $retorno['dias_restantes'] = $this->diasAteEliminacao($data->eliminacao_agendada_para);
        }
        
        if ($data->motivo_desativacao) {
            $retorno['motivo'] = $data->motivo_desativacao;
        }
        
        return $retorno;
    }

    /**
     * Calcula os dias até à eliminação
     */
    private function diasAteEliminacao($dataEliminacao)
    {
        if (!$dataEliminacao) return null;
        
        $agora = new \DateTime('now', new \DateTimeZone('Europe/Lisbon'));
        $eliminacao = new \DateTime($dataEliminacao, new \DateTimeZone('Europe/Lisbon'));
        $diferenca = $agora->diff($eliminacao);
        
        return $diferenca->days;
    }

    /**
     * Pausar a conta (site offline temporariamente)
     * 
     * @param int $cliente_id
     * @param string|null $motivo
     * @return bool
     */
    public function pausarConta($cliente_id, $motivo = null)
    {
        return $this->atualizarStatusConta($cliente_id, 'pausada', $motivo);
    }

    /**
     * Reativar a conta
     * 
     * @param int $cliente_id
     * @return bool
     */
    public function reativarConta($cliente_id)
    {
        return $this->atualizarStatusConta($cliente_id, 'ativa', null);
    }

    /**
     * Desativar a conta (offline indefinidamente)
     * 
     * @param int $cliente_id
     * @param string|null $motivo
     * @return bool
     */
    public function desativarConta($cliente_id, $motivo = null)
    {
        return $this->atualizarStatusConta($cliente_id, 'desativada', $motivo);
    }

    /**
     * Solicitar eliminação da conta (com período de carência)
     * 
     * @param int $cliente_id
     * @param string|null $motivo
     * @return bool
     */
    public function solicitarEliminacaoConta($cliente_id, $motivo = null)
    {
        $agora = date('Y-m-d H:i:s');
        $eliminacaoEm = date('Y-m-d H:i:s', strtotime('+30 days')); // 30 dias de carência
        
        return $this->atualizarStatusConta($cliente_id, 'pendente_eliminacao', $motivo, $eliminacaoEm);
    }

    /**
     * Cancelar a solicitação de eliminação
     * 
     * @param int $cliente_id
     * @return bool
     */
    public function cancelarEliminacaoConta($cliente_id)
    {
        return $this->atualizarStatusConta($cliente_id, 'ativa', null);
    }

    /**
     * Eliminar permanentemente a conta (apenas para uso interno)
     * 
     * @param int $cliente_id
     * @return bool
     */
    public function eliminarPermanentemente($cliente_id)
    {
        // Backup dos dados antes de eliminar (opcional)
        $this->fazerBackupCliente($cliente_id);
        
        // Eliminar dados do cliente (cascade)
        $this->db->delete(
            "DELETE FROM sevenlux_clientes WHERE id_cliente = :cliente_id",
            [':cliente_id' => $cliente_id]
        );
        
        \core\classes\Logger::log('eliminar_conta', "Conta eliminada permanentemente: ID $cliente_id");
        return true;
    }

    /**
     * Atualiza o status da conta
     * 
     * @param int $cliente_id
     * @param string $status
     * @param string|null $motivo
     * @param string|null $eliminacaoAgendada
     * @return bool
     */
    private function atualizarStatusConta($cliente_id, $status, $motivo = null, $eliminacaoAgendada = null)
    {
        $agora = date('Y-m-d H:i:s');
        
        $params = [
            ':cliente_id' => $cliente_id,
            ':status' => $status,
            ':status_updated_at' => $agora
        ];
        
        $sql = "UPDATE sevenlux_clientes 
                SET status_conta = :status, 
                    status_updated_at = :status_updated_at";
        
        if ($motivo !== null) {
            $sql .= ", motivo_desativacao = :motivo";
            $params[':motivo'] = $motivo;
        }
        
        if ($status === 'pendente_eliminacao' && $eliminacaoAgendada) {
            $sql .= ", eliminacao_solicitada_em = :solicitada, 
                     eliminacao_agendada_para = :agendada";
            $params[':solicitada'] = $agora;
            $params[':agendada'] = $eliminacaoAgendada;
        } elseif ($status !== 'pendente_eliminacao') {
            $sql .= ", eliminacao_solicitada_em = NULL, 
                     eliminacao_agendada_para = NULL";
        }
        
        $sql .= " WHERE id_cliente = :cliente_id";
        
        $result = $this->db->update($sql, $params);
        
        // Log da ação
        $acao = "status_conta_$status";
        $detalhes = "Conta ID $cliente_id alterada para: $status";
        if ($motivo) {
            $detalhes .= " - Motivo: $motivo";
        }
        \core\classes\Logger::log($acao, $detalhes, $cliente_id);
        
        return $result;
    }

    /**
     * Faz backup dos dados do cliente antes de eliminar
     */
    private function fazerBackupCliente($cliente_id)
    {
        // Buscar dados do cliente
        $cliente = $this->db->select(
            "SELECT * FROM sevenlux_clientes WHERE id_cliente = :id",
            [':id' => $cliente_id]
        );
        
        if ($cliente) {
            // Salvar em ficheiro JSON (pode ser adaptado para tabela de backups)
            $backupDir = __DIR__ . '/../../backups/';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0777, true);
            }
            
            $filename = $backupDir . 'cliente_' . $cliente_id . '_' . date('Y-m-d_H-i-s') . '.json';
            file_put_contents($filename, json_encode($cliente, JSON_PRETTY_PRINT));
            
            \core\classes\Logger::log('backup_cliente', "Backup criado: $filename", $cliente_id);
        }
    }

    /**
     * Verifica se a conta está ativa (para exibir o site)
     * 
     * @param int $cliente_id
     * @return bool
     */
    public function isContaAtiva($cliente_id)
    {
        $result = $this->db->select(
            "SELECT status_conta FROM sevenlux_clientes 
             WHERE id_cliente = :cliente_id AND activo = 1",
            [':cliente_id' => $cliente_id]
        );
        
        if (!$result || empty($result)) {
            return false;
        }
        
        return $result[0]->status_conta === 'ativa';
    }

    /**
     * Processa eliminações agendadas (para ser executado num cron)
     */
    public function processarEliminacoesAgendadas()
    {
        $result = $this->db->select(
            "SELECT id_cliente FROM sevenlux_clientes 
             WHERE status_conta = 'pendente_eliminacao' 
             AND eliminacao_agendada_para <= NOW()"
        );
        
        if ($result) {
            foreach ($result as $cliente) {
                $this->eliminarPermanentemente($cliente->id_cliente);
                \core\classes\Logger::log('eliminacao_automatica', "Eliminação automática: ID {$cliente->id_cliente}");
            }
        }
    }


    
}