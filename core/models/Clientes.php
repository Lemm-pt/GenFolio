<?php
namespace core\models;

use core\classes\Database;
use core\classes\Store;

class Clientes {
    private $bd;
    
    public function __construct() {
        $this->bd = new Database();
    }
    
    // ============================================================
    // VERIFICAR SE EMAIL JÁ EXISTE
    // ============================================================
    public function verificar_email_existe($email) {
        $resultados = $this->bd->select("SELECT id_cliente FROM clientes WHERE email = :email", [':email' => $email]);
        return count($resultados) != 0;
    }
    
    // ============================================================
    // VERIFICAR SE SLUG JÁ EXISTE
    // ============================================================
    public function verificar_slug_existe($slug) {
        $resultados = $this->bd->select("SELECT id_cliente FROM clientes WHERE slug = :slug", [':slug' => $slug]);
        return count($resultados) != 0;
    }
    
    // ============================================================
    // REGISTAR NOVO CLIENTE (email, slug, senha)
    // ============================================================
    public function registar_cliente($email, $slug, $senha) {
        $purl = Store::criarHash();
        $parametros = [
            ':email' => strtolower(trim($email)),
            ':slug' => $slug,
            ':senha' => password_hash($senha, PASSWORD_DEFAULT),
            ':purl' => $purl,
            ':activo' => 0
        ];
        
        $this->bd->insert("
            INSERT INTO clientes (email, slug, senha, purl, activo, created_at, updated_at) 
            VALUES (:email, :slug, :senha, :purl, :activo, NOW(), NOW())
        ", $parametros);
        
        return $purl;
    }
    
    // ============================================================
    // CONFIRMAR EMAIL (ativar conta)
    // ============================================================
    public function confirmar_email($purl) {
        $resultados = $this->bd->select("SELECT id_cliente FROM clientes WHERE purl = :purl", [':purl' => $purl]);
        if(count($resultados) != 1) return false;
        
        $id_cliente = $resultados[0]->id_cliente;
        $this->bd->update("
            UPDATE clientes SET purl = NULL, activo = 1, updated_at = NOW()
            WHERE id_cliente = :id_cliente
        ", [':id_cliente' => $id_cliente]);
        
        // Criar configurações padrão para o novo cliente
        $this->criarConfiguracoesPadrao($id_cliente);
        
        return true;
    }
    
    // ============================================================
    // VALIDAR LOGIN (por slug ou email)
    // ============================================================
public function validar_login($email, $senha) {
    $resultados = $this->bd->select("
        SELECT * FROM clientes 
        WHERE email = :email AND activo = 1 AND deleted_at IS NULL
    ", [':email' => $email]);
    
    if(count($resultados) != 1) return false;
    
    $cliente = $resultados[0];
    if(password_verify($senha, $cliente->senha)) {
        return $cliente;
    }
    return false;
}
    
    // ============================================================
    // BUSCAR POR SLUG (para frontend)
    // ============================================================
    public function buscarPorSlug($slug) {
        $resultados = $this->bd->select("SELECT id_cliente, email, slug FROM clientes WHERE slug = :slug AND activo = 1", [':slug' => $slug]);
        return count($resultados) > 0 ? $resultados[0] : null;
    }
    
    // ============================================================
    // RECUPERAÇÃO DE PASSWORD (gera token no campo purl)
    // ============================================================
    public function gerarTokenRecuperacao($email) {
        $token = Store::criarHash(32);
        $this->bd->update("UPDATE clientes SET purl = :token WHERE email = :email", [':token' => $token, ':email' => $email]);
        return $token;
    }
    
    public function validarTokenRecuperacao($token) {
        $resultados = $this->bd->select("SELECT id_cliente, email FROM clientes WHERE purl = :token AND activo = 1", [':token' => $token]);
        return count($resultados) == 1 ? $resultados[0] : false;
    }
    
    public function atualizarPassword($id_cliente, $nova_senha) {
        $this->bd->update("
            UPDATE clientes SET senha = :senha, purl = NULL, updated_at = NOW()
            WHERE id_cliente = :id_cliente
        ", [
            ':senha' => password_hash($nova_senha, PASSWORD_DEFAULT),
            ':id_cliente' => $id_cliente
        ]);
    }
    
    // ============================================================
    // CONFIGURAÇÕES PADRÃO
    // ============================================================
    private function criarConfiguracoesPadrao($cliente_id) {
        $existe = $this->bd->select("SELECT id FROM configuracoes_site WHERE cliente_id = :cliente_id LIMIT 1", [':cliente_id' => $cliente_id]);
        if($existe) return;
        
        $configs = [
            ['cliente_id' => $cliente_id, 'chave' => 'logo_parte1', 'valor' => 'Meu'],
            ['cliente_id' => $cliente_id, 'chave' => 'logo_parte2', 'valor' => 'Negócio'],
            ['cliente_id' => $cliente_id, 'chave' => 'logo_imagem', 'valor' => ''],
            ['cliente_id' => $cliente_id, 'chave' => 'slogan', 'valor' => 'Soluções Personalizadas'],
            ['cliente_id' => $cliente_id, 'chave' => 'texto_descritivo', 'valor' => 'Bem-vindo ao seu novo site!'],
            ['cliente_id' => $cliente_id, 'chave' => 'email_contacto', 'valor' => ''],
            ['cliente_id' => $cliente_id, 'chave' => 'telefone', 'valor' => ''],
            ['cliente_id' => $cliente_id, 'chave' => 'endereco', 'valor' => ''],
            ['cliente_id' => $cliente_id, 'chave' => 'meta_description', 'valor' => ''],
            ['cliente_id' => $cliente_id, 'chave' => 'meta_keywords', 'valor' => '']
        ];
        foreach($configs as $config) {
            $this->bd->insert("INSERT INTO configuracoes_site (cliente_id, chave, valor) VALUES (:cliente_id, :chave, :valor)", $config);
        }
    }




public function buscarPorPurl($purl) {
    $res = $this->bd->select("SELECT id_cliente, slug FROM clientes WHERE purl = :purl", [':purl' => $purl]);
    return $res ? $res[0] : null;
}





    }