<?php
namespace core\models;

use core\classes\Database;

class Configuracao {
    private $bd;
    private $cliente_id;
    private $cache = [];
    
    public function __construct($cliente_id = null) {
        $this->bd = new Database();
        $this->cliente_id = $cliente_id ?? ($_SESSION['cliente_id'] ?? 1);
        $this->carregarTodas();
    }
    
    private function carregarTodas() {
        $res = $this->bd->select("SELECT chave, valor FROM configuracoes_site WHERE cliente_id = :cliente_id", [':cliente_id' => $this->cliente_id]);
        foreach($res as $row) {
            $this->cache[$row->chave] = $row->valor;
        }
    }
    
    public function get($chave, $padrao = '') {
        return $this->cache[$chave] ?? $padrao;
    }
    
    public function set($chave, $valor) {
        $existe = $this->bd->select("SELECT id FROM configuracoes_site WHERE cliente_id = :cliente_id AND chave = :chave", [
            ':cliente_id' => $this->cliente_id,
            ':chave' => $chave
        ]);
        if($existe) {
            $this->bd->update("UPDATE configuracoes_site SET valor = :valor WHERE cliente_id = :cliente_id AND chave = :chave", [
                ':valor' => $valor,
                ':cliente_id' => $this->cliente_id,
                ':chave' => $chave
            ]);
        } else {
            $this->bd->insert("INSERT INTO configuracoes_site (cliente_id, chave, valor) VALUES (:cliente_id, :chave, :valor)", [
                ':cliente_id' => $this->cliente_id,
                ':chave' => $chave,
                ':valor' => $valor
            ]);
        }
        $this->cache[$chave] = $valor;
    }
    
    public function getAll() {
        return $this->cache;
    }

    
}