<?php
/**
 * Front Controller
 * 
 * Handles routing, slug detection, and session management for the multi-tenant system.
 * Each client has a unique slug (e.g., /my-business/) which determines which site is loaded.
 * 
 * @package SevenLux
 */

session_start();

require_once('../vendor/autoload.php');

// ============================================================
// Global action and slug detection
// ============================================================
$action = $_GET['a'] ?? 'inicio';
$slug   = $_GET['slug'] ?? $_GET['c'] ?? null;

// ============================================================
// Routes that do NOT require a client slug in the URL
// (authentication, registration, password recovery, etc.)
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
];

// ============================================================
// Admin routes that require the user to be logged in
// ============================================================
$admin_routes = [
    'admin',
    'admin_configuracoes',
    'admin_servicos',
    'admin_galeria',
    'admin_produtos',
    'admin_publicacoes',
];

// ============================================================
// Check admin login for protected routes
// ============================================================
if (in_array($action, $admin_routes) && !isset($_SESSION['cliente_id'])) {
    header("Location: " . BASE_URL . "index.php?a=admin_login");
    exit;
}

// ============================================================
// Define constants CLIENTE_SLUG and CLIENTE_ID
// ============================================================
if (in_array($action, $routes_without_slug)) {
    // Routes without slug: use session if available, otherwise fallback to demo client
    if (isset($_SESSION['cliente_id'], $_SESSION['cliente_slug'])) {
        define('CLIENTE_SLUG', $_SESSION['cliente_slug']);
        define('CLIENTE_ID', (int) $_SESSION['cliente_id']);
    } else {
        define('CLIENTE_SLUG', 'vitrine-demo');
        define('CLIENTE_ID', 1);
    }
} else {
    // Routes that require a valid client slug from the URL
    if (empty($slug)) {
        // No slug provided – redirect to the demo client
        header("Location: " . BASE_URL . "vitrine-demo/");
        exit;
    }

    // 🔐 SECURITY: If user is already logged in, they can only access their own slug
    if (isset($_SESSION['cliente_id'], $_SESSION['cliente_slug'])) {
        if ($slug !== $_SESSION['cliente_slug']) {
            // Attempt to access another client's admin – log out and force login
            session_destroy();
            header("Location: " . BASE_URL . $slug . "/admin_login?erro=acesso_negado");
            exit;
        }
        // Use session data directly (fast, no DB query)
        define('CLIENTE_SLUG', $_SESSION['cliente_slug']);
        define('CLIENTE_ID', (int) $_SESSION['cliente_id']);
    } else {
        // Not logged in – verify the slug from database (public access)
        $db = new \core\classes\Database();
        $client = $db->select(
            "SELECT id_cliente, slug FROM sevenlux_clientes WHERE slug = :slug AND activo = 1",
            [':slug' => $slug]
        );

        if ($client && !empty($client)) {
            define('CLIENTE_SLUG', $client[0]->slug);
            define('CLIENTE_ID', (int) $client[0]->id_cliente);
        } else {
            // Invalid slug – redirect to demo client
            header("Location: " . BASE_URL . "vitrine-demo/");
            exit;
        }
    }
}

// ============================================================
// Detetar idioma baseado no país do cliente (se existir)
// ============================================================
if (defined('CLIENTE_ID') && CLIENTE_ID > 0) {
    $dbLocale = new \core\classes\Database();
    $clienteInfo = $dbLocale->select(
        "SELECT pais FROM sevenlux_clientes WHERE id_cliente = :id",
        [':id' => CLIENTE_ID]
    );
    
    if ($clienteInfo && !empty($clienteInfo[0]->pais)) {
        $pais = $clienteInfo[0]->pais;
        $idioma = \core\classes\LocaleHelper::getLanguageFromCountry($pais);
        $moeda = \core\classes\LocaleHelper::getCurrencyFromCountry($pais);
        
        define('CLIENTE_LOCALE', $idioma);
        define('CLIENTE_CURRENCY', $moeda);
        
        // Definir o locale do PHP
        setlocale(LC_ALL, $idioma . '_' . strtoupper($idioma) . '.utf8');
    } else {
        define('CLIENTE_LOCALE', 'pt');
        define('CLIENTE_CURRENCY', 'EUR');
    }
} else {
    define('CLIENTE_LOCALE', 'pt');
    define('CLIENTE_CURRENCY', 'EUR');
}

// ============================================================
// Load route definitions (maps actions to controllers)
// ============================================================
require_once('../core/rotas.php');