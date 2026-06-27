<?php
/**
 * Visitas Model - Contador de Visitas por Slug
 * 
 * @package SevenLux
 */

namespace core\models;

use core\classes\Database;

class Visitas
{
    private $db;
    private $cliente_id;
    private $slug;

    public function __construct($cliente_id = null, $slug = null)
    {
        $this->db = new Database();
        $this->cliente_id = $cliente_id ?? (defined('CLIENTE_ID') ? CLIENTE_ID : 1);
        $this->slug = $slug ?? (defined('CLIENTE_SLUG') ? CLIENTE_SLUG : 'vitrine-demo');
    }

    /**
     * Regista uma nova visita
     * 
     * @param string $url URL da página visitada
     * @return bool
     */
    public function registrarVisita($url = null)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'desconhecido';
        $referer = $_SERVER['HTTP_REFERER'] ?? null;
        $url = $url ?? $_SERVER['REQUEST_URI'] ?? '/';

        // Verificar se já houve visita deste IP nos últimos 30 minutos
        // (para não contar visitas repetidas do mesmo utilizador)
        $existe = $this->db->select(
            "SELECT id FROM sevenlux_visitas 
             WHERE ip = :ip AND cliente_id = :cliente_id 
             AND created_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)",
            [
                ':ip' => $ip,
                ':cliente_id' => $this->cliente_id
            ]
        );

        if ($existe && !empty($existe)) {
            // Já registou uma visita recente, não contar novamente
            return false;
        }

        // Inserir visita
        $result = $this->db->insert(
            "INSERT INTO sevenlux_visitas 
             (cliente_id, slug, ip, user_agent, url, referer, created_at) 
             VALUES 
             (:cliente_id, :slug, :ip, :user_agent, :url, :referer, NOW())",
            [
                ':cliente_id' => $this->cliente_id,
                ':slug' => $this->slug,
                ':ip' => $ip,
                ':user_agent' => $userAgent,
                ':url' => $url,
                ':referer' => $referer
            ]
        );

        if ($result) {
            // Atualizar resumo
            $this->atualizarResumo();
            return true;
        }

        return false;
    }

    /**
     * Atualiza o resumo de visitas
     */
    private function atualizarResumo()
    {
        // Verificar se já existe resumo para este cliente
        $existe = $this->db->select(
            "SELECT id FROM sevenlux_visitas_resumo WHERE cliente_id = :cliente_id",
            [':cliente_id' => $this->cliente_id]
        );

        if ($existe && !empty($existe)) {
            // Atualizar resumo existente
            $this->db->update(
                "UPDATE sevenlux_visitas_resumo 
                 SET 
                    total_visitas = (SELECT COUNT(*) FROM sevenlux_visitas WHERE cliente_id = :cliente_id),
                    visitas_hoje = (SELECT COUNT(*) FROM sevenlux_visitas WHERE cliente_id = :cliente_id AND DATE(created_at) = CURDATE()),
                    visitas_semana = (SELECT COUNT(*) FROM sevenlux_visitas WHERE cliente_id = :cliente_id AND YEARWEEK(created_at) = YEARWEEK(NOW())),
                    visitas_mes = (SELECT COUNT(*) FROM sevenlux_visitas WHERE cliente_id = :cliente_id AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()))
                 WHERE cliente_id = :cliente_id",
                [':cliente_id' => $this->cliente_id]
            );
        } else {
            // Criar resumo
            $this->db->insert(
                "INSERT INTO sevenlux_visitas_resumo 
                 (cliente_id, slug, total_visitas, visitas_hoje, visitas_semana, visitas_mes) 
                 VALUES 
                 (
                     :cliente_id, 
                     :slug, 
                     (SELECT COUNT(*) FROM sevenlux_visitas WHERE cliente_id = :cliente_id),
                     (SELECT COUNT(*) FROM sevenlux_visitas WHERE cliente_id = :cliente_id AND DATE(created_at) = CURDATE()),
                     (SELECT COUNT(*) FROM sevenlux_visitas WHERE cliente_id = :cliente_id AND YEARWEEK(created_at) = YEARWEEK(NOW())),
                     (SELECT COUNT(*) FROM sevenlux_visitas WHERE cliente_id = :cliente_id AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()))
                 )",
                [
                    ':cliente_id' => $this->cliente_id,
                    ':slug' => $this->slug
                ]
            );
        }
    }

    /**
     * Obtém o total de visitas para um cliente
     * 
     * @param int|null $cliente_id
     * @return int
     */
    public function getTotalVisitas($cliente_id = null)
    {
        $id = $cliente_id ?? $this->cliente_id;
        
        $result = $this->db->select(
            "SELECT total_visitas FROM sevenlux_visitas_resumo WHERE cliente_id = :cliente_id",
            [':cliente_id' => $id]
        );

        return ($result && !empty($result)) ? (int)$result[0]->total_visitas : 0;
    }

    /**
     * Obtém as visitas de hoje para um cliente
     */
    public function getVisitasHoje($cliente_id = null)
    {
        $id = $cliente_id ?? $this->cliente_id;
        
        $result = $this->db->select(
            "SELECT visitas_hoje FROM sevenlux_visitas_resumo WHERE cliente_id = :cliente_id",
            [':cliente_id' => $id]
        );

        return ($result && !empty($result)) ? (int)$result[0]->visitas_hoje : 0;
    }

    /**
     * Obtém as visitas da semana para um cliente
     */
    public function getVisitasSemana($cliente_id = null)
    {
        $id = $cliente_id ?? $this->cliente_id;
        
        $result = $this->db->select(
            "SELECT visitas_semana FROM sevenlux_visitas_resumo WHERE cliente_id = :cliente_id",
            [':cliente_id' => $id]
        );

        return ($result && !empty($result)) ? (int)$result[0]->visitas_semana : 0;
    }

    /**
     * Obtém as visitas do mês para um cliente
     */
    public function getVisitasMes($cliente_id = null)
    {
        $id = $cliente_id ?? $this->cliente_id;
        
        $result = $this->db->select(
            "SELECT visitas_mes FROM sevenlux_visitas_resumo WHERE cliente_id = :cliente_id",
            [':cliente_id' => $id]
        );

        return ($result && !empty($result)) ? (int)$result[0]->visitas_mes : 0;
    }

    /**
     * Obtém todas as estatísticas para um cliente
     * 
     * @param int|null $cliente_id
     * @return array
     */
    public function getEstatisticas($cliente_id = null)
    {
        $id = $cliente_id ?? $this->cliente_id;
        
        $result = $this->db->select(
            "SELECT * FROM sevenlux_visitas_resumo WHERE cliente_id = :cliente_id",
            [':cliente_id' => $id]
        );

        if ($result && !empty($result)) {
            return [
                'total' => (int)$result[0]->total_visitas,
                'hoje' => (int)$result[0]->visitas_hoje,
                'semana' => (int)$result[0]->visitas_semana,
                'mes' => (int)$result[0]->visitas_mes
            ];
        }

        return [
            'total' => 0,
            'hoje' => 0,
            'semana' => 0,
            'mes' => 0
        ];
    }

    /**
     * Obtém as últimas visitas para um cliente
     * 
     * @param int $limit
     * @return array
     */
    public function getUltimasVisitas($limit = 10)
    {
        $result = $this->db->select(
            "SELECT * FROM sevenlux_visitas 
             WHERE cliente_id = :cliente_id 
             ORDER BY created_at DESC 
             LIMIT :limit",
            [
                ':cliente_id' => $this->cliente_id,
                ':limit' => (int)$limit
            ]
        );

        return $result ?? [];
    }

    /**
     * Reseta o contador de visitas para um cliente
     */
    public function resetarContador($cliente_id = null)
    {
        $id = $cliente_id ?? $this->cliente_id;
        
        // Apagar visitas
        $this->db->delete(
            "DELETE FROM sevenlux_visitas WHERE cliente_id = :cliente_id",
            [':cliente_id' => $id]
        );

        // Resetar resumo
        $this->db->update(
            "UPDATE sevenlux_visitas_resumo 
             SET total_visitas = 0, visitas_hoje = 0, visitas_semana = 0, visitas_mes = 0 
             WHERE cliente_id = :cliente_id",
            [':cliente_id' => $id]
        );
    }
}