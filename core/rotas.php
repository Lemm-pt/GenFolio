<?php

// Coleção de rotas (PÚBLICAS + ADMIN)
$rotas = [
    // ========== PÁGINAS PÚBLICAS ==========
    'inicio' => 'main@index',
    '' => 'main@index',
    
    // Cliente (registo e autenticação)
    'novo_cliente' => 'main@novo_cliente',
    'criar_cliente' => 'main@criar_cliente',
    'confirmar_email' => 'main@confirmar_email',
    'login' => 'main@login',
    'login_submit' => 'main@login_submit',
    'logout' => 'main@logout',
    
    // Blog público
    'blog' => 'main@blog',
    'artigo' => 'main@artigo',
    'contacto' => 'main@contacto',
    
    // ========== ADMIN (BACKOFFICE) ==========
    // Login/Logout do admin (ADICIONADO)
    'admin_login' => 'admin@admin_login',
    'admin_login_submit' => 'admin@admin_login_submit',
    'admin_logout' => 'admin@admin_logout',
    
    // Dashboard e Configurações
    'admin' => 'admin@admin',
    'admin_configuracoes' => 'admin@admin_configuracoes',
    'admin_salvar_config' => 'admin@admin_salvar_config',
    
    // CRUD Serviços
    'admin_servicos' => 'admin@admin_servicos',
    'admin_servico_criar' => 'admin@admin_servico_criar',
    'admin_servico_editar' => 'admin@admin_servico_editar',
    'admin_servico_deletar' => 'admin@admin_servico_deletar',
    
    // CRUD Galeria
    'admin_galeria' => 'admin@admin_galeria',
    'admin_galeria_criar' => 'admin@admin_galeria_criar',
    'admin_galeria_deletar' => 'admin@admin_galeria_deletar',
    
    // CRUD Produtos
    'admin_produtos' => 'admin@admin_produtos',
    'admin_produto_criar' => 'admin@admin_produto_criar',
    'admin_produto_editar' => 'admin@admin_produto_editar',
    'admin_produto_deletar' => 'admin@admin_produto_deletar',
    
    // CRUD Publicações
    'admin_publicacoes' => 'admin@admin_publicacoes',
    'admin_publicacao_criar' => 'admin@admin_publicacao_criar',
    'admin_publicacao_editar' => 'admin@admin_publicacao_editar',
    'admin_publicacao_deletar' => 'admin@admin_publicacao_deletar',
    
    // Gestão de Clientes (da loja original)
    'lista_clientes' => 'admin@lista_clientes',
    'detalhe_cliente' => 'admin@detalhe_cliente',
    'cliente_historico_encomendas' => 'admin@cliente_historico_encomendas',
    
    // Gestão de Encomendas (da loja original)
    'lista_encomendas' => 'admin@lista_encomendas',
    'detalhe_encomenda' => 'admin@detalhe_encomenda',
    'encomenda_alterar_estado' => 'admin@encomenda_alterar_estado',
    'criar_pdf_encomenda' => 'admin@criar_pdf_encomenda',
];

// Define ação por defeito
$acao = $_GET['a'] ?? 'inicio';

// Verifica se a ação existe nas rotas
if(!key_exists($acao, $rotas)) {
    $acao = 'inicio';
}

// Trata a definição da rota
$partes = explode('@', $rotas[$acao]);
$controlador = 'core\\controllers\\' . ucfirst($partes[0]);
$metodo = $partes[1];

$ctr = new $controlador();
$ctr->$metodo();