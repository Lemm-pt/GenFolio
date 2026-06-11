<?php
namespace core\models;

use core\classes\Database;

class Galeria {
    private $bd;
    private $cliente_id;
    
   // Recebe cliente_id como parâmetro, padrão = 1 (apenas fallback)
    public function __construct($cliente_id = null) {
        $this->bd = new Database();
        $this->cliente_id = $cliente_id ?? (defined('CLIENTE_ID') ? CLIENTE_ID : 1);
    }
    public function listar() {
        $result = $this->bd->select("SELECT * FROM sevenlux_galeria WHERE cliente_id = :cliente_id ORDER BY ordem", [':cliente_id' => $this->cliente_id]);
        return $result ? $result : [];
    }
    
    public function buscar($id) {
        $res = $this->bd->select("SELECT * FROM sevenlux_galeria WHERE id = :id AND cliente_id = :cliente_id", [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id
        ]);
        return $res ? $res[0] : null;
    }
    
    public function contar() {
        $res = $this->bd->select("SELECT COUNT(*) as total FROM sevenlux_galeria WHERE cliente_id = :cliente_id", [':cliente_id' => $this->cliente_id]);
        return $res ? $res[0]->total : 0;
    }
    
    public function criar($imagem, $legenda = null) {
        $ordem = $this->contar() + 1;
        $this->bd->insert("INSERT INTO sevenlux_galeria (cliente_id, imagem, legenda, ordem) VALUES (:cliente_id, :imagem, :legenda, :ordem)", [
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
        $this->bd->delete("DELETE FROM sevenlux_galeria WHERE id=:id AND cliente_id=:cliente_id", [':id' => $id, ':cliente_id' => $this->cliente_id]);
    }
}