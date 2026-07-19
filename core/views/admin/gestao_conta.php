<?php
/**
 * Gestão de Conta - Admin
 */
$clienteModel = new \core\models\Clientes();
$statusConta = $clienteModel->getStatusConta($_SESSION['cliente_id']);
$slug = $_SESSION['cliente_slug'] ?? 'vitrine-demo';

// Função auxiliar para ícones de status
function getIconeStatus($status)
{
    $icones = [
        'ativa' => 'fa-check-circle text-success',
        'pausada' => 'fa-pause-circle text-warning',
        'desativada' => 'fa-circle text-secondary',
        'pendente_eliminacao' => 'fa-clock text-danger'
    ];
    return $icones[$status] ?? 'fa-circle';
}
?>

<div class="container mt-4 main-content">

    <!-- 🔥 LUXOR - MENSAGEM DA SECÇÃO -->
    <?php include('layouts/luxor_message.php'); ?>


    <h2><i class="fas fa-user-cog text-gold"></i> Gestão da Conta</h2>
    <p class="text-muted">Gerencie o estado da sua conta e do seu site.</p>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Status Atual -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle"></i> Status da Conta
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="status-indicator status-<?= $statusConta['status'] ?>">
                            <i class="fas <?= getIconeStatus($statusConta['status']) ?> fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-uppercase"><?= ucfirst($statusConta['status']) ?></h5>
                            <p class="text-muted small mb-0"><?= $statusConta['mensagem'] ?></p>
                            <?php if ($statusConta['status'] === 'pendente_eliminacao' && isset($statusConta['dias_restantes'])): ?>
                                <p class="text-warning small mb-0">
                                    <i class="fas fa-clock"></i> 
                                    Eliminação agendada em <?= $statusConta['dias_restantes'] ?> dias
                                </p>
                            <?php endif; ?>
                            <?php if ($statusConta['status'] !== 'ativa' && isset($statusConta['motivo'])): ?>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-comment"></i> Motivo: <?= htmlspecialchars($statusConta['motivo']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <small class="text-muted">
                        Última atualização: <?= date('d/m/Y H:i', strtotime($statusConta['updated_at'] ?? 'now')) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Opções de Gestão -->
    <div class="row g-4">
        
        <!-- Opção 1: Pausar -->
        <div class="col-md-4">
            <div class="card h-100 <?= $statusConta['status'] === 'pausada' ? 'border-warning' : '' ?>">
                <div class="card-body text-center">
                    <i class="fas fa-pause-circle fa-3x text-warning mb-3"></i>
                    <h5>Pausar Conta</h5>
                    <p class="small text-muted">
                        O site fica temporariamente offline. Os dados são mantidos.
                        Pode reativar a qualquer momento.
                    </p>
                    <?php if ($statusConta['status'] === 'pausada'): ?>
                        <form action="?a=admin_reativar_conta" method="POST">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-play"></i> Reativar Conta
                            </button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#modalPausar">
                            <i class="fas fa-pause"></i> Pausar
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Opção 2: Desativar -->
        <div class="col-md-4">
            <div class="card h-100 <?= $statusConta['status'] === 'desativada' ? 'border-secondary' : '' ?>">
                <div class="card-body text-center">
                    <i class="fas fa-toggle-off fa-3x text-secondary mb-3"></i>
                    <h5>Desativar Conta</h5>
                    <p class="small text-muted">
                        Desativa permanentemente o site. Os dados são preservados,
                        mas o site fica offline.
                    </p>
                    <?php if ($statusConta['status'] === 'desativada'): ?>
                        <form action="?a=admin_reativar_conta" method="POST">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-play"></i> Reativar Conta
                            </button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#modalDesativar">
                            <i class="fas fa-toggle-off"></i> Desativar
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Opção 3: Apagar -->
        <div class="col-md-4">
            <div class="card h-100 <?= $statusConta['status'] === 'pendente_eliminacao' ? 'border-danger' : '' ?>">
                <div class="card-body text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5>Apagar Conta</h5>
                    <p class="small text-muted">
                        <strong class="text-danger">Ação irreversível!</strong>
                        Todos os dados serão eliminados após 30 dias de carência.
                    </p>
                    <?php if ($statusConta['status'] === 'pendente_eliminacao'): ?>
                        <form action="?a=admin_cancelar_eliminacao" method="POST">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-undo"></i> Cancelar Eliminação
                            </button>
                        </form>
                        <small class="text-warning">
                            <i class="fas fa-clock"></i> Aguardando eliminação
                        </small>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalApagar">
                            <i class="fas fa-trash"></i> Solicitar Eliminação
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info adicional -->
    <div class="mt-4 p-3" style="background: rgba(198, 164, 63, 0.05); border-radius: 10px; border: 1px solid rgba(198, 164, 63, 0.1);">
        <p class="small text-muted mb-0">
            <i class="fas fa-info-circle text-gold"></i> 
            <strong>Nota:</strong> A eliminação da conta é um processo irreversível. 
            Após solicitar a eliminação, terá <strong>30 dias</strong> para cancelar o pedido. 
            Após este período, todos os dados serão permanentemente eliminados.
        </p>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAIS -->
<!-- ============================================ -->

