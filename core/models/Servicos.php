<?php
namespace core\models;

use core\classes\Database;

class Servicos {
    private $bd;
    private $cliente_id;
    
    public function __construct() {
        $this->bd = new Database();
        $this->cliente_id = $_SESSION['cliente_id'] ?? 1;
    }
    
    public function listar() {
        return $this->bd->select("SELECT * FROM servicos WHERE cliente_id = :cliente_id ORDER BY ordem", [':cliente_id' => $this->cliente_id]);
    }
    
    public function buscar($id) {
        $res = $this->bd->select("SELECT * FROM servicos WHERE id = :id AND cliente_id = :cliente_id", [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id
        ]);
        return $res ? $res[0] : null;
    }
    
    public function contar() {
        $res = $this->bd->select("SELECT COUNT(*) as total FROM servicos WHERE cliente_id = :cliente_id", [':cliente_id' => $this->cliente_id]);
        return $res ? $res[0]->total : 0;
    }
    
    public function criar($dados) {
        $ordem = $this->contar() + 1;
        $this->bd->insert("INSERT INTO servicos (cliente_id, titulo, descricao, icone, ordem) VALUES (:cliente_id, :titulo, :descricao, :icone, :ordem)", [
            ':cliente_id' => $this->cliente_id,
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':icone' => $dados['icone'] ?? 'fa-star',
            ':ordem' => $dados['ordem'] ?? $ordem
        ]);
    }
    
    public function atualizar($id, $dados) {
        $this->bd->update("UPDATE servicos SET titulo=:titulo, descricao=:descricao, icone=:icone, ordem=:ordem WHERE id=:id AND cliente_id=:cliente_id", [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id,
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':icone' => $dados['icone'],
            ':ordem' => $dados['ordem'] ?? 0
        ]);
    }
    
    public function deletar($id) {
        $this->bd->delete("DELETE FROM servicos WHERE id=:id AND cliente_id=:cliente_id", [':id' => $id, ':cliente_id' => $this->cliente_id]);
    }
}