<!-- core/views/admin/admin_menu.php -->
<div class="sl-sidebar">
    <div class="sl-header">
        <h4><i class="fas fa-crown"></i> Seven<span class="text-gold">Lux</span></h4>
        <button class="sl-close d-md-none" onclick="toggleMobileMenu()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- 🔥 BOTÃO VER REINO DESTACADO -->
    <div class="sl-top-actions">
        <a href="<?= BASE_URL . CLIENTE_SLUG ?>/" target="_blank" class="sl-btn-ver-reino">
            <i class="fas fa-external-link-alt"></i> 
            <span>Ver Site</span>
            <span class="sl-badge-reino">🔗 live</span>
        </a>
    </div>
    
    <div class="sl-user">
        <div class="sl-user-info">
            <i class="fas fa-user-circle"></i>
            <span class="sl-user-slug"><?= htmlspecialchars(CLIENTE_SLUG) ?></span>
        </div>
        <div class="sl-luzes-mini">
            <?php 
            $cristaisMini = new \core\models\Cristais(CLIENTE_ID);
            foreach ($cristaisMini->getAll() as $key => $c): 
            ?>
                <span class="sl-luz-mini <?= $c['ativo'] ? 'sl-ativa' : '' ?>" 
                      style="background: <?= $c['ativo'] ? $c['cor'] : '#2a2a3a' ?>;"
                      title="<?= $c['nome'] ?>">
                </span>
            <?php endforeach; ?>
        </div>
    </div>
    
    <nav class="sl-nav">
        <a href="?a=admin" class="sl-item <?= ($_GET['a'] ?? '') == 'admin' ? 'sl-active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span class="sl-label">
                <span class="sl-title">Dashboard</span>
                <span class="sl-subtitle">O Mirante do Reino</span>
            </span>
        </a>
        
        <a href="?a=admin_configuracoes" class="sl-item <?= ($_GET['a'] ?? '') == 'admin_configuracoes' ? 'sl-active' : '' ?>">
            <i class="fas fa-crown"></i>
            <span class="sl-label">
                <span class="sl-title">Configurações</span>
                <span class="sl-subtitle">A Essência do Reino</span>
            </span>
        </a>
        
        <a href="?a=admin_servicos" class="sl-item <?= strpos($_GET['a'] ?? '', 'servico') !== false ? 'sl-active' : '' ?>">
            <i class="fas fa-hammer"></i>
            <span class="sl-label">
                <span class="sl-title">Serviços</span>
                <span class="sl-subtitle">Os Ofícios do Guardião</span>
            </span>
        </a>
        
        <a href="?a=admin_produtos" class="sl-item <?= strpos($_GET['a'] ?? '', 'produto') !== false ? 'sl-active' : '' ?>">
            <i class="fas fa-gem"></i>
            <span class="sl-label">
                <span class="sl-title">Produtos</span>
                <span class="sl-subtitle">A Vitrina dos Artefactos</span>
            </span>
        </a>
        
        <a href="?a=admin_galeria" class="sl-item <?= ($_GET['a'] ?? '') == 'admin_galeria' ? 'sl-active' : '' ?>">
            <i class="fas fa-images"></i>
            <span class="sl-label">
                <span class="sl-title">Galeria</span>
                <span class="sl-subtitle">O Espelho das Profundezas</span>
            </span>
        </a>
        
        <a href="?a=admin_publicacoes" class="sl-item <?= strpos($_GET['a'] ?? '', 'publicacao') !== false ? 'sl-active' : '' ?>">
            <i class="fas fa-book"></i>
            <span class="sl-label">
                <span class="sl-title">Publicações</span>
                <span class="sl-subtitle">A Biblioteca das Crónicas</span>
            </span>
        </a>
        
        <a href="?a=admin_social" class="sl-item <?= ($_GET['a'] ?? '') == 'admin_social' ? 'sl-active' : '' ?>">
            <i class="fas fa-share-alt"></i>
            <span class="sl-label">
                <span class="sl-title">Redes Sociais</span>
                <span class="sl-subtitle">Os Ecos do Mundo</span>
            </span>
        </a>
        
        <a href="?a=admin_gestao_conta" class="sl-item <?= ($_GET['a'] ?? '') == 'admin_gestao_conta' ? 'sl-active' : '' ?>">
            <i class="fas fa-user-cog"></i>
            <span class="sl-label">
                <span class="sl-title">Gestão de Conta</span>
                <span class="sl-subtitle">O Reino do Guardião</span>
            </span>
        </a>
        
        <a href="?a=admin_logs" class="sl-item <?= ($_GET['a'] ?? '') == 'admin_logs' ? 'sl-active' : '' ?>">
            <i class="fas fa-scroll"></i>
            <span class="sl-label">
                <span class="sl-title">Logs</span>
                <span class="sl-subtitle">O Pergaminho das Acções</span>
            </span>
        </a>
    </nav>
    
    <div class="sl-footer">
        <small>SevenLux v1.0</small>
    </div>
