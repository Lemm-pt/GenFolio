<?php
/**
 * Front Controller
 * 
 * Handles routing, slug detection, and session management for the multi-tenant system.
 */

// ============================================================
// CONFIGURAÇÕES SEGURAS DE SESSÃO
// ============================================================

// No index.php ou config.php
// Aumentar limites de upload (se permitido pelo host)

@ini_set('upload_max_filesize', '10M');
@ini_set('post_max_size', '10M');
@ini_set('memory_limit', '128M');
@ini_set('max_execution_time', 120);
@ini_set('max_input_time', 120);

$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || $_SERVER['SERVER_PORT'] == 443;

session_set_cookie_params([
    'httponly' => true,
    'secure'   => $isSecure,
    'samesite' => 'Strict'
]);

session_start();

// No index.php ou config.php
// Aumentar limites de upload (se permitido pelo host)

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
];

// ============================================================
// Admin routes
// ============================================================
$admin_routes = [
    'admin',
    'admin_configuracoes',
    'admin_servicos',
    'admin_galeria',
    'admin_produtos',
    'admin_publicacoes',
    'admin_logs', 
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
// 🔥 REGISTAR VISITA (apenas para páginas públicas, não admin)
// ============================================================
if (!in_array($action, $admin_routes) && !in_array($action, $routes_without_slug)) {
    try {
        $visitas = new \core\models\Visitas(CLIENTE_ID, CLIENTE_SLUG);
        $visitas->registrarVisita();
    } catch (Exception $e) {
        // Silencioso - não interrompe o funcionamento
        error_log("Erro ao registar visita: " . $e->getMessage());
    }
}

// ============================================================
// Load route definitions
// ============================================================
require_once('../core/rotas.php');