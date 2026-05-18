<?php
// Rota especial para clientes inactivos
$rotas = [
    '' => 'main@conta_inativa',
    'inicio' => 'main@conta_inativa',
];

$acao = $_GET['a'] ?? '';
if(empty($acao)) $acao = 'inicio';
if(!isset($rotas[$acao])) $acao = 'inicio';

$partes = explode('@', $rotas[$acao]);
$controlador = 'core\\controllers\\' . ucfirst($partes[0]);
$metodo = $partes[1];
$ctr = new $controlador();
$ctr->$metodo();