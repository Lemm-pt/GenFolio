<?php
/**
 * Secção de Horário de Atendimento
 * Exibe o horário completo da semana de forma visual
 */
if (!isset($config)) {
    $config = new \core\models\Configuracao(CLIENTE_ID);
}

$horarioModel = new \core\models\Horario(CLIENTE_ID);

// Se o horário não estiver ativo, não mostra nada
if (!$horarioModel->isAtivo()) {
    return;
}

$horarioData = $horarioModel->getHorarioParaHome();
$hoje = $horarioData['hoje'];
$dias = $horarioData['dias'];
?>

<section id="horario" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">
            <i class="fas fa-clock text-gold"></i> 
            Horário de Atendimento
        </h2>
        
        <!-- Status Atual - Destaque -->
        <div class="horario-status-card mb-4">
            <div class="row align-items-center justify-content-center">
                <div class="col-auto">
                    <div class="status-badge <?= $hoje['status_class'] ?>">
                        <span class="status-icon"><?= $hoje['status_icon'] ?></span>
                        <span class="status-text"><?= $hoje['status_text'] ?></span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="status-info">
                        <span class="dia-atual"><strong><?= $hoje['dia_label'] ?></strong></span>
                        <span class="horario-atual"><?= $hoje['horario'] ?></span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="status-proximo">
                        <?= $hoje['proximo_texto'] ?? '' ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabela de Horários da Semana -->
        <div class="horario-tabela-wrapper">
            <div class="row g-2">
                <?php 
                $hojeKey = $hoje['dia'];
                $ordemDias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
                foreach ($ordemDias as $dia):
                    $info = $dias[$dia] ?? null;
                    if (!$info) continue;
                    $isHoje = ($dia === $hojeKey);
                    $isFechado = ($info['status'] === 'fechado');
                ?>
                <div class="col-md-3 col-sm-6 col-6">
                    <div class="dia-card <?= $isHoje ? 'dia-hoje' : '' ?> <?= $isFechado ? 'dia-fechado' : 'dia-aberto' ?>">
                        <div class="dia-header">
                            <span class="dia-nome"><?= substr($info['label'], 0, 3) ?></span>
                            <?php if ($isHoje): ?>
                                <span class="dia-hoje-badge">Hoje</span>
                            <?php endif; ?>
                        </div>
                        <div class="dia-body">
                            <?php if ($isFechado): ?>
                                <span class="dia-status fechado-text">
                                    <i class="fas fa-times-circle"></i> Fechado
                                </span>
                            <?php else: ?>
                                <span class="dia-horario-text">
                                    <i class="fas fa-clock"></i>
                                    <?= $info['abertura'] ?> - <?= $info['fechamento'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if ($isHoje && !$isFechado && $hoje['status'] === 'aberto'): ?>
                            <div class="dia-footer">
                                <span class="aberto-agora">🟢 Aberto agora</span>
                            </div>
                        <?php elseif ($isHoje && !$isFechado && $hoje['status'] === 'fechado'): ?>
                            <div class="dia-footer">
                                <span class="fechado-agora">🔴 Fechado agora</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Legenda -->
        <div class="horario-legenda text-center mt-4">
            <span class="legenda-item">
                <span class="legenda-cor aberto"></span> Aberto
            </span>
            <span class="legenda-item">
                <span class="legenda-cor fechado"></span> Fechado
            </span>
            <span class="legenda-item">
                <span class="legenda-cor hoje"></span> Hoje
            </span>
        </div>
    </div>
</section>

<style>
/* ============================================
   SECÇÃO HORÁRIO
   ============================================ */
#horario {
    background: linear-gradient(135deg, #0f0f1f 0%, #1a1a2e 100%);
    border-top: 1px solid rgba(198, 164, 63, 0.15);
    border-bottom: 1px solid rgba(198, 164, 63, 0.15);
}

/* Status Card */
.horario-status-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 20px 30px;
    border: 1px solid rgba(198, 164, 63, 0.15);
    text-align: center;
    max-width: 700px;
    margin: 0 auto 30px auto;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
}

.status-badge.aberto {
    background: rgba(76, 175, 80, 0.15);
    color: #4CAF50;
    border: 1px solid rgba(76, 175, 80, 0.3);
}

