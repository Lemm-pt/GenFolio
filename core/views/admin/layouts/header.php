<?php 
use core\classes\Store;

// 🔥 DETERMINAR A SECÇÃO ATUAL PARA A MENSAGEM DO LUXOR
$acao = $_GET['a'] ?? '';
$mapaSecoes = [
    'admin' => 'dashboard',
    'admin_configuracoes' => 'configuracoes',
    'admin_servicos' => 'servicos',
    'admin_servico_criar' => 'servicos',
    'admin_servico_editar' => 'servicos',
    'admin_produtos' => 'produtos',
    'admin_produto_criar' => 'produtos',
    'admin_produto_editar' => 'produtos',
    'admin_galeria' => 'galeria',
    'admin_publicacoes' => 'publicacoes',
    'admin_publicacao_criar' => 'publicacoes',
    'admin_publicacao_editar' => 'publicacoes',
    'admin_social' => 'social',
    'admin_gestao_conta' => 'gestao_conta',
    'admin_logs' => 'logs'
];
$secao = $mapaSecoes[$acao] ?? 'dashboard';
?>

<!-- 🔥 OVERLAY PARA FECHAR MENU MOBILE -->
<div class="sl-overlay" id="slOverlay" onclick="fecharMobileMenu()"></div>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-dark d-md-none" type="button" onclick="toggleMobileMenu()" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="<?= Store::getBaseUrl() ?>admin">
                <i class="fas fa-crown"></i> Admin
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-gold d-none d-sm-inline" style="font-size: 0.8rem;">
                <i class="fas fa-user-circle"></i> 
                <?= $_SESSION['cliente_slug'] ?? 'Admin' ?>
            </span>
            <a href="?a=admin_logout" class="btn btn-sm btn-danger">
                <i class="fas fa-sign-out-alt d-none d-sm-inline"></i> <span class="d-sm-none">Sair</span>
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Menu Lateral -->
        <div class="col-md-3 col-lg-2 p-0">
            <?php include(__DIR__ . '/admin_menu.php'); ?>
        </div>
        
        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10">
            <div class="p-2 p-md-3">

<script>
function toggleMobileMenu() {
    const sidebar = document.querySelector('.sl-sidebar');
    const overlay = document.getElementById('slOverlay');
    if (sidebar) {
        sidebar.classList.toggle('sl-mobile-open');
        if (overlay) overlay.classList.toggle('sl-active');
        document.body.style.overflow = sidebar.classList.contains('sl-mobile-open') ? 'hidden' : '';
    }
}

function fecharMobileMenu() {
    const sidebar = document.querySelector('.sl-sidebar');
    const overlay = document.getElementById('slOverlay');
    if (sidebar) {
        sidebar.classList.remove('sl-mobile-open');
        if (overlay) overlay.classList.remove('sl-active');
        document.body.style.overflow = '';
    }
}

// Fechar menu ao redimensionar para desktop
window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
        fecharMobileMenu();
    }
});

// Fechar menu ao clicar num link (mobile)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.sl-item').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                fecharMobileMenu();
            }
        });
    });
});
</script>