<!-- Modal Pausar -->
<div class="modal fade" id="modalPausar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-light text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-warning">
                    <i class="fas fa-pause-circle"></i> Pausar Conta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?a=admin_pausar_conta" method="POST">
                <div class="modal-body">
                    <p>Tem certeza que deseja <strong>pausar</strong> a sua conta?</p>
                    <p class="text-muted small">
                        O seu site ficará offline temporariamente. Os dados serão mantidos
                        e poderá reativar a conta a qualquer momento.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Motivo (opcional)</label>
                        <textarea name="motivo" class="form-control" rows="2" 
                                  placeholder="Ex: Férias, manutenção, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-pause"></i> Pausar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Desativar -->
<div class="modal fade" id="modalDesativar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-light text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-secondary">
                    <i class="fas fa-toggle-off"></i> Desativar Conta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?a=admin_desativar_conta" method="POST">
                <div class="modal-body">
                    <p>Tem certeza que deseja <strong>desativar</strong> a sua conta?</p>
                    <p class="text-muted small">
                        O seu site ficará offline. Os dados serão preservados,
                        mas o site não ficará acessível ao público.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Motivo (opcional)</label>
                        <textarea name="motivo" class="form-control" rows="2" 
                                  placeholder="Ex: Encerramento temporário, reestruturação, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-toggle-off"></i> Desativar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Apagar -->
<div class="modal fade" id="modalApagar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-light text-white">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-trash-alt"></i> Apagar Conta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?a=admin_solicitar_eliminacao" method="POST">
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Ação irreversível!</strong>
                    </div>
                    <p>Tem certeza que deseja <strong class="text-danger">apagar permanentemente</strong> a sua conta?</p>
                    <ul class="text-muted small">
                        <li><i class="fas fa-times text-danger"></i> Todos os dados serão eliminados</li>
                        <li><i class="fas fa-times text-danger"></i> O site ficará inacessível</li>
                        <li><i class="fas fa-clock text-warning"></i> Tem 30 dias para cancelar</li>
                    </ul>
                    <div class="mb-3">
                        <label class="form-label">Motivo (opcional)</label>
                        <textarea name="motivo" class="form-control" rows="2" 
                                  placeholder="Ex: Encerramento do negócio, mudança de plataforma, etc."></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="confirmarEliminacao" required>
                        <label class="form-check-label small" for="confirmarEliminacao">
                            <span class="text-danger">Confirmo que compreendo que esta ação é irreversível</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="btnEliminar" disabled>
                        <i class="fas fa-trash"></i> Solicitar Eliminação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ============================================
   GESTÃO DE CONTA - ESTILOS
   ============================================ */

/* Status Indicator */
.status-indicator {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.status-indicator.status-ativa {
    background: rgba(40, 167, 69, 0.15);
    border: 2px solid #28a745;
}

.status-indicator.status-pausada {
    background: rgba(255, 193, 7, 0.15);
    border: 2px solid #ffc107;
}

.status-indicator.status-desativada {
    background: rgba(108, 117, 125, 0.15);
    border: 2px solid #6c757d;
}

.status-indicator.status-pendente_eliminacao {
    background: rgba(220, 53, 69, 0.15);
    border: 2px solid #dc3545;
}

/* Cards */
.card.border-warning {
    border: 2px solid #ffc107 !important;
}

.card.border-secondary {
    border: 2px solid #6c757d !important;
}

.card.border-danger {
    border: 2px solid #dc3545 !important;
}

.card .btn {
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.card .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Cores dos botões */
.btn-warning {
    background: #ffc107;
    border: none;
    color: #0a0a1a;
}

.btn-warning:hover {
    background: #e0a800;
    color: #0a0a1a;
}

.btn-success {
    background: #28a745;
    border: none;
}

.btn-success:hover {
    background: #1e7e34;
}

.btn-secondary {
    background: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-danger {
    background: #dc3545;
    border: none;
}

.btn-danger:hover {
    background: #c82333;
}

/* Modais */
.modal-content {
    border-radius: 16px;
    border: 1px solid rgba(198, 164, 63, 0.2);
}

.modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.modal .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
}

.modal .form-control {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    border-radius: 10px;
}

.modal .form-control:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #C6A43F;
    box-shadow: 0 0 0 0.2rem rgba(198, 164, 63, 0.25);
}

.modal .form-check-input:checked {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Alerts */
.alert-success {
    background: rgba(40, 167, 69, 0.15);
    border: 1px solid rgba(40, 167, 69, 0.3);
    color: #5ddb6f;
    border-radius: 10px;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.15);
    border: 1px solid rgba(220, 53, 69, 0.3);
    color: #ff6b6b;
    border-radius: 10px;
}

/* Responsivo */
@media (max-width: 768px) {
    .status-indicator {
        width: 45px;
        height: 45px;
        font-size: 1.2rem;
    }
    
    .card .col-md-4 {
        margin-bottom: 15px;
    }
}

@media (max-width: 576px) {
    .status-indicator {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .modal-dialog {
        margin: 10px;
    }
}
</style>

<script>
// Ativar botão de eliminação apenas quando checkbox estiver marcado
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('confirmarEliminacao');
    const btnEliminar = document.getElementById('btnEliminar');
    
    if (checkbox && btnEliminar) {
        checkbox.addEventListener('change', function() {
            btnEliminar.disabled = !this.checked;
        });
    }
});
</script>