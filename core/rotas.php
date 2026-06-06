<?php
/**
 * Route Definitions
 * 
 * Maps URL actions (a=... or friendly URLs) to controller methods.
 * Handles the 'artigo' route from .htaccess slug detection.
 * 
 * @package SevenLux
 */

// ============================================================
// Route mappings: action => controller@method
// ============================================================
$rotas = [
    // Public pages
    ''                  => 'main@index',
    'inicio'            => 'main@index',
    'blog'              => 'main@blog',
    'artigo'            => 'main@artigo',
    'contacto'          => 'main@contacto',

    // Client authentication
    'novo_cliente'      => 'main@novo_cliente',
    'criar_cliente'     => 'main@criar_cliente',
    'confirmar_email'   => 'main@confirmar_email',
    'login'             => 'main@login',
    'login_submit'      => 'main@login_submit',
    'logout'            => 'main@logout',

    // Password recovery (email-based)
    'recuperar_password'           => 'main@recuperar_password',
    'recuperar_password_submit'    => 'main@recuperar_password_submit',
    'recuperar_password_confirmar' => 'main@recuperar_password_confirmar',
    'nova_password_submit'         => 'main@nova_password_submit',

    // Admin area
    'admin_login'         => 'admin@admin_login',
    'admin_login_submit'  => 'admin@admin_login_submit',
    'admin_logout'        => 'admin@admin_logout',
    'admin'               => 'admin@admin',
    'admin_configuracoes' => 'admin@admin_configuracoes',
    'admin_salvar_config' => 'admin@admin_salvar_config',

    // Services CRUD
    'admin_servicos'        => 'admin@admin_servicos',
    'admin_servico_criar'   => 'admin@admin_servico_criar',
    'admin_servico_editar'  => 'admin@admin_servico_editar',
    'admin_servico_deletar' => 'admin@admin_servico_deletar',

    // Gallery CRUD
    'admin_galeria'        => 'admin@admin_galeria',
    'admin_galeria_criar'  => 'admin@admin_galeria_criar',
    'admin_galeria_deletar' => 'admin@admin_galeria_deletar',

    // Products CRUD
    'admin_produtos'        => 'admin@admin_produtos',
    'admin_produto_criar'   => 'admin@admin_produto_criar',
    'admin_produto_editar'  => 'admin@admin_produto_editar',
    'admin_produto_deletar' => 'admin@admin_produto_deletar',

    // Blog posts CRUD
    'admin_publicacoes'        => 'admin@admin_publicacoes',
    'admin_publicacao_criar'   => 'admin@admin_publicacao_criar',
    'admin_publicacao_editar'  => 'admin@admin_publicacao_editar',
    'admin_publicacao_deletar' => 'admin@admin_publicacao_deletar',

    // Digit code recovery (security question based)
    'recuperar_codigo'        => 'main@recuperar_codigo',
    'recuperar_codigo_submit' => 'main@recuperar_codigo_submit',
    'ajax_get_pergunta'       => 'main@ajax_get_pergunta',
];

// ============================================================
// Routing logic
// ============================================================
$acao = $_GET['a'] ?? '';
$slug_artigo = $_GET['slug_artigo'] ?? null;

// If a blog article slug is present and no other action, route to 'artigo'
if ($slug_artigo && empty($acao)) {
    $acao = 'artigo';
    $_GET['slug'] = $slug_artigo;
}

// Default action
if (empty($acao)) {
    $acao = 'inicio';
}
if (!isset($rotas[$acao])) {
    $acao = 'inicio';
}

$partes = explode('@', $rotas[$acao]);
$controlador = 'core\\controllers\\' . ucfirst($partes[0]);
$metodo = $partes[1];
$ctr = new $controlador();
$ctr->$metodo();