.status-badge.fechado {
    background: rgba(244, 67, 54, 0.15);
    color: #f44336;
    border: 1px solid rgba(244, 67, 54, 0.3);
}

.status-icon {
    font-size: 1.2rem;
}

.status-info {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #e0e0e0;
}

.status-info .dia-atual {
    font-size: 1rem;
}

.status-info .horario-atual {
    color: #C6A43F;
    font-weight: 500;
}

.status-proximo {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

.status-proximo strong {
    color: #C6A43F;
}

/* Dias Cards */
.dia-card {
    background: rgba(255, 255, 255, 0.04);
    border-radius: 12px;
    padding: 15px 10px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.06);
    transition: all 0.3s ease;
    height: 100%;
    min-height: 110px;
    display: flex;
    flex-direction: column;
}

.dia-card:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, 0.08);
}

.dia-card.dia-hoje {
    border-color: #C6A43F;
    background: rgba(198, 164, 63, 0.08);
    box-shadow: 0 0 20px rgba(198, 164, 63, 0.05);
}

.dia-card.dia-aberto {
    border-left: 3px solid #4CAF50;
}

.dia-card.dia-fechado {
    border-left: 3px solid #f44336;
    opacity: 0.6;
}

.dia-card.dia-fechado:hover {
    opacity: 0.8;
}

.dia-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.dia-nome {
    font-weight: 600;
    color: #e0e0e0;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dia-hoje-badge {
    background: #C6A43F;
    color: #0a0a1a;
    font-size: 0.55rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dia-body {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 40px;
}

.dia-horario-text {
    color: #C6A43F;
    font-size: 0.85rem;
    font-weight: 500;
}

.dia-horario-text i {
    margin-right: 5px;
    font-size: 0.7rem;
}

.fechado-text {
    color: #888;
    font-size: 0.8rem;
}

.fechado-text i {
    margin-right: 4px;
}

.dia-footer {
    margin-top: 8px;
    font-size: 0.7rem;
}

.aberto-agora {
    color: #4CAF50;
    font-weight: 500;
}

.fechado-agora {
    color: #f44336;
    font-weight: 500;
}

/* Legenda */
.horario-legenda {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.legenda-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.8rem;
}

.legenda-cor {
    width: 14px;
    height: 14px;
    border-radius: 4px;
    display: inline-block;
}

.legenda-cor.aberto {
    background: #4CAF50;
}

.legenda-cor.fechado {
    background: #f44336;
}

.legenda-cor.hoje {
    background: #C6A43F;
    border: 1px solid #C6A43F;
}

/* Responsivo */
@media (max-width: 768px) {
    .horario-status-card {
        padding: 15px;
    }
    
    .status-badge {
        font-size: 0.9rem;
        padding: 6px 14px;
    }
    
    .status-info {
        flex-direction: column;
        gap: 2px;
    }
    
    .status-info .horario-atual {
        font-size: 0.9rem;
    }
    
    .dia-card {
        min-height: 90px;
        padding: 10px 6px;
    }
    
    .dia-nome {
        font-size: 0.75rem;
    }
    
    .dia-horario-text {
        font-size: 0.7rem;
    }
    
    .fechado-text {
        font-size: 0.65rem;
    }
    
    .dia-hoje-badge {
        font-size: 0.45rem;
        padding: 1px 6px;
    }
    
    .dia-footer {
        font-size: 0.55rem;
    }
    
    .horario-legenda {
        gap: 12px;
    }
    
    .legenda-item {
        font-size: 0.7rem;
    }
}

@media (max-width: 576px) {
    .dia-card {
        min-height: 80px;
    }
    
    .status-proximo {
        font-size: 0.75rem;
    }
}
</style>

<!-- JavaScript para destacar a secção ao clicar no link -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Se a URL tiver #horario, scroll suave
    if (window.location.hash === '#horario') {
        setTimeout(function() {
            const section = document.getElementById('horario');
            if (section) {
                const navbarHeight = document.querySelector('.navbar-modern')?.offsetHeight || 80;
                const targetPos = section.getBoundingClientRect().top + window.pageYOffset - navbarHeight - 20;
                window.scrollTo({ top: targetPos, behavior: 'smooth' });
            }
        }, 500);
    }
});
</script>