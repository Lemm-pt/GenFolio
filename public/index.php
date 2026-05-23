<?php
session_start();

require_once('../vendor/autoload.php');


$acao = $_GET['a'] ?? 'inicio';
$slug = $_GET['slug'] ?? $_GET['c'] ?? null;

// ============================================================
// ROTAS QUE NÃO PRECISAM DE SLUG (páginas de autenticação, registo, etc.)
// ============================================================
$rotas_sem_slug = [
    'admin_login', 'admin_login_submit', 'admin_logout',
    'criar_cliente', 'novo_cliente', 'confirmar_email',
    'recuperar_password', 'recuperar_password_submit',
    'recuperar_password_confirmar', 'nova_password_submit'
    // 'artigo' REMOVIDO – agora precisa de slug do cliente
];

// ============================================================
// ROTAS DE ADMIN QUE EXIGEM LOGIN
// ============================================================
$rotas_admin = [
    'admin', 'admin_configuracoes', 'admin_servicos',
    'admin_galeria', 'admin_produtos', 'admin_publicacoes'
];

// ============================================================
// VERIFICAR LOGIN ADMIN
// ============================================================
if (in_array($acao, $rotas_admin) && !isset($_SESSION['cliente_id'])) {
    header("Location: " . BASE_URL . "index.php?a=admin_login");
    exit;
}

// ============================================================
// DEFINIR CONSTANTES CLIENTE_SLUG E CLIENTE_ID
// ============================================================
if (in_array($acao, $rotas_sem_slug)) {
    // Rotas sem slug: usar sessão se disponível, senão demo
    if (isset($_SESSION['cliente_id'], $_SESSION['cliente_slug'])) {
        define('CLIENTE_SLUG', $_SESSION['cliente_slug']);
        define('CLIENTE_ID', (int)$_SESSION['cliente_id']);
    } else {
        define('CLIENTE_SLUG', 'vitrine-demo');
        define('CLIENTE_ID', 1);
    }
} else {
    // Rotas que precisam de slug (incluindo 'artigo')
    if (empty($slug)) {
        // Se não tem slug na URL, redireciona para o demo
        header("Location: " . BASE_URL . "vitrine-demo/");
        exit;
    }
    
    $bd = new \core\classes\Database();
    $cliente = $bd->select(
        "SELECT id_cliente, slug FROM clientes WHERE slug = :slug AND activo = 1",
        [':slug' => $slug]
    );
    
    if ($cliente && !empty($cliente)) {
        define('CLIENTE_SLUG', $cliente[0]->slug);
        define('CLIENTE_ID', (int)$cliente[0]->id_cliente);
    } else {
        // Slug inválido – redireciona para demo
        header("Location: " . BASE_URL . "vitrine-demo/");
        exit;
    }
}

// ============================================================
// CARREGAR ROTAS
// ============================================================
require_once('../core/rotas.php');