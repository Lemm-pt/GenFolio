<?php
/**
 * Front Controller
 * 
 * Handles routing, slug detection, and session management for the multi-tenant system.
 */

// ============================================================
// 🔥 DEFINIR FUSO HORÁRIO DE PORTUGAL (DEVE SER O PRIMEIRO)
// ============================================================
date_default_timezone_set('Europe/Lisbon');

// ============================================================
// CONFIGURAÇÕES SEGURAS DE SESSÃO
// ============================================================
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || $_SERVER['SERVER_PORT'] == 443;

session_set_cookie_params([
    'httponly' => true,
    'secure'   => $isSecure,
    'samesite' => 'Strict'
]);

session_start();

// ============================================================
// Aumentar limites de upload (uma única vez)
// ============================================================
@ini_set('upload_max_filesize', '10M');
@ini_set('post_max_size', '10M');
@ini_set('memory_limit', '128M');
@ini_set('max_execution_time', 120);
@ini_set('max_input_time', 120);

require_once('../vendor/autoload.php');

// ============================================================
// Global action and slug detection
// ============================================================
$action = $_GET['a'] ?? 'inicio';
$slug   = $_GET['slug'] ?? $_GET['c'] ?? null;

// ============================================================
// Routes that do NOT require a client slug in the URL
// ============================================================
$routes_without_slug = [
    'admin_login_submit',
    'admin_logout',
    'criar_cliente',
    'novo_cliente',
    'confirmar_email',
    'recuperar_password',
    'recuperar_password_submit',
    'recuperar_password_confirmar',
    'nova_password_submit',
    'recuperar_codigo',
    'recuperar_codigo_submit',
    'recuperar_codigo_confirmar',
    'recuperar_codigo_novo_submit',
    'admin_gestao_conta',
    'admin_pausar_conta',
    'admin_reativar_conta',
    'admin_desativar_conta',
    'admin_solicitar_eliminacao',
    'admin_cancelar_eliminacao',
    'admin_social',
    'admin_salvar_social',
    'politica_privacidade'
];

// ============================================================
// Admin routes (todas as rotas que começam com 'admin_')
// ============================================================
$admin_routes = [
    'admin',
    'admin_configuracoes',
    'admin_servicos',
    'admin_galeria',
    'admin_produtos',
    'admin_publicacoes',
    'admin_logs',
    'admin_salvar_config',
    'admin_servico_criar',
    'admin_servico_editar',
    'admin_servico_deletar',
    'admin_galeria_criar',
    'admin_galeria_deletar',
    'admin_produto_criar',
    'admin_produto_editar',
    'admin_produto_deletar',
    'admin_publicacao_criar',
    'admin_publicacao_editar',
    'admin_publicacao_deletar',
    'admin_gestao_conta',
    'admin_pausar_conta',
    'admin_reativar_conta',
    'admin_desativar_conta',
    'admin_solicitar_eliminacao',
    'admin_cancelar_eliminacao',
    'admin_social',
    'admin_salvar_social',
    'admin_estatisticas'
];

// ============================================================
// Check admin login
// ============================================================
if (in_array($action, $admin_routes) && !isset($_SESSION['cliente_id'])) {
    header("Location: " . BASE_URL . "index.php?a=admin_login");
    exit;
}

// ============================================================
// Define constants CLIENTE_SLUG and CLIENTE_ID
// ============================================================
if (in_array($action, $routes_without_slug)) {
    if (isset($_SESSION['cliente_id'], $_SESSION['cliente_slug'])) {
        define('CLIENTE_SLUG', $_SESSION['cliente_slug']);
        define('CLIENTE_ID', (int) $_SESSION['cliente_id']);
    } else {
        define('CLIENTE_SLUG', 'vitrine-demo');
        define('CLIENTE_ID', 1);
    }
} else {
    if (empty($slug)) {
        header("Location: " . BASE_URL . "vitrine-demo/");
        exit;
    }

    if (isset($_SESSION['cliente_id'], $_SESSION['cliente_slug'])) {
        if ($slug !== $_SESSION['cliente_slug']) {
            session_destroy();
            header("Location: " . BASE_URL . $slug . "/admin_login?erro=acesso_negado");
            exit;
        }
        define('CLIENTE_SLUG', $_SESSION['cliente_slug']);
        define('CLIENTE_ID', (int) $_SESSION['cliente_id']);
    } else {
        $db = new \core\classes\Database();
        $client = $db->select(
            "SELECT id_cliente, slug, locale, currency FROM sevenlux_clientes WHERE slug = :slug AND activo = 1",
            [':slug' => $slug]
        );

        if ($client && !empty($client)) {
            define('CLIENTE_SLUG', $client[0]->slug);
            define('CLIENTE_ID', (int) $client[0]->id_cliente);
            define('CLIENTE_LOCALE', $client[0]->locale ?? 'pt');
            define('CLIENTE_CURRENCY', $client[0]->currency ?? 'EUR');
        } else {
            header("Location: " . BASE_URL . "vitrine-demo/");
            exit;
        }
    }
}

// ============================================================
// Fallback: se CLIENTE_LOCALE ou CLIENTE_CURRENCY não estiverem definidos
// ============================================================
if (!defined('CLIENTE_LOCALE')) {
    if (defined('CLIENTE_ID') && CLIENTE_ID > 0) {
        $dbLocale = new \core\classes\Database();
        $clienteInfo = $dbLocale->select(
            "SELECT locale, currency FROM sevenlux_clientes WHERE id_cliente = :id",
            [':id' => CLIENTE_ID]
        );
        if ($clienteInfo && !empty($clienteInfo[0])) {
            define('CLIENTE_LOCALE', $clienteInfo[0]->locale ?? 'pt');
            define('CLIENTE_CURRENCY', $clienteInfo[0]->currency ?? 'EUR');
        } else {
            define('CLIENTE_LOCALE', 'pt');
            define('CLIENTE_CURRENCY', 'EUR');
        }
    } else {
        define('CLIENTE_LOCALE', 'pt');
        define('CLIENTE_CURRENCY', 'EUR');
    }
}

// Definir o locale do PHP
setlocale(LC_ALL, CLIENTE_LOCALE . '_' . strtoupper(CLIENTE_LOCALE) . '.utf8');

// ============================================================
// 🔥 VERIFICAR STATUS DA CONTA
// ============================================================
$isAdminRoute = in_array($action, $admin_routes);
$isPublicRoute = in_array($action, $routes_without_slug);
$isLoginRoute = ($action === 'admin_login' || $action === 'admin_login_submit');

if (defined('CLIENTE_ID') && CLIENTE_ID > 0 && !$isAdminRoute && !$isPublicRoute && !$isLoginRoute) {
    $clientModel = new \core\models\Clientes();
    
    if (!$clientModel->isContaAtiva(CLIENTE_ID)) {
        $status = $clientModel->getStatusConta(CLIENTE_ID);
        $statusSlug = $status['status'];
        $tempo = $status['dias_restantes'] ?? null;
        
        include('../core/views/manutencao.php');
        exit;
    }
}

// ============================================================
// 🔥 REGISTAR VISITA (apenas para páginas públicas, não admin)
// ============================================================
if (!in_array($action, $admin_routes) && !in_array($action, $routes_without_slug)) {
    try {
        $visitas = new \core\models\Visitas(CLIENTE_ID, CLIENTE_SLUG);
        $visitas->registrarVisita();
    } catch (Exception $e) {
        error_log("Erro ao registar visita: " . $e->getMessage());
    }
}

// ============================================================
// Load route definitions
// ============================================================
require_once('../core/rotas.php');