<?php
namespace core\models;

use core\classes\Database;
use core\classes\ImageHelper;


class Publicacoes {
    private $bd;
    private $cliente_id;
    
  // Recebe cliente_id como parâmetro, padrão = 1 (apenas fallback)
    public function __construct($cliente_id = null) {
        $this->bd = new Database();
        $this->cliente_id = $cliente_id ?? (defined('CLIENTE_ID') ? CLIENTE_ID : 1);
    }
    
  public function listar($limite = null) {
    $sql = "SELECT * FROM sevenlux_publicacoes WHERE cliente_id = :cliente_id ORDER BY created_at DESC";
    if($limite) $sql .= " LIMIT " . intval($limite);
    $result = $this->bd->select($sql, [':cliente_id' => $this->cliente_id]);
    return $result ? $result : [];
}
    
    public function listarTodas() {
        return $this->bd->select("SELECT * FROM sevenlux_publicacoes WHERE cliente_id = :cliente_id ORDER BY created_at DESC", [':cliente_id' => $this->cliente_id]);
    }
    
    public function buscar($id) {
        $res = $this->bd->select("SELECT * FROM sevenlux_publicacoes WHERE id = :id AND cliente_id = :cliente_id", [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id
        ]);
        return $res ? $res[0] : null;
    }
    
   public function buscarPorSlug($slug) {
    $res = $this->bd->select("SELECT * FROM sevenlux_publicacoes WHERE slug = :slug AND cliente_id = :cliente_id", [
        ':slug' => $slug,
        ':cliente_id' => $this->cliente_id
    ]);
    
    if($res && count($res) > 0) {
        return $res[0];
    }
    return null;
}
    
    public function contar() {
        $res = $this->bd->select("SELECT COUNT(*) as total FROM sevenlux_publicacoes WHERE cliente_id = :cliente_id", [':cliente_id' => $this->cliente_id]);
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
        $existe = $this->bd->select("SELECT id FROM sevenlux_publicacoes WHERE slug = :slug AND cliente_id = :cliente_id", [
            ':slug' => $slug,
            ':cliente_id' => $this->cliente_id
        ]);
        if($existe) $slug .= '-' . time();
        
        $filename = null;
        if ($imagem && isset($imagem['tmp_name']) && $imagem['error'] === UPLOAD_ERR_OK) {
            // 🔥 COMPRIMIR IMAGEM
            $uploadDir = __DIR__ . '/../../public/assets/images/blog/';
            $filename = ImageHelper::processarImagem($imagem, 'blog', $uploadDir);
        }
        
        $this->bd->insert("INSERT INTO sevenlux_publicacoes (cliente_id, titulo, slug, conteudo, imagem, publicado) VALUES (:cliente_id, :titulo, :slug, :conteudo, :imagem, :publicado)", [
            ':cliente_id' => $this->cliente_id,
            ':titulo' => $dados['titulo'],
            ':slug' => $slug,
            ':conteudo' => $dados['conteudo'],
            ':imagem' => $filename,
            ':publicado' => isset($dados['publicado']) ? 1 : 0
        ]);
        return $slug;
    }
    
     public function atualizar($id, $dados, $imagem = null) {
        $sql = "UPDATE sevenlux_publicacoes SET titulo=:titulo, conteudo=:conteudo, publicado=:publicado";
        $params = [
            ':id' => $id,
            ':cliente_id' => $this->cliente_id,
            ':titulo' => $dados['titulo'],
            ':conteudo' => $dados['conteudo'],
            ':publicado' => isset($dados['publicado']) ? 1 : 0
        ];
        
        if ($imagem && isset($imagem['tmp_name']) && $imagem['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/assets/images/blog/';
            $filename = ImageHelper::processarImagem($imagem, 'blog', $uploadDir);
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
            $caminho = __DIR__ . '/../../public/blog/' . $item->imagem;
            if(file_exists($caminho)) unlink($caminho);
        }
        $this->bd->delete("DELETE FROM sevenlux_publicacoes WHERE id=:id AND cliente_id=:cliente_id", [':id' => $id, ':cliente_id' => $this->cliente_id]);
    }
}