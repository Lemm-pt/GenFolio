<?php
namespace core\classes;

use Exception;

class Store {
    
    public static function Layout($estruturas, $dados = null) {
        if(!is_array($estruturas)) throw new Exception("Coleção de estruturas inválida");
        if(!empty($dados) && is_array($dados)) extract($dados);
        foreach($estruturas as $estrutura) include("../core/views/$estrutura.php");
    }
    
public static function redirect($rota = '') {
    // Obter slug da sessão (já deve estar correto)
    $slug = $_SESSION['cliente_slug'] ?? 'vitrine-demo';
    
    // Rotas que NÃO devem usar URL amigável
    $rotas_simples = ['admin_login', 'admin_login_submit', 'admin_logout', 
                      'criar_cliente', 'confirmar_email', 'recuperar_password', 
                      'recuperar_password_submit', 'recuperar_password_confirmar',
                      'nova_password_submit'];
    
    if(in_array($rota, $rotas_simples)) {
        header("Location: " . BASE_URL . "index.php?a=" . $rota);
        exit;
    }
    
    $url = BASE_URL . $slug . '/';
    if(!empty($rota) && $rota !== 'inicio') {
        $url .= $rota;
    }
    header("Location: " . $url);
    exit;
}
    
    public static function adminLogado() {
        return isset($_SESSION['cliente_id']);
    }
    
    public static function criarHash($num_caracteres = 12) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, $num_caracteres);
    }
    
public static function getClienteSlug() {
    // Para o admin (sessão)
    if(isset($_SESSION['cliente_slug'])) {
        return $_SESSION['cliente_slug'];
    }
    
    // Para o frontend (constante definida no index.php)
    if(defined('CLIENTE_SLUG')) {
        return CLIENTE_SLUG;
    }
    
    // Fallback
    return 'vitrine-demo';
}



   public static function getBaseUrl() {
    // Se estiver no admin e houver sessão, usar slug da sessão
    if(isset($_SESSION['cliente_slug'])) {
        return BASE_URL . $_SESSION['cliente_slug'] . '/';
    }
    // Fallback para a constante definida no index.php
    return BASE_URL . CLIENTE_SLUG . '/';
}

public static function getUrl($rota = '') {
    $slug = self::getClienteSlug();
    $rotas_simples = ['novo_cliente', 'criar_cliente', 'confirmar_email', 'recuperar_password', 'admin_login'];
    
    if(in_array($rota, $rotas_simples)) {
        return BASE_URL . 'index.php?a=' . $rota;
    }
    
    // Para o artigo, precisamos passar o slug como parâmetro
    if($rota === 'artigo') {
        return BASE_URL . $slug . '/artigo';
    }
    
    return BASE_URL . $slug . '/' . $rota;
}


public static function getCurrentSlug() {
    // Prioridade 1: Sessão do admin logado
    if(isset($_SESSION['cliente_slug'])) {
        return $_SESSION['cliente_slug'];
    }
    
    // Prioridade 2: Constante definida no index.php
    if(defined('CLIENTE_SLUG')) {
        return CLIENTE_SLUG;
    }
    
    // Prioridade 3: Fallback
    return 'vitrine-demo';
}


}