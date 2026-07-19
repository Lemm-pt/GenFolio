<?php
/**
 * Cristais Model - Sistema de Luzes da SevenLux
 * 
 * @package SevenLux
 */

namespace core\models;

use core\classes\Database;

class Cristais
{
    private $db;
    private $cliente_id;
    private $cache = [];

    // Definição dos cristais com categorias corrigidas
    const CRISTAIS = [
        'esmeralda' => [
            'nome' => 'Esmeralda',
            'cor' => '#2ecc71',
            'cor_hex' => '#2ecc71',
            'icone' => 'fa-leaf',
            'emoji' => '🟢',
            'elemento' => 'Natureza',
            'descricao' => 'Tecnologia da Vida',
            'lenda' => 'A vida nasce da terra. Quem domina a Esmeralda aprende a alimentar e preservar o mundo.',
            'categorias' => [
                'Alimentação', 
                'Agricultura', 
                'Restaurante',
                'Pastelaria',
                'Talho',
                'Mercearia',
                'Florista',
                'Veterinário'
            ]
        ],
        'safira' => [
            'nome' => 'Safira',
            'cor' => '#3498db',
            'cor_hex' => '#3498db',
            'icone' => 'fa-water',
            'emoji' => '🔵',
            'elemento' => 'Água',
            'descricao' => 'Tecnologia dos Oceanos',
            'lenda' => 'Os oceanos uniam povos muito antes das estradas. A Safira guarda o conhecimento da descoberta.',
            'categorias' => [
                'Turismo',
                'Hotel',
                'Transporte',
                'Marítimo',
                'Pescaria'
            ]
        ],
        'rubi' => [
            'nome' => 'Rubi',
            'cor' => '#e74c3c',
            'cor_hex' => '#e74c3c',
            'icone' => 'fa-fire',
            'emoji' => '🔴',
            'elemento' => 'Fogo',
            'descricao' => 'Tecnologia da Fundação',
            'lenda' => 'Toda a grande civilização começou com uma ideia. O Rubi não constrói muralhas. Constrói identidade.',
            'categorias' => [
                'Design',
                'Marketing',
                'Publicidade',
                'Branding',
                'Impressão',
                'Artes / Cultura'
            ]
        ],
        'topazio' => [
            'nome' => 'Topázio',
            'cor' => '#f1c40f',
            'cor_hex' => '#f1c40f',
            'icone' => 'fa-sun',
            'emoji' => '🟡',
            'elemento' => 'Luz',
            'descricao' => 'Tecnologia do Sol',
            'lenda' => 'Sem luz não existe direção. Sem direção não existe progresso.',
            'categorias' => [
                'Construção',
                'Arquitetura / Urbanismo',
                'Engenharia',
                'Eletricista',
                'Energia',
                'Imobiliário'
            ]
        ],
        'perola' => [
            'nome' => 'Pérola',
            'cor' => '#ecf0f1',
            'cor_hex' => '#ecf0f1',
            'icone' => 'fa-moon',
            'emoji' => '⚪',
            'elemento' => 'Lua',
            'descricao' => 'Tecnologia da Harmonia',
            'lenda' => 'O verdadeiro poder não está na força. Está no equilíbrio.',
            'categorias' => [
                'Saúde',
                'Beleza',
                'Dentista',
                'Psicologia',
                'Nutrição',
                'Fitness',
                'Spa',
                'Cabelo',
                'Farmácia'
            ]
        ],
        'ametista' => [
            'nome' => 'Ametista',
            'cor' => '#9b59b6',
            'cor_hex' => '#9b59b6',
            'icone' => 'fa-star',
            'emoji' => '🟣',
            'elemento' => 'Céu',
            'descricao' => 'Tecnologia do Conhecimento',
            'lenda' => 'O conhecimento apenas cresce quando é partilhado.',
            'categorias' => [
                'Educação',
                'Tecnologia',
                'Programação',
                'Consultoria',
                'Fotografia',
                'Música',
                'IA',
                'Financeiro'
            ]
        ],
        'diamante' => [
            'nome' => 'Diamante',
            'cor' => '#C6A43F',
            'cor_hex' => '#C6A43F',
            'icone' => 'fa-crown',
            'emoji' => '💎',
            'elemento' => 'Cosmos',
            'descricao' => 'Tecnologia da União',
            'lenda' => 'Nenhuma estrela ilumina o universo sozinha. Também nenhum negócio cresce sozinho.',
            'categorias' => [
                'Comércio',
                'Serviços',
                'Moda',
                'Automóvel',
                'Logística',
                'Desporto',
                'Outro'
            ]
        ]
    ];

    public function __construct($cliente_id = null)
    {
        $this->db = new Database();
        $this->cliente_id = $cliente_id ?? (defined('CLIENTE_ID') ? CLIENTE_ID : 1);
        $this->carregarCristais();
    }

