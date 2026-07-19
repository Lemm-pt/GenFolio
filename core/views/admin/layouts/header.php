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
<div class="sl-overlay" id="slOverlay"></div>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-dark d-md-none" type="button" id="menuToggle">
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
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sl-sidebar');
    const overlay = document.getElementById('slOverlay');
    const closeBtn = document.querySelector('.sl-close');
    
    console.log('Menu mobile - elementos encontrados:', { 
        menuToggle: !!menuToggle, 
        sidebar: !!sidebar, 
        overlay: !!overlay,
        closeBtn: !!closeBtn
    });
    
    function abrirMenu() {
        if (sidebar) sidebar.classList.add('sl-mobile-open');
        if (overlay) overlay.classList.add('sl-active');
        document.body.style.overflow = 'hidden';
        console.log('Menu aberto');
    }
    
    function fecharMenu() {
        if (sidebar) sidebar.classList.remove('sl-mobile-open');
        if (overlay) overlay.classList.remove('sl-active');
        document.body.style.overflow = '';
        console.log('Menu fechado');
    }
    
    // Abrir/fechar ao clicar no botão hambúrguer
    if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (sidebar && sidebar.classList.contains('sl-mobile-open')) {
                fecharMenu();
            } else {
                abrirMenu();
            }
        });
    }
    
    // 🔥 FECHAR AO CLICAR NO BOTÃO X (close)
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Botão X clicado!');
            fecharMenu();
        });
    }
    
    // Fechar ao clicar no overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            fecharMenu();
        });
    }
    
    // Fechar ao clicar num link do menu (mobile)
    document.querySelectorAll('.sl-item').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                fecharMenu();
            }
        });
    });
    
    // Fechar ao redimensionar para desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            fecharMenu();
        }
    });
});
</script>

<style>
/* ============================================
   MENU MOBILE - ESTILOS
   ============================================ */

/* Overlay */
.sl-overlay {
    display: none !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0, 0, 0, 0.6) !important;
    z-index: 1040 !important;
}

.sl-overlay.sl-active {
    display: block !important;
}

/* Botão hambúrguer */
#menuToggle {
    font-size: 1.2rem;
    padding: 4px 10px;
    border: none;
    background: transparent;
    color: #e0e0e0;
    cursor: pointer;
}

#menuToggle:hover {
    color: #C6A43F;
}

#menuToggle i {
    font-size: 1.4rem;
}

/* Botão X (close) no mobile */
.sl-close {
    display: none !important;
    background: none !important;
    border: none !important;
    color: #ffffff !important;
    font-size: 24px !important;
    cursor: pointer !important;
    padding: 0 5px !important;
}

/* Sidebar em mobile */
@media (max-width: 768px) {
    .sl-sidebar {
        position: fixed !important;
        left: -300px !important;
        top: 0 !important;
        width: 290px !important;
        z-index: 1050 !important;
        height: 100% !important;
        overflow-y: auto !important;
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 2px 0 30px rgba(0, 0, 0, 0.5) !important;
        background: #0a0a14 !important;
        border-right: 1px solid rgba(198, 164, 63, 0.1) !important;
    }
    
    .sl-sidebar.sl-mobile-open {
        left: 0 !important;
    }
    
    .sl-close {
        display: block !important;
    }
}
</style>