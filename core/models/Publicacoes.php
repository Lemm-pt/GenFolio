<?php
namespace core\models;

use core\classes\Database;

class Publicacoes {
    private $bd;
    private $cliente_id;
    
    public function __construct() {
        $this->bd = new Database();
        $this->cliente_id = $_SESSION['cliente_id'] ?? 1; // Padrão = 1
    }
    
  public function listar($limite = null) {
    $sql = "SELECT * FROM publicacoes WHERE cliente_id = :cliente_id ORDER BY created_at DESC";
    if($limite) $sql .= " LIMIT " . intval($limite);
    $result = $this->bd->select($sql, [':cliente_id' => $this->cliente_id]);
    return $result ? $result : [];
}
    
    public function listarTodas() {
        return $this->bd->select("SELECT * FROM publicacoes WHERE cliente_id = :cliente_id ORDER BY created_at DESC", [':cliente_id' => $this->cliente_id]);
    }
    
    public function buscar($id) {
        $res = $this->bd->select("SELECT * FROM publicacoes WHERE id = :id AND cliente_id = :cliente_id", [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id
        ]);
        return $res ? $res[0] : null;
    }
    
   public function buscarPorSlug($slug) {
    $res = $this->bd->select("SELECT * FROM publicacoes WHERE slug = :slug AND cliente_id = :cliente_id", [
        ':slug' => $slug,
        ':cliente_id' => $this->cliente_id
    ]);
    
    if($res && count($res) > 0) {
        return $res[0];
    }
    return null;
}
    
    public function contar() {
        $res = $this->bd->select("SELECT COUNT(*) as total FROM publicacoes WHERE cliente_id = :cliente_id", [':cliente_id' => $this->cliente_id]);
        return $res ? $res[0]->total : 0;
    }
    
    private function gerarSlug($texto) {
        $texto = preg_replace('~[^\pL\d]+~u', '-', $texto);
        $texto = iconv('utf-8', 'us-ascii//TRANSLIT', $texto);
        $texto = preg_replace('~[^-\w]+~', '', $texto);
        $texto = trim($texto, '-');
        return strtolower($texto);
    }
    
    public function criar($dados, $imagem = null) {
        $slug = $this->gerarSlug($dados['titulo']);
        // Verificar se slug já existe
        $existe = $this->bd->select("SELECT id FROM publicacoes WHERE slug = :slug AND cliente_id = :cliente_id", [
            ':slug' => $slug,
            ':cliente_id' => $this->cliente_id
        ]);
        if($existe) $slug .= '-' . time();
        
        $this->bd->insert("INSERT INTO publicacoes (cliente_id, titulo, slug, conteudo, imagem, publicado) VALUES (:cliente_id, :titulo, :slug, :conteudo, :imagem, :publicado)", [
            ':cliente_id' => $this->cliente_id,
            ':titulo' => $dados['titulo'],
            ':slug' => $slug,
            ':conteudo' => $dados['conteudo'],
            ':imagem' => $imagem,
            ':publicado' => isset($dados['publicado']) ? 1 : 0
        ]);
        return $slug;
    }
    
    public function atualizar($id, $dados, $imagem = null) {
        $sql = "UPDATE publicacoes SET titulo=:titulo, conteudo=:conteudo, publicado=:publicado";
        $params = [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id,
            ':titulo' => $dados['titulo'],
            ':conteudo' => $dados['conteudo'],
            ':publicado' => isset($dados['publicado']) ? 1 : 0
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
            $caminho = __DIR__ . '/../../public/blog/' . $item->imagem;
            if(file_exists($caminho)) unlink($caminho);
        }
        $this->bd->delete("DELETE FROM publicacoes WHERE id=:id AND cliente_id=:cliente_id", [':id' => $id, ':cliente_id' => $this->cliente_id]);
    }
}