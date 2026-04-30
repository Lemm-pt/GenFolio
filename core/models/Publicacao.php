<?php
namespace core\models;

use core\classes\Database;

class Publicacao {
    public function listar($limite = null) {
        $bd = new Database();
        $sql = "SELECT * FROM publicacoes WHERE publicado = 1 ORDER BY created_at DESC";
        if($limite) $sql .= " LIMIT $limite";
        return $bd->select($sql);
    }

    public function buscarPorSlug($slug) {
        $bd = new Database();
        $res = $bd->select("SELECT * FROM publicacoes WHERE slug = :slug AND publicado = 1", [':slug' => $slug]);
        return $res ? $res[0] : null;
    }

    public function criar($dados) {
        $bd = new Database();
        $slug = $this->gerarSlug($dados['titulo']);
        $bd->insert("INSERT INTO publicacoes (titulo, slug, conteudo, publicado) VALUES (:titulo, :slug, :conteudo, :publicado)", [
            ':titulo' => $dados['titulo'],
            ':slug' => $slug,
            ':conteudo' => $dados['conteudo'],
            ':publicado' => isset($dados['publicado']) ? 1 : 0
        ]);
        return $slug;
    }

    public function atualizar($id, $dados) {
        $bd = new Database();
        $bd->update("UPDATE publicacoes SET titulo=:titulo, conteudo=:conteudo, publicado=:publicado WHERE id=:id", [
            ':id' => $id,
            ':titulo' => $dados['titulo'],
            ':conteudo' => $dados['conteudo'],
            ':publicado' => isset($dados['publicado']) ? 1 : 0
        ]);
    }

    public function deletar($id) {
        $bd = new Database();
        $bd->delete("DELETE FROM publicacoes WHERE id = :id", [':id' => $id]);
    }

    private function gerarSlug($texto) {
        $texto = preg_replace('~[^\pL\d]+~u', '-', $texto);
        $texto = iconv('utf-8', 'us-ascii//TRANSLIT', $texto);
        $texto = preg_replace('~[^-\w]+~', '', $texto);
        $texto = trim($texto, '-');
        return strtolower($texto);
    }
}