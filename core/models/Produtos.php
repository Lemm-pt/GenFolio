<?php
// core/models/Produtos.php
namespace core\models;

use core\classes\Database;
use core\classes\ImageHelper;

class Produtos {
    private $bd;
    private $cliente_id;
    
    public function __construct($cliente_id = null) {
        $this->bd = new Database();
        $this->cliente_id = $cliente_id ?? (defined('CLIENTE_ID') ? CLIENTE_ID : 1);
    }
    
    public function listar($limite = null) {
        $sql = "SELECT * FROM sevenlux_produtos WHERE cliente_id = :cliente_id ORDER BY destaque DESC, ordem, id DESC";
        if($limite) $sql .= " LIMIT " . intval($limite);
        $result = $this->bd->select($sql, [':cliente_id' => $this->cliente_id]);
        return $result ? $result : [];
    }
    
    /**
     * Lista apenas produtos em destaque/promoção
     */
    public function listarDestaques($limite = 7) {
        $sql = "SELECT * FROM sevenlux_produtos 
                WHERE cliente_id = :cliente_id 
                AND destaque = 1 
                ORDER BY ordem, id DESC 
                LIMIT " . intval($limite);
        $result = $this->bd->select($sql, [':cliente_id' => $this->cliente_id]);
        return $result ? $result : [];
    }
    
    /**
     * Lista produtos normais (não destaque)
     */
    public function listarNormais($limite = null) {
        $sql = "SELECT * FROM sevenlux_produtos 
                WHERE cliente_id = :cliente_id 
                AND (destaque = 0 OR destaque IS NULL)
                ORDER BY ordem, id DESC";
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
        $destaque = isset($dados['destaque']) && $dados['destaque'] == '1' ? 1 : 0;
        
        $filename = null;
        if ($imagem && isset($imagem['tmp_name']) && $imagem['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/images/produtos/';
            $filename = ImageHelper::processarImagem($imagem, 'produto', $uploadDir);
        }
        
        $this->bd->insert("INSERT INTO sevenlux_produtos (cliente_id, nome, descricao, preco, preco_promocional, imagem, ordem, destaque) VALUES (:cliente_id, :nome, :descricao, :preco, :preco_promocional, :imagem, :ordem, :destaque)", [
            ':cliente_id' => $this->cliente_id,
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'] ?? null,
            ':preco' => $dados['preco'] ?? null,
            ':preco_promocional' => $dados['preco_promocional'] ?? null,
            ':imagem' => $filename,
            ':ordem' => $ordem,
            ':destaque' => $destaque
        ]);
    }
    
    public function atualizar($id, $dados, $imagem = null) {
        $destaque = isset($dados['destaque']) && $dados['destaque'] == '1' ? 1 : 0;
        
        $sql = "UPDATE sevenlux_produtos SET nome=:nome, descricao=:descricao, preco=:preco, preco_promocional=:preco_promocional, ordem=:ordem, destaque=:destaque";
        $params = [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id,
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'] ?? null,
            ':preco' => $dados['preco'] ?? null,
            ':preco_promocional' => $dados['preco_promocional'] ?? null,
            ':ordem' => $dados['ordem'] ?? 0,
            ':destaque' => $destaque
        ];
        
        if ($imagem && isset($imagem['tmp_name']) && $imagem['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/images/produtos/';
            $filename = ImageHelper::processarImagem($imagem, 'produto', $uploadDir);
            if ($filename) {
                $sql .= ", imagem=:imagem";
                $params[':imagem'] = $filename;
            }
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