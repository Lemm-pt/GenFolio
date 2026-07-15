<div class="container-fluid dashboard-container">
    <!-- ============================================ -->
    <!-- HEADER COM SLUG E BOTÕES -->
    <!-- ============================================ -->
    <div class="dashboard-header d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h2 class="dashboard-title"><i class="fas fa-tachometer-alt text-gold"></i> Painel de Controlo</h2>
           
        </div>
        <div class="dashboard-actions mt-2 mt-md-0">
            <a href="<?= BASE_URL . CLIENTE_SLUG ?>/" target="_blank" class="btn btn-sm btn-outline-gold">
                <i class="fas fa-external-link-alt"></i> Ver Site
            </a>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- ALERTA DE STATUS (se conta pausada) -->
    <!-- ============================================ -->
    <?php
    $clientModel = new \core\models\Clientes();
    $statusConta = $clientModel->getStatusConta($_SESSION['cliente_id']);
    if ($statusConta['status'] !== 'ativa'):
    ?>
    <div class="alert alert-warning d-flex align-items-center gap-3 flex-wrap mb-4">
        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
        <div class="flex-grow-1">
            <strong>Conta <?= ucfirst($statusConta['status']) ?></strong>
            <p class="mb-0 small"><?= $statusConta['mensagem'] ?></p>
        </div>
        <?php if ($statusConta['status'] === 'pausada'): ?>
            <a href="?a=admin_gestao_conta" class="btn btn-sm btn-warning">
                <i class="fas fa-play"></i> Reativar
            </a>
        <?php elseif ($statusConta['status'] === 'pendente_eliminacao'): ?>
            <a href="?a=admin_gestao_conta" class="btn btn-sm btn-danger">
                <i class="fas fa-undo"></i> Cancelar
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- CONFIGURAÇÕES RÁPIDAS (Topo) -->
    <!-- ============================================ -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card quick-config-card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-cog fa-2x text-gold"></i>
                            <div>
                                <h6 class="mb-0">Configurações Rápidas</h6>
                                <small class="text-muted">Gerir logotipo, slogan e contactos</small>
                            </div>
                        </div>
                        <a href="?a=admin_configuracoes" class="btn btn-sm btn-gold">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- CARDS DE MÓDULOS (Grid 2 colunas em mobile) -->
    <!-- ============================================ -->
    <div class="row g-3 modules-grid">
        
        <!-- Serviços -->
        <div class="col-6 col-md-3">
            <div class="card module-card">
                <div class="card-body text-center">
                    <div class="module-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3 class="module-count"><?= isset($servicos) ? count($servicos) : 0 ?></h3>
                    <p class="module-label">Serviços</p>
                    <a href="?a=admin_servicos" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Galeria -->
        <div class="col-6 col-md-3">
            <div class="card module-card">
                <div class="card-body text-center">
                    <div class="module-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="module-count"><?= isset($galeria) ? count($galeria) : 0 ?></h3>
                    <p class="module-label">Galeria <small class="text-muted">(max 7)</small></p>
                    <a href="?a=admin_galeria" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Produtos -->
        <div class="col-6 col-md-3">
            <div class="card module-card">
                <div class="card-body text-center">
                    <div class="module-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 class="module-count"><?= isset($produtos) ? count($produtos) : 0 ?></h3>
                    <p class="module-label">Produtos <small class="text-muted">(max 7)</small></p>
                    <a href="?a=admin_produtos" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Publicações -->
        <div class="col-6 col-md-3">
            <div class="card module-card">
                <div class="card-body text-center">
                    <div class="module-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="module-count"><?= isset($publicacoes) ? count($publicacoes) : 0 ?></h3>
                    <p class="module-label">Publicações <small class="text-muted">(max 7)</small></p>
                    <a href="?a=admin_publicacoes" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>
    </div>

  

        <!-- Estatísticas rápidas -->
        <div class="col-md-6">
            <div class="card info-card">
                <div class="card-body">
                    <div class="row g-2 text-center">
                        <div class="col-3">
                            <div class="stat-item">
                                <span class="stat-number"><?= isset($servicos) ? count($servicos) : 0 ?></span>
                                <span class="stat-label">Serviços</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="stat-item">
                                <span class="stat-number"><?= isset($galeria) ? count($galeria) : 0 ?></span>
                                <span class="stat-label">Fotos</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="stat-item">
                                <span class="stat-number"><?= isset($produtos) ? count($produtos) : 0 ?></span>
                                <span class="stat-label">Produtos</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="stat-item">
                                <span class="stat-number"><?= isset($publicacoes) ? count($publicacoes) : 0 ?></span>
                                <span class="stat-label">Posts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- CONTADOR DE VISITAS (Rodapé do Dashboard) -->
    <!-- ============================================ -->
    <?php if (defined('CLIENTE_ID') && CLIENTE_ID > 0): 
        $visitasModel = new \core\models\Visitas(CLIENTE_ID, CLIENTE_SLUG);
        $stats = $visitasModel->getEstatisticas(CLIENTE_ID);
    ?>
    <div class="mt-4 p-3 dashboard-footer">
        <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
            <small class="text-muted">
                <i class="fas fa-eye text-gold"></i>
                <strong>Total:</strong> <?= number_format($stats['total']) ?>
            </small>
            <small class="text-muted">
                <i class="fas fa-calendar-day text-gold"></i>
                <strong>Hoje:</strong> <?= number_format($stats['hoje']) ?>
            </small>
            <small class="text-muted">
                <i class="fas fa-calendar-week text-gold"></i>
                <strong>Semana:</strong> <?= number_format($stats['semana']) ?>
            </small>
            <small class="text-muted">
                <i class="fas fa-calendar-alt text-gold"></i>
                <strong>Mês:</strong> <?= number_format($stats['mes']) ?>
            </small>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* ============================================
   DASHBOARD ADMIN - RESPONSIVO
   ============================================ */

