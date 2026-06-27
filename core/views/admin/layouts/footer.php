<!-- core/views/admin/layouts/footer.php -->
</div> <!-- Fecha .p-3 -->
        </div> <!-- Fecha .col-md-9 -->
    </div> <!-- Fecha .row -->
</div> <!-- Fecha .container-fluid -->

<!-- 🔥 CONTADOR DE VISITAS NO ADMIN -->
<?php if (defined('CLIENTE_ID') && CLIENTE_ID > 0): 
    $visitasModel = new \core\models\Visitas(CLIENTE_ID, CLIENTE_SLUG);
    $stats = $visitasModel->getEstatisticas(CLIENTE_ID);
?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="admin-visitas-counter p-2 text-center" style="background: rgba(0,0,0,0.05); border-radius: 8px; border-top: 1px solid #ddd;">
                <small class="text-muted">
                    <i class="fas fa-eye"></i>
                    <strong>Total:</strong> <?= number_format($stats['total']) ?> &nbsp;|&nbsp;
                    <i class="fas fa-calendar-day"></i>
                    <strong>Hoje:</strong> <?= number_format($stats['hoje']) ?> &nbsp;|&nbsp;
                    <i class="fas fa-calendar-week"></i>
                    <strong>Semana:</strong> <?= number_format($stats['semana']) ?> &nbsp;|&nbsp;
                    <i class="fas fa-calendar-alt"></i>
                    <strong>Mês:</strong> <?= number_format($stats['mes']) ?>
                </small>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

</body>
</html>