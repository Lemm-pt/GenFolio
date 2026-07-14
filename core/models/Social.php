<?php
/**
 * Social Model - Gestão de Redes Sociais
 * 
 * @package SevenLux
 */

namespace core\models;

use core\classes\Database;

class Social
{
    private $db;
    private $cliente_id;
    private $cache = [];

    /**
     * Configuração padrão das redes sociais
     */
    private $redesPadrao = [
        'facebook' => [
            'icone' => 'fa-facebook',
            'cor' => '#1877F2',
            'label' => 'Facebook',
            'ordem' => 1
        ],
        'instagram' => [
            'icone' => 'fa-instagram',
            'cor' => '#E4405F',
            'label' => 'Instagram',
            'ordem' => 2
        ],
        'twitter' => [
            'icone' => 'fa-x-twitter',
            'cor' => '#000000',
            'label' => 'X (Twitter)',
            'ordem' => 3
        ],
        'linkedin' => [
            'icone' => 'fa-linkedin',
            'cor' => '#0A66C2',
            'label' => 'LinkedIn',
            'ordem' => 4
        ],
        'youtube' => [
            'icone' => 'fa-youtube',
            'cor' => '#FF0000',
            'label' => 'YouTube',
            'ordem' => 5
        ],
        'tiktok' => [
            'icone' => 'fa-tiktok',
            'cor' => '#000000',
            'label' => 'TikTok',
            'ordem' => 6
        ],
        'whatsapp' => [
            'icone' => 'fa-whatsapp',
            'cor' => '#25D366',
            'label' => 'WhatsApp',
            'ordem' => 7
        ],
        'pinterest' => [
            'icone' => 'fa-pinterest',
            'cor' => '#E60023',
            'label' => 'Pinterest',
            'ordem' => 8
        ],
        'telegram' => [
            'icone' => 'fa-telegram',
            'cor' => '#26A5E4',
            'label' => 'Telegram',
            'ordem' => 9
        ],
        'spotify' => [
            'icone' => 'fa-spotify',
            'cor' => '#1DB954',
            'label' => 'Spotify',
            'ordem' => 10
        ],
        'github' => [
            'icone' => 'fa-github',
            'cor' => '#333333',
            'label' => 'GitHub',
            'ordem' => 11
        ],
        'discord' => [
            'icone' => 'fa-discord',
            'cor' => '#5865F2',
            'label' => 'Discord',
            'ordem' => 12
        ],
        'threads' => [
            'icone' => 'fa-threads',
            'cor' => '#000000',
            'label' => 'Threads',
            'ordem' => 13
        ]
    ];

    public function __construct($cliente_id = null)
    {
        $this->db = new Database();
        $this->cliente_id = $cliente_id ?? (defined('CLIENTE_ID') ? CLIENTE_ID : 1);
        $this->carregarRedes();
    }

    /**
     * Carrega todas as redes do cliente para cache
     */
    private function carregarRedes()
    {
        $result = $this->db->select(
            "SELECT * FROM sevenlux_social 
             WHERE cliente_id = :cliente_id AND ativo = 1
             ORDER BY ordem ASC",
            [':cliente_id' => $this->cliente_id]
        );

        if ($result) {
            foreach ($result as $row) {
                $this->cache[$row->rede] = [
                    'id' => $row->id,
                    'rede' => $row->rede,
                    'url' => $row->url,
                    'icone' => $row->icone,
                    'cor' => $row->cor,
                    'label' => $this->redesPadrao[$row->rede]['label'] ?? ucfirst($row->rede),
                    'ordem' => $row->ordem,
                    'ativo' => $row->ativo
                ];
            }
        }
    }

    /**
     * Obtém todas as redes sociais ativas
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->cache;
    }

    /**
     * Obtém apenas as redes com URL preenchida
     * 
     * @return array
     */
    public function getAtivas()
    {
        return array_filter($this->cache, function($rede) {
            return !empty($rede['url']);
        });
    }

    /**
     * Obtém uma rede específica
     * 
     * @param string $rede
     * @return array|null
     */
    public function get($rede)
    {
        return $this->cache[$rede] ?? null;
    }