.dashboard-container {
    padding: 0.5rem;
}

/* Header */
.dashboard-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0;
    color: #1a1a2e;
}

.dashboard-title i {
    margin-right: 8px;
}

.dashboard-actions .btn-outline-gold {
    border-color: #C6A43F;
    color: #C6A43F;
    font-size: 0.75rem;
    padding: 4px 14px;
    border-radius: 50px;
}

.dashboard-actions .btn-outline-gold:hover {
    background: #C6A43F;
    color: #1a1a2e;
}

/* Quick Config Card */
.quick-config-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    border: 1px solid #e0e0e0;
    border-radius: 12px;
}

.quick-config-card .card-body {
    padding: 12px 18px;
}

/* Module Cards */
.module-card {
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: white;
    height: 100%;
}

.module-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
}

.module-card .card-body {
    padding: 16px 12px;
}

.module-icon {
    width: 44px;
    height: 44px;
    margin: 0 auto 8px auto;
    background: rgba(198, 164, 63, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #C6A43F;
}

.module-count {
    font-size: 1.6rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
    line-height: 1.2;
}

.module-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: #666;
    margin: 2px 0 10px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.module-label small {
    font-weight: 400;
    text-transform: none;
    font-size: 0.6rem;
}

.module-card .btn-outline-gold {
    font-size: 0.7rem;
    padding: 4px 8px;
    border-radius: 50px;
    border-color: #C6A43F;
    color: #C6A43F;
}

.module-card .btn-outline-gold:hover {
    background: #C6A43F;
    color: #1a1a2e;
}

/* Info Cards */
.info-card {
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    background: white;
}

.info-card .card-body {
    padding: 12px 16px;
}

.logo-preview-mini {
    min-width: 60px;
    text-align: center;
}

/* Stats */
.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1.2;
}

