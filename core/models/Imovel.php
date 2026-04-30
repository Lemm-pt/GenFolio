<?php
namespace core\models;

use core\classes\Database;

class Imovel {
    public function listar($apenasDestaque = false, $limite = null) {
        $bd = new Database();
        $sql = "SELECT * FROM imoveis WHERE status = 'disponivel'";
        if($apenasDestaque) $sql .= " AND destaque = 1";
        $sql .= " ORDER BY created_at DESC";
        if($limite) $sql .= " LIMIT $limite";
        return $bd->select($sql);
    }

    public function buscarPorSlug($slug) {
        $bd = new Database();
        $res = $bd->select("SELECT * FROM imoveis WHERE slug = :slug", [':slug' => $slug]);
        return $res ? $res[0] : null;
    }

    public function criar($dados) {
        $bd = new Database();
        $slug = $this->gerarSlug($dados['titulo']);
        $bd->insert("INSERT INTO imoveis (titulo, slug, descricao, preco, localizacao, tipo, destaque, status) VALUES (:titulo, :slug, :descricao, :preco, :localizacao, :tipo, :destaque, :status)", [
            ':titulo' => $dados['titulo'],
            ':slug' => $slug,
            ':descricao' => $dados['descricao'],
            ':preco' => $dados['preco'],
            ':localizacao' => $dados['localizacao'],
            ':tipo' => $dados['tipo'],
            ':destaque' => isset($dados['destaque']) ? 1 : 0,
            ':status' => $dados['status']
        ]);
        return $slug;
    }

    public function atualizar($id, $dados) {
        $bd = new Database();
        $bd->update("UPDATE imoveis SET titulo=:titulo, descricao=:descricao, preco=:preco, localizacao=:localizacao, tipo=:tipo, destaque=:destaque, status=:status WHERE id=:id", [
            ':id' => $id,
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':preco' => $dados['preco'],
            ':localizacao' => $dados['localizacao'],
            ':tipo' => $dados['tipo'],
            ':destaque' => isset($dados['destaque']) ? 1 : 0,
            ':status' => $dados['status']
        ]);
    }

    public function deletar($id) {
        $bd = new Database();
        $bd->delete("DELETE FROM imoveis WHERE id = :id", [':id' => $id]);
    }

    private function gerarSlug($texto) {
        $texto = preg_replace('~[^\pL\d]+~u', '-', $texto);
        $texto = iconv('utf-8', 'us-ascii//TRANSLIT', $texto);
        $texto = preg_replace('~[^-\w]+~', '', $texto);
        $texto = trim($texto, '-');
        return strtolower($texto);
    }
}