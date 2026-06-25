<?php
/**
 * RateLimiter - Controlo de tentativas por IP
 * 
 * @package SevenLux
 */

namespace core\classes;

class RateLimiter
{
    private $db;
    private $ip;
    
    /**
     * Limites por ação
     */
    private $limits = [
        'login' => [
            'max_attempts' => 5,
            'time_window' => 60,        // 1 minuto
            'block_time' => 300,        // 5 minutos (após exceder)
        ],
        'registro' => [
            'max_attempts' => 3,
            'time_window' => 3600,      // 1 hora
            'block_time' => 7200,       // 2 horas
        ],
        'recuperacao' => [
            'max_attempts' => 3,
            'time_window' => 3600,      // 1 hora
            'block_time' => 7200,       // 2 horas
        ],
        'contacto' => [
            'max_attempts' => 3,
            'time_window' => 300,       // 5 minutos
            'block_time' => 1800,       // 30 minutos
        ],
    ];
    
    public function __construct()
    {
        $this->db = new Database();
        $this->ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Verifica se o IP pode realizar a ação
     * 
     * @param string $acao login|registro|recuperacao|contacto
     * @return bool True se pode, False se está bloqueado
     */
    public function podeRealizar($acao)
{
    if (!isset($this->limits[$acao])) {
        return true;
    }
    
    $limit = $this->limits[$acao];
    
    $registro = $this->db->select(
        "SELECT * FROM sevenlux_rate_limits 
         WHERE ip = :ip AND acao = :acao",
        [':ip' => $this->ip, ':acao' => $acao]
    );
    
    if (!$registro || empty($registro)) {
        return true;
    }
    
    $registro = $registro[0];
    
    // Verificar se está bloqueado
    if ($registro->bloqueado_ate && strtotime($registro->bloqueado_ate) > time()) {
        return false;
    }
    
    // Verificar se o período já passou (reset automático - mas sem apagar)
    $tempoDecorrido = time() - strtotime($registro->primeira_tentativa);
    if ($tempoDecorrido > $limit['time_window']) {
        // 🔥 NÃO APAGA - apenas reseta as tentativas
        $this->db->update(
            "UPDATE sevenlux_rate_limits 
             SET tentativas = 0, primeira_tentativa = NOW(), ultima_tentativa = NOW(), bloqueado_ate = NULL
             WHERE ip = :ip AND acao = :acao",
            [':ip' => $this->ip, ':acao' => $acao]
        );
        error_log("🔍 RATE LIMIT: Reset automático (período expirado) para IP {$this->ip}, ação: $acao");
        return true;
    }
    
    // Verificar se excedeu o limite
    if ($registro->tentativas >= $limit['max_attempts']) {
        $bloqueioAte = date('Y-m-d H:i:s', time() + $limit['block_time']);
        $this->db->update(
            "UPDATE sevenlux_rate_limits 
             SET bloqueado_ate = :bloqueio_ate, ultima_tentativa = NOW() 
             WHERE ip = :ip AND acao = :acao",
            [
                ':bloqueio_ate' => $bloqueioAte,
                ':ip' => $this->ip,
                ':acao' => $acao
            ]
        );
        
        Logger::log('rate_limit_block', "IP {$this->ip} bloqueado para $acao até $bloqueioAte");
        return false;
    }
    
    return true;
}
    
    /**
     * Regista uma tentativa
     * 
     * @param string $acao login|registro|recuperacao|contacto
     * @return int Número de tentativas
     */
    public function registrarTentativa($acao)
{
    // Se não houver limite definido, não registar
    if (!isset($this->limits[$acao])) {
        return 0;
    }
    
    // Verificar se já existe registo
    $registro = $this->db->select(
        "SELECT * FROM sevenlux_rate_limits 
         WHERE ip = :ip AND acao = :acao",
        [':ip' => $this->ip, ':acao' => $acao]
    );
    
    if ($registro && !empty($registro)) {
        $registro = $registro[0];
        $tempoDecorrido = time() - strtotime($registro->primeira_tentativa);
        $limit = $this->limits[$acao];
        
        // Se já passou o período, reset
        if ($tempoDecorrido > $limit['time_window']) {
            $this->reset($acao);
            return $this->registrarTentativa($acao);
        }
        
        // Incrementar tentativas
        $this->db->update(
            "UPDATE sevenlux_rate_limits 
             SET tentativas = tentativas + 1, ultima_tentativa = NOW() 
             WHERE ip = :ip AND acao = :acao",
            [':ip' => $this->ip, ':acao' => $acao]
        );
        
        // Buscar novo valor
        $novo = $this->db->select(
            "SELECT tentativas FROM sevenlux_rate_limits 
             WHERE ip = :ip AND acao = :acao",
            [':ip' => $this->ip, ':acao' => $acao]
        );
        
        return $novo ? (int)$novo[0]->tentativas : 0;
    } else {
        // 🔥 CRIAR NOVO REGISTO - usando método direto
        $sql = "INSERT INTO sevenlux_rate_limits 
                (ip, acao, tentativas, primeira_tentativa, ultima_tentativa) 
                VALUES 
                (:ip, :acao, 1, NOW(), NOW())";
        
        $params = [
            ':ip' => $this->ip,
            ':acao' => $acao
        ];
        
        $result = $this->db->insert($sql, $params);
        
        // 🔥 DEBUG - verificar se foi inserido
        error_log("🔍 RateLimiter: Inserindo registo para IP {$this->ip}, ação: $acao, resultado: " . ($result ? 'SUCESSO' : 'FALHA'));
        
        return 1;
    }
}
    
    /**
     * Reseta as tentativas para uma ação
     * 
     * @param string $acao
     */
    public function reset($acao)
{
    // Em vez de apagar, apenas desativa o bloqueio
    $this->db->update(
        "UPDATE sevenlux_rate_limits 
         SET bloqueado_ate = NULL, tentativas = 0 
         WHERE ip = :ip AND acao = :acao",
        [':ip' => $this->ip, ':acao' => $acao]
    );
    
    error_log("🔍 RATE LIMIT: Reset para IP {$this->ip}, ação: $acao (mantido na BD)");
}
    
    /**
     * Obtém o tempo restante de bloqueio (em segundos)
     * 
     * @param string $acao
     * @return int Segundos restantes (0 se não estiver bloqueado)
     */
    public function getTempoRestante($acao)
    {
        $registro = $this->db->select(
            "SELECT bloqueado_ate FROM sevenlux_rate_limits 
             WHERE ip = :ip AND acao = :acao",
            [':ip' => $this->ip, ':acao' => $acao]
        );
        
        if (!$registro || empty($registro) || !$registro[0]->bloqueado_ate) {
            return 0;
        }
        
        $restante = strtotime($registro[0]->bloqueado_ate) - time();
        return $restante > 0 ? $restante : 0;
    }



    /**
 * Método de debug para verificar se a tabela está acessível
 */
public function testarConexao()
{
    error_log("🔍 TESTE RATE LIMIT: Verificando tabela...");
    
    // Verificar se a tabela existe
    $tabela = $this->db->select("SHOW TABLES LIKE 'sevenlux_rate_limits'");
    error_log("🔍 TESTE RATE LIMIT: Tabela existe? " . ($tabela ? 'SIM' : 'NÃO'));
    
    // Tentar inserir um registo de teste
    $sql = "INSERT INTO sevenlux_rate_limits 
            (ip, acao, tentativas, primeira_tentativa, ultima_tentativa) 
            VALUES 
            (:ip, 'teste', 1, NOW(), NOW())";
    
    $params = [':ip' => $this->ip];
    $result = $this->db->insert($sql, $params);
    
    error_log("🔍 TESTE RateLimiter: " . ($result ? '✅ Inserido com sucesso (ID: ' . $result . ')' : '❌ Falha ao inserir'));
    
    // Verificar se existe
    $check = $this->db->select(
        "SELECT * FROM sevenlux_rate_limits WHERE ip = :ip AND acao = 'teste'",
        [':ip' => $this->ip]
    );
    
    error_log("🔍 TESTE RateLimiter: Registos encontrados: " . ($check ? count($check) : 0));
    
    if ($check) {
        error_log("🔍 TESTE RateLimiter: Dados: " . print_r($check[0], true));
    }
    
    // NÃO APAGAR o registo de teste!
    // $this->db->delete(...);
    
    return $result;
}
}