.stat-label {
    font-size: 0.6rem;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Dashboard Footer */
.dashboard-footer {
    background: rgba(0, 0, 0, 0.02);
    border-radius: 12px;
    border: 1px solid #e8e8e8;
}

.dashboard-footer small {
    font-size: 0.7rem;
}

.dashboard-footer .text-gold {
    color: #C6A43F !important;
}

/* Alert */
.alert-warning {
    background: rgba(255, 193, 7, 0.08);
    border: 1px solid rgba(255, 193, 7, 0.2);
    border-radius: 12px;
    color: #856404;
    padding: 12px 16px;
}

.alert-warning .btn-warning {
    background: #ffc107;
    border: none;
    color: #1a1a2e;
    font-weight: 600;
    border-radius: 50px;
    padding: 4px 16px;
    font-size: 0.75rem;
}

.alert-warning .btn-warning:hover {
    background: #e0a800;
}

.alert-warning .btn-danger {
    background: #dc3545;
    border: none;
    border-radius: 50px;
    padding: 4px 16px;
    font-size: 0.75rem;
}

.alert-warning .btn-danger:hover {
    background: #c82333;
}

/* ============================================
   RESPONSIVO
   ============================================ */

/* Tablets e telemóveis grandes */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 0.25rem;
    }
    
    .dashboard-title {
        font-size: 1.1rem;
    }
    
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 6px;
    }
    
    .dashboard-actions {
        width: 100%;
    }
    
    .dashboard-actions .btn {
        width: 100%;
        text-align: center;
    }
    
    .quick-config-card .card-body {
        padding: 10px 14px;
    }
    
    .quick-config-card h6 {
        font-size: 0.85rem;
    }
    
    .quick-config-card small {
        font-size: 0.7rem;
    }
    
    .module-card .card-body {
        padding: 12px 8px;
    }
    
    .module-icon {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
    
    .module-count {
        font-size: 1.3rem;
    }
    
    .module-label {
        font-size: 0.6rem;
    }
    
    .module-card .btn-outline-gold {
        font-size: 0.6rem;
        padding: 3px 6px;
    }
    
    .info-card .card-body {
        padding: 10px 14px;
    }
    
    .stat-number {
        font-size: 1rem;
    }
    
    .stat-label {
        font-size: 0.5rem;
    }
    
    .dashboard-footer {
        padding: 10px;
    }
    
    .dashboard-footer small {
        font-size: 0.6rem;
    }
}

/* Telemóveis pequenos */
@media (max-width: 576px) {
    .modules-grid .col-6 {
        padding: 6px;
    }
    
    .module-card .card-body {
        padding: 10px 6px;
    }
    
    .module-icon {
        width: 32px;
        height: 32px;
        font-size: 0.85rem;
        margin-bottom: 4px;
    }
    
    .module-count {
        font-size: 1.1rem;
    }
    
    .module-label {
        font-size: 0.5rem;
        margin-bottom: 6px;
    }
    
    .module-label small {
        display: none;
    }
    
    .module-card .btn-outline-gold {
        font-size: 0.55rem;
        padding: 2px 4px;
    }
    
    .quick-config-card .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 8px;
    }
    
    .quick-config-card .btn {
        width: 100%;
        text-align: center;
    }
    
    .alert-warning {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .alert-warning .btn {
        width: 100%;
    }
    
    .info-card .d-flex {
        flex-direction: column;
        align-items: center !important;
        text-align: center;
    }
    
    .logo-preview-mini {
        min-width: auto;
    }
    
    .stat-item {
        padding: 4px 0;
    }
    
    .stat-number {
        font-size: 0.9rem;
    }
    
    .dashboard-footer .d-flex {
        gap: 8px !important;
    }
    
    .dashboard-footer small {
        font-size: 0.55rem;
    }
}

/* Telemóveis muito pequenos */
@media (max-width: 360px) {
    .modules-grid .col-6 {
        padding: 4px;
    }
    
    .module-card .card-body {
        padding: 8px 4px;
    }
    
    .module-icon {
        width: 28px;
        height: 28px;
        font-size: 0.7rem;
    }
    
    .module-count {
        font-size: 0.9rem;
    }
    
    .module-label {
        font-size: 0.45rem;
    }
    
    .module-card .btn-outline-gold {
        font-size: 0.5rem;
        padding: 2px 4px;
    }
    
    .dashboard-title {
        font-size: 0.95rem;
    }
}
</style>