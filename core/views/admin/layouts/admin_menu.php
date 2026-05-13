<div class="admin-sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-crown"></i> Vitrine<span class="text-gold">.lemm</span></h4>
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
    </nav>
    
    <div class="sidebar-footer">
        <a href="?a=inicio" target="_blank" class="btn btn-outline-light w-100">
            <i class="fas fa-external-link-alt"></i> Ver Site
        </a>
        <a href="?a=admin_logout" class="btn btn-danger w-100 mt-2">
            <i class="fas fa-sign-out-alt"></i> Sair
        </a>
    </div>
</div>

<style>
.admin-sidebar {
    background: linear-gradient(180deg, #1a1a2e 0%, #0f0f1a 100%);
    min-height: 100vh;
    padding: 20px 0;
    transition: all 0.3s ease;
}

.sidebar-header {
    padding: 0 20px 20px 20px;
    border-bottom: 1px solid rgba(198, 164, 63, 0.2);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar-header h4 {
    color: white;
    margin: 0;
}

.sidebar-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 0 15px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    color: #e0e0e0;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.2s;
}

.nav-item i {
    width: 24px;
    font-size: 18px;
}

.nav-item:hover {
    background: rgba(198, 164, 63, 0.15);
    color: #C6A43F;
}

.nav-item.active {
    background: #C6A43F;
    color: #1a1a2e;
}

.sidebar-footer {
    padding: 20px;
    margin-top: 30px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

@media (max-width: 768px) {
    .admin-sidebar {
        position: fixed;
        left: -280px;
        top: 0;
        width: 280px;
        z-index: 1050;
        height: 100%;
        overflow-y: auto;
    }
    .admin-sidebar.mobile-open {
        left: 0;
    }
}
</style>

<script>
function toggleMobileMenu() {
    document.querySelector('.admin-sidebar').classList.toggle('mobile-open');
}
</script>