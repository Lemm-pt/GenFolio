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

                <!-- ============================================ -->
                <!-- INFO DO CLIENTE (Logo, Contactos, Visitas) -->
                <!-- ============================================ -->
                
                <!-- Logo e Copyright -->
                <small class="text-white-50">
                    <?= $config->get('logo_parte1', 'Seven') ?><?= $config->get('logo_parte2', 'Lux') ?> &copy; <?= date('Y') ?>
                </small>
                <br>
                
                <!-- Contactos -->
                <small class="text-white-50">
                    <?php if (!empty($config->get('email_contacto', ''))): ?>
                        <a href="mailto:<?= $config->get('email_contacto', '') ?>" class="text-gold">
                            <?= $config->get('email_contacto', '') ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($config->get('email_contacto', '')) && !empty($config->get('telefone', ''))): ?>
                        <span class="text-muted">|</span>
                    <?php endif; ?>
                    
                    <?php if (!empty($config->get('telefone', ''))): ?>
                        <?= $config->get('telefone', '') ?>
                    <?php endif; ?>
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

                <!-- ============================================ -->
                <!-- DIVISOR -->
                <!-- ============================================ -->
                <div class="footer-divider my-3"></div>

                <!-- ============================================ -->
                <!-- POLÍTICA DE PRIVACIDADE + DEVELOPED BY + BUGS -->
                <!-- ============================================ -->
                <div class="footer-legal">
                    <small class="text-white-50">
                        <a href="<?= BASE_URL . CLIENTE_SLUG ?>/politica_privacidade" class="text-gold legal-link">
                            <i class="fas fa-shield-alt"></i> Política de Privacidade
                        </a>
                    </small>
                    
                    <span class="text-muted separator">|</span>
                    
                    <!-- 🔥 NOVO: Comunicar Erros e Sugestões (apenas para beta) -->
                    <small class="text-white-50">
                        <a href="<?= BASE_URL ?>vitrine-demo/contacto" class="text-gold legal-link" target="_blank">
                            <i class="fas fa-bug"></i> Comunicar Erro ou Sugestão
                        </a>
                    </small>
                    
                    <span class="text-muted separator">|</span>
                    
                    <small class="text-white-50 developed-by">
                        <i class="fas fa-code"></i> 
                        Desenvolvido por 
                        <a href="https://sevenlux.pt" target="_blank" rel="noopener noreferrer" class="text-gold developed-link">
                            <strong>SevenLux.pt</strong>
                        </a>
                    </small>
                </div>

            </div>
        </div>
    </div>
</footer>

<style>
/* ============================================
   FOOTER ESTILOS
   ============================================ */

/* Divisor */
.footer-divider {
    width: 60px;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(198, 164, 63, 0.3), transparent);
    margin: 15px auto;
}

/* Área legal */
.footer-legal {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.footer-legal .separator {
    color: rgba(255, 255, 255, 0.15);
    font-size: 0.8rem;
}

/* Links legais */
.legal-link {
    font-size: 0.75rem;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
    padding: 4px 8px;
    border-radius: 4px;
    text-decoration: none !important;
}

.legal-link:hover {
    background: rgba(198, 164, 63, 0.1);
    text-decoration: none !important;
}

/* Developed by */
.developed-by {
    font-size: 0.75rem;
    letter-spacing: 0.2px;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.developed-by:hover {
    opacity: 1;
}

.developed-link {
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 2px 6px;
    border-radius: 4px;
}

.developed-link:hover {
    background: rgba(198, 164, 63, 0.15);
    text-decoration: none !important;
    transform: translateY(-1px);
}

/* Visitas counter */
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

@keyframes countPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.visitas-counter .visit-count {
    animation: countPulse 3s ease-in-out infinite;
    display: inline-block;
}

/* ============================================
   RESPONSIVO
   ============================================ */
@media (max-width: 576px) {
    .visitas-counter small {
        font-size: 0.65rem;
    }
    
    .visitas-counter .text-muted {
        margin: 0 2px !important;
    }
    
    .footer-legal {
        flex-direction: column;
        gap: 4px;
    }
    
    .footer-legal .separator {
        display: none;
    }
    
    .legal-link,
    .developed-by {
        font-size: 0.7rem;
    }
    
    .footer-divider {
        width: 40px;
    }
}

@media (max-width: 768px) {
    .footer-legal {
        flex-wrap: wrap;
    }
}
</style>

<!-- Script para animar o contador -->
<script>
document.addEventListener('DOMContentLoaded', function() {
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