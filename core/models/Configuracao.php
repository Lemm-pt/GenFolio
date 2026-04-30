<?php
namespace core\models;

use core\classes\Database;

class Configuracao {
    private $bd;
    private $cache = [];
    
    public function __construct() {
        $this->bd = new Database();
        $this->carregarTodas();
    }
    
    private function carregarTodas() {
        $res = $this->bd->select("SELECT chave, valor FROM configuracoes");
        foreach($res as $row) {
            $this->cache[$row->chave] = $row->valor;
        }
    }
    
    public function get($chave, $padrao = '') {
        return $this->cache[$chave] ?? $padrao;
    }
    
    public function set($chave, $valor) {
        $existe = $this->bd->select("SELECT id FROM configuracoes WHERE chave = :chave", [':chave' => $chave]);
        if($existe) {
            $this->bd->update("UPDATE configuracoes SET valor = :valor WHERE chave = :chave", [':valor' => $valor, ':chave' => $chave]);
        } else {
            $this->bd->insert("INSERT INTO configuracoes (chave, valor) VALUES (:chave, :valor)", [':chave' => $chave, ':valor' => $valor]);
        }
        $this->cache[$chave] = $valor;
    }
    
    public function getAll() {
        return $this->cache;
    }
}