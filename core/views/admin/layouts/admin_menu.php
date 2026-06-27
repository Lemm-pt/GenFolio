
<?php use core\classes\Store; ?>



<div class="admin-sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-crown"></i> Seven<span class="text-gold">Lux</span></h4>
        <button class="sidebar-close d-md-none" onclick="toggleMobileMenu()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <a href="?a=admin" class="nav-item <?= ($_GET['a'] ?? '') == 'admin' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="?a=admin_servicos" class="nav-item <?= strpos($_GET['a'] ?? '', 'servico') !== false ? 'active' : '' ?>">
            <i class="fas fa-concierge-bell"></i> Serviços
        </a>
        <a href="?a=admin_galeria" class="nav-item <?= ($_GET['a'] ?? '') == 'admin_galeria' ? 'active' : '' ?>">
            <i class="fas fa-images"></i> Galeria
        </a>
        <a href="?a=admin_produtos" class="nav-item <?= strpos($_GET['a'] ?? '', 'produto') !== false ? 'active' : '' ?>">
            <i class="fas fa-box"></i> Produtos
        </a>
        <a href="?a=admin_publicacoes" class="nav-item <?= strpos($_GET['a'] ?? '', 'publicacao') !== false ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i> Publicações
        </a>
        <a href="?a=admin_configuracoes" class="nav-item <?= ($_GET['a'] ?? '') == 'admin_configuracoes' ? 'active' : '' ?>">
            <i class="fas fa-cog"></i> Configurações
        </a>
        <a href="?a=admin_logs" class="nav-item <?= ($_GET['a'] ?? '') == 'admin_logs' ? 'active' : '' ?>">
    <i class="fas fa-clipboard-list"></i> Logs
</a>
    </nav>
    
    <div class="sidebar-footer">
     <a href="<?= BASE_URL . ($_SESSION['cliente_slug'] ?? 'vitrine-demo') ?>/" target="_blank" class="btn btn-outline-light w-100">
          <i class="fas fa-external-link-alt"></i> Ver Site
    </a>
    </div>
</div>

<script>
function toggleMobileMenu() {
    document.querySelector('.admin-sidebar').classList.toggle('mobile-open');
}
</script>