    /**
     * Carrega os cristais do cliente da base de dados
     */
    private function carregarCristais()
    {
        $result = $this->db->select(
            "SELECT * FROM sevenlux_cristais WHERE cliente_id = :cliente_id",
            [':cliente_id' => $this->cliente_id]
        );

        if ($result) {
            foreach ($result as $row) {
                $this->cache[$row->cristal] = (bool)$row->ativo;
            }
        } else {
            // Se não houver registos, criar com base na categoria do cliente
            $this->inicializarCristaisPorCategoria();
        }
    }

    /**
     * Inicializa os cristais com base na categoria do cliente
     */
    private function inicializarCristaisPorCategoria()
    {
        // Buscar a categoria do cliente
        $cliente = $this->db->select(
            "SELECT categoria FROM sevenlux_clientes WHERE id_cliente = :id",
            [':id' => $this->cliente_id]
        );

        $categoria = $cliente ? trim($cliente[0]->categoria) : 'Outro';
        
        error_log("🔍 Inicializando cristais para categoria: '$categoria'");

        // Determinar qual cristal corresponde à categoria
        $cristalInicial = $this->getCristalPorCategoria($categoria);
        
        error_log("🔍 Cristal inicial encontrado: '$cristalInicial'");

        // Inserir todos os cristais
        foreach (self::CRISTAIS as $key => $cristal) {
            $ativo = ($key === $cristalInicial) ? 1 : 0;
            $this->db->insert(
                "INSERT INTO sevenlux_cristais (cliente_id, cristal, ativo) VALUES (:cliente_id, :cristal, :ativo)",
                [
                    ':cliente_id' => $this->cliente_id,
                    ':cristal' => $key,
                    ':ativo' => $ativo
                ]
            );
            $this->cache[$key] = (bool)$ativo;
        }
    }

    /**
     * Obtém o cristal correspondente a uma categoria
     */
    public function getCristalPorCategoria($categoria)
    {
        if (empty($categoria)) {
            error_log("⚠️ Categoria vazia, usando fallback 'diamante'");
            return 'diamante';
        }

        $categoria = trim($categoria);
        $categoriaLower = strtolower($categoria);
        
        error_log("🔍 Procurando cristal para categoria: '$categoria' (lower: '$categoriaLower')");

        foreach (self::CRISTAIS as $key => $cristal) {
            foreach ($cristal['categorias'] as $cat) {
                $catLower = strtolower(trim($cat));
                // Verificar correspondência exata ou parcial
                if ($categoriaLower === $catLower || 
                    strpos($categoriaLower, $catLower) !== false || 
                    strpos($catLower, $categoriaLower) !== false) {
                    error_log("✅ Cristal '$key' encontrado para categoria '$categoria' (match com '$cat')");
                    return $key;
                }
            }
        }

        error_log("⚠️ Nenhum cristal encontrado para '$categoria', usando fallback 'diamante'");
        return 'diamante';
    }

    /**
     * Obtém todos os cristais com o seu estado
     */
    public function getAll()
    {
        $result = [];
        foreach (self::CRISTAIS as $key => $cristal) {
            $result[$key] = [
                'chave' => $key,
                'nome' => $cristal['nome'],
                'cor' => $cristal['cor'],
                'icone' => $cristal['icone'],
                'emoji' => $cristal['emoji'],
                'elemento' => $cristal['elemento'],
                'descricao' => $cristal['descricao'],
                'lenda' => $cristal['lenda'],
                'ativo' => $this->cache[$key] ?? false
            ];
        }
        return $result;
    }

    /**
     * Obtém apenas os cristais ativos
     */
    public function getAtivos()
    {
        return array_filter($this->getAll(), function($cristal) {
            return $cristal['ativo'];
        });
    }

    /**
     * Obtém o número de cristais ativos
     */
    public function getContagemAtivos()
    {
        return count($this->getAtivos());
    }

    /**
     * Ativa um cristal
     */
    public function ativar($cristal)
    {
        if (!isset(self::CRISTAIS[$cristal])) {
            return false;
        }

        $this->db->update(
            "UPDATE sevenlux_cristais SET ativo = 1 WHERE cliente_id = :cliente_id AND cristal = :cristal",
            [':cliente_id' => $this->cliente_id, ':cristal' => $cristal]
        );
        $this->cache[$cristal] = true;
        return true;
    }

    /**
     * Verifica se um cristal está ativo
     */
    public function isAtivo($cristal)
    {
        return $this->cache[$cristal] ?? false;
    }

    /**
     * Obtém o cristal principal (o primeiro ativo)
     */
    public function getCristalPrincipal()
    {
        foreach ($this->getAll() as $key => $cristal) {
            if ($cristal['ativo']) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Obtém a informação de um cristal específico
     */
    public function getInfo($cristal)
    {
        return self::CRISTAIS[$cristal] ?? null;
    }
}