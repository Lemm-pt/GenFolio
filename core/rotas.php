<?php
$rotas = [
    'inicio' => 'main@index',
    '' => 'main@index',
    'imovel' => 'main@imovel',
    'blog' => 'main@blog',
    'artigo' => 'main@artigo',
    'contacto' => 'main@contacto',
    'admin_login' => 'admin@admin_login',
    'admin_login_submit' => 'admin@admin_login_submit',
    'admin' => 'admin@admin',
    'admin_imovel_criar' => 'admin@admin_imovel_criar',
    'admin_imovel_editar' => 'admin@admin_imovel_editar',
    'admin_imovel_deletar' => 'admin@admin_imovel_deletar',
    'admin_publicacao_criar' => 'admin@admin_publicacao_criar',
    'admin_publicacao_editar' => 'admin@admin_publicacao_editar',
    'admin_publicacao_deletar' => 'admin@admin_publicacao_deletar',
    'admin_logout' => 'admin@admin_logout',
     'admin_configuracoes' => 'admin@admin_configuracoes',
];
$acao = $_GET['a'] ?? 'inicio';
if(!isset($rotas[$acao])) $acao = 'inicio';
$partes = explode('@', $rotas[$acao]);
$controlador = 'core\\controllers\\'.ucfirst($partes[0]);
$metodo = $partes[1];
$ctr = new $controlador();
$ctr->$metodo();