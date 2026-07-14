<!-- core/views/layouts/footer.php -->
<footer class="rodape py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">

             <!-- 🔥 REDES SOCIAIS -->
                <?php 
                $socialModel = new \core\models\Social(CLIENTE_ID);
                echo $socialModel->render('md', 'justify-content-center mb-3');
                ?>
                
                <!-- Logo e Copyright -->
                <small class="text-white-50">
                    <?= $config->get('logo_parte1', 'Seven') ?><?= $config->get('logo_parte2', 'Lux') ?> &copy; <?= date('Y') ?>
                </small>
                <br>
                
                <!-- Contactos -->
                <small class="text-white-50">
                    <a href="mailto:<?= $config->get('email_contacto', '') ?>" class="text-gold">
                        <?= $config->get('email_contacto', '') ?>
                    </a> | 
                    <?= $config->get('telefone', '') ?>
                </small>
                
                <!-- 🔥 CONTADOR DE VISITAS -->
                <?php if (defined('CLIENTE_ID') && CLIENTE_ID > 0): 
                    $visitasModel = new \core\models\Visitas(CLIENTE_ID, CLIENTE_SLUG);
                    $stats = $visitasModel->getEstatisticas(CLIENTE_ID);
                ?>
                <br>
                <div class="visitas-counter mt-2">
                    <small class="text-white-50">
                        <i class="fas fa-eye text-gold"></i>
                        <span class="visit-count" title="Total de visitas"><?= number_format($stats['total']) ?></span>
                        <span class="text-muted mx-1">|</span>
                        <i class="fas fa-calendar-day text-gold"></i>
                        <span class="visit-today" title="Visitas hoje"><?= number_format($stats['hoje']) ?></span>
                        <span class="text-muted mx-1">|</span>
                        <i class="fas fa-calendar-week text-gold"></i>
                        <span class="visit-week" title="Visitas esta semana"><?= number_format($stats['semana']) ?></span>
                        <span class="text-muted mx-1">|</span>
                        <i class="fas fa-calendar-alt text-gold"></i>
                        <span class="visit-month" title="Visitas este mês"><?= number_format($stats['mes']) ?></span>
                    </small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<style>
.visitas-counter {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.visitas-counter:hover {
    opacity: 1;
}

.visitas-counter .text-gold {
    color: #C6A43F !important;
}

.visitas-counter .visit-count,
.visitas-counter .visit-today,
.visitas-counter .visit-week,
.visitas-counter .visit-month {
    font-weight: 500;
    font-variant-numeric: tabular-nums;
}

.visitas-counter .text-muted {
    color: rgba(255, 255, 255, 0.3) !important;
}

/* Animação suave para o contador */
@keyframes countPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.visitas-counter .visit-count {
    animation: countPulse 3s ease-in-out infinite;
    display: inline-block;
}

/* Responsivo */
@media (max-width: 576px) {
    .visitas-counter small {
        font-size: 0.7rem;
    }
    .visitas-counter .text-muted {
        margin: 0 2px !important;
    }
}
</style>

<!-- Script para animar o contador -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animação de contagem para o total
    const countEl = document.querySelector('.visit-count');
    if (countEl) {
        const target = parseInt(countEl.textContent.replace(/\D/g, ''));
        if (target > 0) {
            let current = 0;
            const increment = Math.ceil(target / 30);
            const duration = 1000;
            const stepTime = duration / 30;
            
            const counter = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(counter);
                }
                countEl.textContent = current.toLocaleString();
            }, stepTime);
        }
    }
});
</script>