    /**
     * Atualiza ou cria uma rede social
     * 
     * @param string $rede
     * @param string $url
     * @param bool $ativo
     * @return bool
     */
    public function set($rede, $url, $ativo = true)
    {
        // Validar rede
        if (!isset($this->redesPadrao[$rede])) {
            return false;
        }

        $icone = $this->redesPadrao[$rede]['icone'];
        $cor = $this->redesPadrao[$rede]['cor'];
        $ordem = $this->redesPadrao[$rede]['ordem'];

        // Verificar se já existe
        $existe = $this->db->select(
            "SELECT id FROM sevenlux_social 
             WHERE cliente_id = :cliente_id AND rede = :rede",
            [':cliente_id' => $this->cliente_id, ':rede' => $rede]
        );

        if ($existe && !empty($existe)) {
            // Atualizar
            $this->db->update(
                "UPDATE sevenlux_social 
                 SET url = :url, ativo = :ativo, updated_at = NOW()
                 WHERE cliente_id = :cliente_id AND rede = :rede",
                [
                    ':cliente_id' => $this->cliente_id,
                    ':rede' => $rede,
                    ':url' => $url,
                    ':ativo' => $ativo ? 1 : 0
                ]
            );
        } else {
            // Inserir
            $this->db->insert(
                "INSERT INTO sevenlux_social (cliente_id, rede, url, icone, cor, ordem, ativo) 
                 VALUES (:cliente_id, :rede, :url, :icone, :cor, :ordem, :ativo)",
                [
                    ':cliente_id' => $this->cliente_id,
                    ':rede' => $rede,
                    ':url' => $url,
                    ':icone' => $icone,
                    ':cor' => $cor,
                    ':ordem' => $ordem,
                    ':ativo' => $ativo ? 1 : 0
                ]
            );
        }

        // Atualizar cache
        $this->cache[$rede] = [
            'rede' => $rede,
            'url' => $url,
            'icone' => $icone,
            'cor' => $cor,
            'label' => $this->redesPadrao[$rede]['label'],
            'ordem' => $ordem,
            'ativo' => $ativo
        ];

        return true;
    }

    /**
     * Remove uma rede social (desativa)
     * 
     * @param string $rede
     * @return bool
     */
    public function delete($rede)
    {
        $this->db->update(
            "UPDATE sevenlux_social 
             SET ativo = 0, updated_at = NOW()
             WHERE cliente_id = :cliente_id AND rede = :rede",
            [':cliente_id' => $this->cliente_id, ':rede' => $rede]
        );

        if (isset($this->cache[$rede])) {
            $this->cache[$rede]['ativo'] = false;
        }

        return true;
    }

    /**
     * Reseta as redes para o padrão
     */
    public function resetToDefault()
    {
        foreach ($this->redesPadrao as $rede => $config) {
            $this->set($rede, '');
        }
    }

    /**
     * Obtém a lista de todas as redes disponíveis (padrão)
     * 
     * @return array
     */
    public function getRedesDisponiveis()
    {
        return $this->redesPadrao;
    }

    /**
     * Gera HTML para exibir os ícones
     * 
     * @param string $tamanho 'sm'|'md'|'lg'
     * @param string $classes CSS adicionais
     * @return string
     */
    public function render($tamanho = 'md', $classes = '')
    {
        $redes = $this->getAtivas();
        if (empty($redes)) {
            return '';
        }

        $tamanhos = [
            'sm' => 'font-size: 1.2rem; width: 32px; height: 32px;',
            'md' => 'font-size: 1.5rem; width: 40px; height: 40px;',
            'lg' => 'font-size: 2rem; width: 50px; height: 50px;'
        ];

        $style = $tamanhos[$tamanho] ?? $tamanhos['md'];

        $html = '<div class="social-icons ' . $classes . '">';
        foreach ($redes as $rede) {
            $html .= sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" 
                    class="social-icon" 
                    style="%s color: %s; display: inline-flex; align-items: center; justify-content: center; 
                           border-radius: 50%%; transition: all 0.3s ease; text-decoration: none;"
                    title="%s"
                    onmouseover="this.style.transform=\'scale(1.15)\'; this.style.boxShadow=\'0 0 20px %s40\'"
                    onmouseout="this.style.transform=\'scale(1)\'; this.style.boxShadow=\'none\'">
                    <i class="fab %s"></i>
                </a>',
                htmlspecialchars($rede['url']),
                $style,
                $rede['cor'],
                htmlspecialchars($rede['label']),
                $rede['cor'],
                $rede['icone']
            );
        }
        $html .= '</div>';

        return $html;
    }
}