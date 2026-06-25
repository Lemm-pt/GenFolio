<?php
/**
 * Logger - Sistema de Logs de Auditoria
 * 
 * @package SevenLux
 */

namespace core\classes;

class Logger
{
    /**
     * Regista uma ação no log de auditoria
     * 
     * @param string $acao Tipo de ação (ex: 'login', 'logout', 'criar_cliente')
     * @param string $detalhes Descrição detalhada
     * @param int|null $cliente_id ID do cliente (opcional)
     * @param string|null $usuario Nome do utilizador (opcional)
     */
    public static function log($acao, $detalhes = null, $cliente_id = null, $usuario = null)
    {
        $db = new Database();
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'desconhecido';
        
        // Limitar tamanho dos detalhes
        if (strlen($detalhes) > 1000) {
            $detalhes = substr($detalhes, 0, 1000) . '... (truncado)';
        }
        
        $params = [
            ':cliente_id' => $cliente_id,
            ':usuario' => $usuario,
            ':acao' => $acao,
            ':detalhes' => $detalhes,
            ':ip' => $ip,
            ':user_agent' => $userAgent
        ];
        
        $db->insert("
            INSERT INTO sevenlux_audit_logs 
            (cliente_id, usuario, acao, detalhes, ip, user_agent, created_at) 
            VALUES 
            (:cliente_id, :usuario, :acao, :detalhes, :ip, :user_agent, NOW())
        ", $params);
        
        error_log("📝 AUDIT LOG: $acao - $detalhes (IP: $ip)");
    }
}