</div>

<style>
/* ============================================
   SIDEBAR - CORES COM !important
   ============================================ */

/* Container principal */
.sl-sidebar {
    background: #0a0a14 !important;
    min-height: 100vh !important;
    padding: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    border-right: 1px solid rgba(198, 164, 63, 0.1) !important;
}

/* Header */
.sl-header {
    padding: 16px 20px 12px 20px !important;
    border-bottom: 1px solid rgba(198, 164, 63, 0.1) !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-shrink: 0 !important;
}

.sl-header h4 {
    color: #ffffff !important;
    margin: 0 !important;
    font-size: 1.3rem !important;
    font-weight: 700 !important;
}

.sl-header h4 .text-gold {
    color: #C6A43F !important;
}

.sl-close {
    background: none !important;
    border: none !important;
    color: #ffffff !important;
    font-size: 24px !important;
    cursor: pointer !important;
    padding: 0 5px !important;
    display: none !important;
}

/* Top actions - Botão Ver Site */
.sl-top-actions {
    padding: 10px 15px !important;
    border-bottom: 1px solid rgba(198, 164, 63, 0.08) !important;
    flex-shrink: 0 !important;
}

.sl-btn-ver-reino {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    padding: 10px 16px !important;
    border-radius: 8px !important;
    background: rgba(198, 164, 63, 0.15) !important;
    border: 1px solid rgba(198, 164, 63, 0.25) !important;
    color: #C6A43F !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
}

.sl-btn-ver-reino:hover {
    background: #C6A43F !important;
    color: #0a0a14 !important;
    text-decoration: none !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 20px rgba(198, 164, 63, 0.3) !important;
}

.sl-btn-ver-reino i {
    font-size: 0.9rem !important;
}

.sl-badge-reino {
    font-size: 0.5rem !important;
    background: rgba(198, 164, 63, 0.2) !important;
    padding: 2px 8px !important;
    border-radius: 10px !important;
    color: rgba(198, 164, 63, 0.7) !important;
    font-weight: 400 !important;
}

.sl-btn-ver-reino:hover .sl-badge-reino {
    background: rgba(255, 255, 255, 0.2) !important;
    color: #0a0a14 !important;
}

/* Sidebar user */
.sl-user {
    padding: 12px 15px !important;
    border-bottom: 1px solid rgba(198, 164, 63, 0.08) !important;
    flex-shrink: 0 !important;
}

.sl-user-info {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}

.sl-user-info i {
    color: #C6A43F !important;
    font-size: 1.1rem !important;
}

.sl-user-slug {
    color: #ffffff !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
}

/* Luzes mini */
.sl-luzes-mini {
    display: flex !important;
    gap: 5px !important;
    margin-top: 8px !important;
}

.sl-luz-mini {
    width: 16px !important;
    height: 16px !important;
    border-radius: 50% !important;
    display: inline-block !important;
    transition: all 0.3s ease !important;
    border: 1px solid rgba(255,255,255,0.05) !important;
}

.sl-luz-mini.sl-ativa {
    box-shadow: 0 0 14px rgba(198, 164, 63, 0.3) !important;
}

