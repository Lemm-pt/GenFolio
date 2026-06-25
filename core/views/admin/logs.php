<?php
/**
 * View: Logs de Auditoria
 * - Master (vitrine-demo): vê todos os logs
 * - Outros clientes: vêem apenas os seus logs
 */

// Verificar se as variáveis existem (fallback)
$isMaster = $isMaster ?? false;
$clienteSlug = $clienteSlug ?? ($_SESSION['cliente_slug'] ?? '');
$logs = $logs ?? [];
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-clipboard-list"></i> Logs de Auditoria</h2>
            <?php if($isMaster): ?>
                <span class="badge bg-warning text-dark">
                    <i class="fas fa-crown"></i> Modo Master - Todos os clientes
                </span>
            <?php else: ?>
                <span class="badge bg-info">
                    <i class="fas fa-user"></i> Logs de <?= htmlspecialchars($clienteSlug) ?>
                </span>
            <?php endif; ?>
        </div>
        <a href="?a=admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
    
    <?php if(empty($logs)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Nenhum log registado ainda.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <?php if($isMaster): ?>
                        <th>Cliente</th>
                        <?php endif; ?>
                        <th>Utilizador</th>
                        <th>Ação</th>
                        <th>Detalhes</th>
                        <th>IP</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($logs as $log): ?>
                    <tr>
                        <td><?= $log->id ?></td>
                        <?php if($isMaster): ?>
                        <td>
                            <?php if($log->cliente_id): ?>
                                <?php
                                // Buscar o slug do cliente (apenas para master)
                                $db = new \core\classes\Database();
                                $cliente = $db->select(
                                    "SELECT slug FROM sevenlux_clientes WHERE id_cliente = :id",
                                    [':id' => $log->cliente_id]
                                );
                                $slug = $cliente ? $cliente[0]->slug : 'desconhecido';
                                ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($slug) ?></span>
                            <?php else: ?>
                                <span class="text-muted">Sistema</span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <td>
                            <?php if($log->usuario): ?>
                                <span class="text-primary"><?= htmlspecialchars($log->usuario) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $badgeClass = 'secondary';
                            if (strpos($log->acao, 'sucesso') !== false || $log->acao === 'login') {
                                $badgeClass = 'success';
                            } elseif (strpos($log->acao, 'falha') !== false || strpos($log->acao, 'erro') !== false) {
                                $badgeClass = 'danger';
                            } elseif ($log->acao === 'logout') {
                                $badgeClass = 'warning';
                            } elseif (strpos($log->acao, 'criar') !== false) {
                                $badgeClass = 'primary';
                            } elseif (strpos($log->acao, 'editar') !== false || strpos($log->acao, 'atualizar') !== false) {
                                $badgeClass = 'info';
                            } elseif (strpos($log->acao, 'deletar') !== false || strpos($log->acao, 'excluir') !== false) {
                                $badgeClass = 'danger';
                            } elseif (strpos($log->acao, 'recuperar') !== false) {
                                $badgeClass = 'warning';
                            } elseif (strpos($log->acao, 'bloqueio') !== false) {
                                $badgeClass = 'dark';
                            }
                            ?>
                            <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($log->acao) ?></span>
                        </td>
                        <td style="max-width: 300px;">
                            <small><?= htmlspecialchars($log->detalhes ?? '-') ?></small>
                        </td>
                        <td><code><?= htmlspecialchars($log->ip) ?></code></td>
                        <td><small><?= date('d/m/Y H:i:s', strtotime($log->created_at)) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="text-muted">
            <small><i class="fas fa-info-circle"></i> Últimos <?= count($logs) ?> registos</small>
        </p>
    <?php endif; ?>
</div>

<style>
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}
.table td, .table th {
    vertical-align: middle;
}
.table td code {
    font-size: 0.75rem;
}
</style>