<?php
namespace core\models;

use core\classes\Database;

class Galeria {
    private $bd;
    private $cliente_id;
    
    public function __construct() {
        $this->bd = new Database();
        $this->cliente_id = $_SESSION['cliente_id'] ?? 1;
    }
    
    public function listar() {
        $result = $this->bd->select("SELECT * FROM galeria WHERE cliente_id = :cliente_id ORDER BY ordem", [':cliente_id' => $this->cliente_id]);
        return $result ? $result : [];
    }
    
    public function buscar($id) {
        $res = $this->bd->select("SELECT * FROM galeria WHERE id = :id AND cliente_id = :cliente_id", [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id
        ]);
        return $res ? $res[0] : null;
    }
    
    public function contar() {
        $res = $this->bd->select("SELECT COUNT(*) as total FROM galeria WHERE cliente_id = :cliente_id", [':cliente_id' => $this->cliente_id]);
        return $res ? $res[0]->total : 0;
    }
    
    public function criar($imagem, $legenda = null) {
        $ordem = $this->contar() + 1;
        $this->bd->insert("INSERT INTO galeria (cliente_id, imagem, legenda, ordem) VALUES (:cliente_id, :imagem, :legenda, :ordem)", [
            ':cliente_id' => $this->cliente_id,
            ':imagem' => $imagem,
            ':legenda' => $legenda,
            ':ordem' => $ordem
        ]);
        return true;
    }
    
    public function deletar($id) {
        $item = $this->buscar($id);
        if($item && $item->imagem) {
            $caminho = __DIR__ . '/../../public/assets/images/galeria/' . $item->imagem;
            if(file_exists($caminho)) unlink($caminho);
        }
        $this->bd->delete("DELETE FROM galeria WHERE id=:id AND cliente_id=:cliente_id", [':id' => $id, ':cliente_id' => $this->cliente_id]);
    }
}