/* Navigation */
.sl-nav {
    display: flex !important;
    flex-direction: column !important;
    gap: 2px !important;
    padding: 8px 10px !important;
    flex: 1 !important;
    overflow-y: auto !important;
}

.sl-item {
    display: flex !important;
    align-items: center !important;
    gap: 14px !important;
    padding: 10px 14px !important;
    color: #b0b8c8 !important;
    text-decoration: none !important;
    border-radius: 8px !important;
    transition: all 0.2s ease !important;
    border: none !important;
}

.sl-item:hover {
    background: rgba(198, 164, 63, 0.1) !important;
    color: #C6A43F !important;
    text-decoration: none !important;
}

.sl-item.sl-active {
    background: #C6A43F !important;
    color: #0a0a14 !important;
    text-decoration: none !important;
}

.sl-item i {
    width: 22px !important;
    font-size: 1rem !important;
    flex-shrink: 0 !important;
    text-align: center !important;
    color: #555 !important;
    transition: color 0.2s !important;
}

.sl-item:hover i {
    color: #C6A43F !important;
}

.sl-item.sl-active i {
    color: #0a0a14 !important;
}

.sl-label {
    display: flex !important;
    flex-direction: column !important;
    line-height: 1.3 !important;
    flex: 1 !important;
    min-width: 0 !important;
}

/* 🔥 COR BRANCA PARA O TÍTULO PRINCIPAL COM !important */
.sl-title {
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    transition: color 0.2s !important;
}

.sl-item:hover .sl-title {
    color: #C6A43F !important;
}

.sl-item.sl-active .sl-title {
    color: #0a0a14 !important;
}

/* 🔥 COR DOURADA PARA O SUBTÍTULO COM !important */
.sl-subtitle {
    font-size: 0.6rem !important;
    color: #C6A43F !important;
    font-weight: 400 !important;
    letter-spacing: 0.3px !important;
    opacity: 0.8 !important;
    transition: opacity 0.2s !important;
}

.sl-item:hover .sl-subtitle {
    opacity: 1 !important;
}

.sl-item.sl-active .sl-subtitle {
    color: rgba(10, 20, 20, 0.6) !important;
    opacity: 1 !important;
}

/* Footer */
.sl-footer {
    padding: 12px 20px !important;
    border-top: 1px solid rgba(255,255,255,0.03) !important;
    flex-shrink: 0 !important;
    text-align: center !important;
}

.sl-footer small {
    color: rgba(255, 255, 255, 0.12) !important;
    font-size: 0.5rem !important;
    letter-spacing: 1px !important;
}

/* ============================================
   RESPONSIVO - MOBILE
   ============================================ */
@media (max-width: 768px) {
    .sl-sidebar {
        position: fixed !important;
        left: -300px !important;
        top: 0 !important;
        width: 290px !important;
        z-index: 1050 !important;
        height: 100% !important;
        overflow-y: auto !important;
        transition: left 0.3s ease !important;
        box-shadow: 2px 0 30px rgba(0, 0, 0, 0.5) !important;
    }
    
    .sl-sidebar.sl-mobile-open {
        left: 0 !important;
    }
    
    .sl-close {
        display: block !important;
    }
    
    .sl-item {
        padding: 8px 12px !important;
    }
    
    .sl-title {
        font-size: 0.85rem !important;
    }
    
    .sl-subtitle {
        font-size: 0.55rem !important;
    }
}

/* Overlay */
.sl-overlay {
    display: none !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0, 0, 0, 0.5) !important;
    z-index: 1040 !important;
}

.sl-overlay.sl-active {
    display: block !important;
}
</style>

<script>
function toggleMobileMenu() {
    const sidebar = document.querySelector('.sl-sidebar');
    const overlay = document.querySelector('.sl-overlay');
    if (sidebar) {
        sidebar.classList.toggle('sl-mobile-open');
        if (overlay) overlay.classList.toggle('sl-active');
        document.body.style.overflow = sidebar.classList.contains('sl-mobile-open') ? 'hidden' : '';
    }
}

function fecharMobileMenu() {
    const sidebar = document.querySelector('.sl-sidebar');
    const overlay = document.querySelector('.sl-overlay');
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