<?php
session_start();

require_once('../vendor/autoload.php');
require_once('../config.php');

$acao = $_GET['a'] ?? 'inicio';
$slug = $_GET['slug'] ?? $_GET['c'] ?? null;

// Rotas que NÃO precisam de slug (podem ser acedidas diretamente)
$rotas_sem_slug = ['admin_login', 'admin_login_submit', 'admin_logout', 
                   'criar_cliente', 'novo_cliente', 'confirmar_email',
                   'recuperar_password', 'recuperar_password_submit', 
                   'recuperar_password_confirmar', 'nova_password_submit', 'artigo'];

// Rotas de admin que exigem login
$rotas_admin = ['admin', 'admin_configuracoes', 'admin_servicos', 
                'admin_galeria', 'admin_produtos', 'admin_publicacoes'];

// Verificar se é uma rota de admin que precisa de login
if(in_array($acao, $rotas_admin) && !isset($_SESSION['cliente_id'])) {
    header("Location: " . BASE_URL . "index.php?a=admin_login");
    exit;
}

// Para rotas sem slug (como criar_cliente), não redirecionar
if(!$slug && !in_array($acao, $rotas_sem_slug)) {
    // Redirecionar para o demo
    header("Location: " . BASE_URL . "vitrine-demo/");
    exit;
}

// Buscar cliente na BD (se houver slug)
$cliente_atual = null;
if($slug) {
    $bd = new \core\classes\Database();
    $cliente = $bd->select("SELECT id_cliente, slug, activo FROM clientes WHERE slug = :slug", [':slug' => $slug]);
    if($cliente && $cliente[0]->activo == 1) {
        $cliente_atual = $cliente[0];
    }
}

// Se não encontrou cliente e não é rota sem slug, redireciona para demo
if(!$cliente_atual && !in_array($acao, $rotas_sem_slug) && $acao !== 'admin_login') {
    header("Location: " . BASE_URL . "vitrine-demo/");
    exit;
}

// Constantes para o frontend
define('CLIENTE_SLUG', $cliente_atual ? $cliente_atual->slug : 'vitrine-demo');
define('CLIENTE_ID', $cliente_atual ? $cliente_atual->id_cliente : 1);

require_once('../core/rotas.php');