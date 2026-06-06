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

class Configuracao
{
    private $bd;
    private $cliente_id;
    private $cache = [];

    /**
     * Constructor.
     *
     * @param int|null $cliente_id Client ID (defaults to logged-in client or 1)
     */
    public function __construct($cliente_id = null)
    {
        $this->bd = new Database();
        $this->cliente_id = $cliente_id ?? ($_SESSION['cliente_id'] ?? 1);
        $this->carregarTodas();
    }

    /**
     * Loads all configuration entries for the client into the cache.
     */
    private function carregarTodas()
    {
        $res = $this->bd->select(
            "SELECT chave, valor FROM configuracoes_site WHERE cliente_id = :cliente_id",
            [':cliente_id' => $this->cliente_id]
        );
        foreach ($res as $row) {
            $this->cache[$row->chave] = $row->valor;
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
        $existe = $this->bd->select(
            "SELECT id FROM configuracoes_site WHERE cliente_id = :cliente_id AND chave = :chave",
            [':cliente_id' => $this->cliente_id, ':chave' => $chave]
        );

        if ($existe) {
            $this->bd->update(
                "UPDATE configuracoes_site SET valor = :valor WHERE cliente_id = :cliente_id AND chave = :chave",
                [':valor' => $valor, ':cliente_id' => $this->cliente_id, ':chave' => $chave]
            );
        } else {
            $this->bd->insert(
                "INSERT INTO configuracoes_site (cliente_id, chave, valor) VALUES (:cliente_id, :chave, :valor)",
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
}