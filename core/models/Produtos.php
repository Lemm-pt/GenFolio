<?php
namespace core\models;

use core\classes\Database;

class Produtos {
    private $bd;
    private $cliente_id;
    
   // Recebe cliente_id como parâmetro, padrão = 1 (apenas fallback)
    public function __construct($cliente_id = null) {
        $this->bd = new Database();
        $this->cliente_id = $cliente_id ?? (defined('CLIENTE_ID') ? CLIENTE_ID : 1);
    }
    
    public function listar($limite = null) {
        $sql = "SELECT * FROM sevenlux_produtos WHERE cliente_id = :cliente_id ORDER BY ordem, id DESC";
        if($limite) $sql .= " LIMIT " . intval($limite);
        $result = $this->bd->select($sql, [':cliente_id' => $this->cliente_id]);
        return $result ? $result : [];
    }
    
    public function buscar($id) {
        $res = $this->bd->select("SELECT * FROM sevenlux_produtos WHERE id = :id AND cliente_id = :cliente_id", [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id
        ]);
        return $res ? $res[0] : null;
    }
    
    public function contar() {
        $res = $this->bd->select("SELECT COUNT(*) as total FROM sevenlux_produtos WHERE cliente_id = :cliente_id", [':cliente_id' => $this->cliente_id]);
        return $res ? $res[0]->total : 0;
    }
    
    public function criar($dados, $imagem = null) {
        $ordem = $this->contar() + 1;
        $this->bd->insert("INSERT INTO sevenlux_produtos (cliente_id, nome, descricao, preco, imagem, ordem) VALUES (:cliente_id, :nome, :descricao, :preco, :imagem, :ordem)", [
            ':cliente_id' => $this->cliente_id,
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'] ?? null,
            ':preco' => $dados['preco'] ?? null,
            ':imagem' => $imagem,
            ':ordem' => $ordem
        ]);
    }
    
    public function atualizar($id, $dados, $imagem = null) {
        $sql = "UPDATE sevenlux_produtos SET nome=:nome, descricao=:descricao, preco=:preco, ordem=:ordem";
        $params = [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id,
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'] ?? null,
            ':preco' => $dados['preco'] ?? null,
            ':ordem' => $dados['ordem'] ?? 0
        ];
        if($imagem) {
            $sql .= ", imagem=:imagem";
            $params[':imagem'] = $imagem;
        }
        $sql .= " WHERE id=:id AND cliente_id=:cliente_id";
        $this->bd->update($sql, $params);
    }
    
    public function deletar($id) {
        $item = $this->buscar($id);
        if($item && $item->imagem) {
            $caminho = __DIR__ . '/../../public/assets/images/produtos/' . $item->imagem;
            if(file_exists($caminho)) unlink($caminho);
        }
        $this->bd->delete("DELETE FROM sevenlux_produtos WHERE id=:id AND cliente_id=:cliente_id", [':id' => $id, ':cliente_id' => $this->cliente_id]);
    }
}