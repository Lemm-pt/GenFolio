<?php
/**
 * Configuracao Model
 * 
 * Manages site configuration settings (logo, slogan, contact info, etc.)
 * for each client (tenant). Settings are cached after the first load.
 * 
 * @package SevenLux
 */

namespace core\models;

use core\classes\Database;
use Exception;

class Configuracao
{
    private $bd;
    private $cliente_id;
    private $cache = [];

    /**
     * Constructor.
     *
     * @param int|null $cliente_id Client ID (defaults to logged-in client or 1)
     * @param bool $silent Se true, não lança exceção se cliente_id for inválido
     */
    public function __construct($cliente_id = null, $silent = false)
    {
        $this->bd = new Database();
        
        // Determinar cliente_id
        if ($cliente_id === null) {
            $cliente_id = $_SESSION['cliente_id'] ?? null;
        }
        
        // Se não houver cliente_id e não for silencioso, usar fallback
        if ($cliente_id === null) {
            if (!$silent) {
                // Em vez de lançar exceção, usar o cliente padrão (vitrine-demo)
                $cliente_id = 1; // vitrine-demo
            } else {
                // Se for silencioso, simplesmente não carregar nada
                $this->cliente_id = null;
                return;
            }
        }
        
        $this->cliente_id = (int) $cliente_id;
        $this->carregarTodas();
    }

    /**
     * Loads all configuration entries for the client into the cache.
     */
    private function carregarTodas()
    {
        if ($this->cliente_id === null) {
            return;
        }
        
        $res = $this->bd->select(
            "SELECT chave, valor FROM sevenlux_configuracoes_site WHERE cliente_id = :cliente_id",
            [':cliente_id' => $this->cliente_id]
        );
        
        if ($res && is_array($res)) {
            foreach ($res as $row) {
                if (isset($row->chave)) {
                    $this->cache[$row->chave] = $row->valor ?? '';
                }
            }
        }
    }

    /**
     * Retrieves a configuration value by its key.
     *
     * @param string $chave
     * @param mixed $padrao Default value if key not found.
     * @return mixed
     */
    public function get($chave, $padrao = '')
    {
        return $this->cache[$chave] ?? $padrao;
    }

    /**
     * Sets a configuration value (inserts or updates).
     *
     * @param string $chave
     * @param mixed $valor
     */
    public function set($chave, $valor)
    {
        if ($this->cliente_id === null) {
            return; // Não fazer nada se não houver cliente
        }
        
        // Verificar se já existe
        $existe = $this->bd->select(
            "SELECT id FROM sevenlux_configuracoes_site WHERE cliente_id = :cliente_id AND chave = :chave",
            [':cliente_id' => $this->cliente_id, ':chave' => $chave]
        );

        if ($existe && !empty($existe)) {
            // Atualizar
            $this->bd->update(
                "UPDATE sevenlux_configuracoes_site SET valor = :valor WHERE cliente_id = :cliente_id AND chave = :chave",
                [':valor' => $valor, ':cliente_id' => $this->cliente_id, ':chave' => $chave]
            );
        } else {
            // Inserir
            $this->bd->insert(
                "INSERT INTO sevenlux_configuracoes_site (cliente_id, chave, valor) VALUES (:cliente_id, :chave, :valor)",
                [':cliente_id' => $this->cliente_id, ':chave' => $chave, ':valor' => $valor]
            );
        }

        $this->cache[$chave] = $valor;
    }

    /**
     * Returns all cached configuration entries.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->cache;
    }
    
    /**
     * Verifica se uma chave existe
     *
     * @param string $chave
     * @return bool
     */
    public function has($chave)
    {
        return isset($this->cache[$chave]);
    }


     // ============================================================
    // 🔥 REDES SOCIAIS - NOVOS MÉTODOS
    // ============================================================

    /**
     * Obtém todas as redes sociais configuradas
     * 
     * @return array
     */
    public function getSocialLinks()
    {
        $networks = [
            'facebook' => ['icon' => 'fa-facebook-f', 'label' => 'Facebook', 'color' => '#1877F2'],
            'twitter' => ['icon' => 'fa-x-twitter', 'label' => 'X (Twitter)', 'color' => '#000000'],
            'instagram' => ['icon' => 'fa-instagram', 'label' => 'Instagram', 'color' => '#E4405F'],
            'linkedin' => ['icon' => 'fa-linkedin-in', 'label' => 'LinkedIn', 'color' => '#0A66C2'],
            'youtube' => ['icon' => 'fa-youtube', 'label' => 'YouTube', 'color' => '#FF0000'],
            'tiktok' => ['icon' => 'fa-tiktok', 'label' => 'TikTok', 'color' => '#000000'],
            'pinterest' => ['icon' => 'fa-pinterest-p', 'label' => 'Pinterest', 'color' => '#E60023'],
            'whatsapp' => ['icon' => 'fa-whatsapp', 'label' => 'WhatsApp', 'color' => '#25D366'],
            'telegram' => ['icon' => 'fa-telegram-plane', 'label' => 'Telegram', 'color' => '#26A5E4'],
            'github' => ['icon' => 'fa-github', 'label' => 'GitHub', 'color' => '#181717'],
        ];

        $links = [];
        foreach ($networks as $key => $info) {
            $url = $this->get('social_' . $key);
            if (!empty($url)) {
                $links[$key] = [
                    'url' => $url,
                    'icon' => $info['icon'],
                    'label' => $info['label'],
                    'color' => $info['color']
                ];
            }
        }
        return $links;
    }

    /**
     * Verifica se há redes sociais configuradas
     * 
     * @return bool
     */
    public function hasSocialLinks()
    {
        return !empty($this->getSocialLinks());
    }

    /**
     * Obtém uma rede social específica
     * 
     * @param string $network facebook|twitter|instagram|linkedin|youtube|tiktok|pinterest|whatsapp|telegram|github
     * @return array|null
     */
    public function getSocialLink($network)
    {
        $links = $this->getSocialLinks();
        return $links[$network] ?? null;
    }





}