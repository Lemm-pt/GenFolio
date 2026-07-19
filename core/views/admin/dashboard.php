<!-- core/views/admin/dashboard.php -->
<div class="container-fluid dashboard-container main-content">
    
    <!-- ============================================ -->
    <!-- CABEÇALHO COM LUXOR E LUZES -->
    <!-- ============================================ -->
    <?php 
    $cristaisModel = new \core\models\Cristais(CLIENTE_ID);
    $cristais = $cristaisModel->getAll();
    $ativos = $cristaisModel->getAtivos();
    $totalAtivos = $cristaisModel->getContagemAtivos();
    $cristalPrincipal = $cristaisModel->getCristalPrincipal();
    $infoPrincipal = $cristalPrincipal ? $cristaisModel->getInfo($cristalPrincipal) : null;
    ?>

    <div class="dashboard-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="luxor-welcome">
                   
                    <div class="luxor-message">
                        <h5 class="mb-1">
                            <i class="fas fa-crown text-gold"></i> 
                            Bem-vindo, Guardião de <span class="text-gold"><?= htmlspecialchars(CLIENTE_SLUG) ?></span>
                        </h5>
                        <p class="mb-0" style="color: #b0b0c0; font-size: 0.9rem;">
                            <?php if ($infoPrincipal): ?>
                                <span class="text-gold" style="font-weight: 600;">
                                    <i class="fas <?= $infoPrincipal['icone'] ?>"></i>
                                    <?= $infoPrincipal['nome'] ?>
                                </span>
                                — <?= $infoPrincipal['lenda'] ?>
                            <?php else: ?>
                                A tua jornada está prestes a começar.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-end">
                <div class="luzes-status">
                    <span class="luzes-contagem">
                        <?= $totalAtivos ?> / 7 Luzes
                    </span>
                    <div class="luzes-indicadores">
                        <?php foreach ($cristais as $key => $cristal): ?>
                            <span class="luz-indicador <?= $cristal['ativo'] ? 'ativa' : 'inativa' ?>" 
                                  title="<?= $cristal['nome'] ?> <?= $cristal['ativo'] ? '✨ Ativa' : '🔒 Inativa' ?>"
                                  style="background: <?= $cristal['ativo'] ? $cristal['cor'] : '#2a2a3a' ?>;">
                                <?= $cristal['emoji'] ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- ALERTA DE STATUS -->
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
    <!-- JORNADA DO GUARDIÃO - PROGRESSO -->
    <!-- ============================================ -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-route text-gold"></i> A Jornada do Guardião
            <span class="badge bg-gold text-dark float-end">
                <?= $totalAtivos ?> de 7 Luzes
            </span>
        </div>
        <div class="card-body">
            <div class="progress-jornada">
                <?php 
                $ordemCristais = ['esmeralda', 'safira', 'rubi', 'topazio', 'perola', 'ametista', 'diamante'];
                $labels = [
                    'esmeralda' => 'Alimentação',
                    'safira' => 'Turismo',
                    'rubi' => 'Criatividade',
                    'topazio' => 'Construção',
                    'perola' => 'Saúde',
                    'ametista' => 'Tecnologia',
                    'diamante' => 'Parcerias'
                ];
                ?>
                <?php foreach ($ordemCristais as $key): ?>
                    <?php 
                    $cristal = $cristais[$key] ?? null;
                    if (!$cristal) continue;
                    $isAtivo = $cristal['ativo'];
                    ?>
                    <div class="passo-jornada <?= $isAtivo ? 'concluido' : 'pendente' ?>">
                        <div class="passo-circulo" style="border-color: <?= $cristal['cor'] ?>;">
                            <?php if ($isAtivo): ?>
                                <i class="fas fa-check"></i>
                            <?php else: ?>
                                <i class="fas <?= $cristal['icone'] ?>"></i>
                            <?php endif; ?>
                        </div>
                        <div class="passo-info">
                            <span class="passo-nome"><?= $cristal['nome'] ?></span>
                            <span class="passo-label"><?= $labels[$key] ?? '' ?></span>
                        </div>
                        <?php if ($key === 'diamante' && !$isAtivo): ?>
                            <div class="passo-dica">
                                <small>🔗 Crie parcerias</small>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($key !== end($ordemCristais)): ?>
                        <div class="passo-conector <?= $isAtivo && ($cristais[next($ordemCristais)]['ativo'] ?? false) ? 'ativo' : '' ?>">
                            <span></span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MENSAGEM DO LUXOR -->
    <!-- ============================================ -->
    <div class="card mb-4" style="background: rgba(198, 164, 63, 0.05); border-color: rgba(198, 164, 63, 0.2);">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3">
              
                <div>
                    <h6 class="text-gold mb-1">🧙‍♂️ Luxor, o Guardião Supremo</h6>
                    <p class="mb-0" style="color: #e0e0e0; font-size: 1rem;">
                        <?php if ($totalAtivos === 0): ?>
                            "Bem-vindo, Guardião. A tua jornada começa agora. 
                            Define a identidade do teu reino para despertar a primeira Luz."
                        <?php elseif ($totalAtivos < 7): ?>
                            "Vais bem, Guardião. Já despertaste <strong><?= $totalAtivos ?></strong> Luzes. 
                            Continua a construir o teu reino para desbloquear as restantes."
                        <?php elseif ($totalAtivos === 7): ?>
                            "🌌 <strong>Incrível!</strong> As sete Luzes estão acesas! 
                            O Cristal Primordial revelou-se. O conhecimento dos antigos é teu."
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MÓDULOS DO BACKOFFICE - TEXTOS MAIORES -->
    <!-- ============================================ -->
    <div class="row g-3 modules-grid">
        
        <!-- Essência do Reino -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($config->get('logo_parte1')) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(231, 76, 60, 0.15); color: #e74c3c;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="module-count"><?= !empty($config->get('logo_parte1')) ? '✓' : '?' ?></h3>
                    <p class="module-label">A Essência do Reino</p>
                    <p class="module-desc">Identidade, nome, slogan</p>
                    <a href="?a=admin_configuracoes" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Ofícios do Guardião -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($servicos) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(46, 204, 113, 0.15); color: #2ecc71;">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <h3 class="module-count"><?= count($servicos ?? []) ?></h3>
                    <p class="module-label">Os Ofícios do Guardião</p>
                    <p class="module-desc">Serviços</p>
                    <a href="?a=admin_servicos" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Vitrina dos Artefactos -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($produtos) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(241, 196, 15, 0.15); color: #f1c40f;">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3 class="module-count"><?= count($produtos ?? []) ?></h3>
                    <p class="module-label">A Vitrina dos Artefactos</p>
                    <p class="module-desc">Produtos</p>
                    <a href="?a=admin_produtos" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Espelho das Profundezas -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($galeria) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(52, 152, 219, 0.15); color: #3498db;">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="module-count"><?= count($galeria ?? []) ?></h3>
                    <p class="module-label">O Espelho das Profundezas</p>
                    <p class="module-desc">Galeria</p>
                    <a href="?a=admin_galeria" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Portal do Luar -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($config->get('endereco')) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(241, 196, 15, 0.15); color: #f1c40f;">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    <h3 class="module-count"><?= !empty($config->get('endereco')) ? '✓' : '?' ?></h3>
                    <p class="module-label">O Portal do Luar</p>
                    <p class="module-desc">Mapa e localização</p>
                    <a href="?a=admin_configuracoes" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Portais da Harmonia -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($config->get('email_contacto')) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(236, 240, 241, 0.15); color: #ecf0f1;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="module-count"><?= !empty($config->get('email_contacto')) ? '✓' : '?' ?></h3>
                    <p class="module-label">Os Portais da Harmonia</p>
                    <p class="module-desc">Contactos</p>
                    <a href="?a=admin_configuracoes" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Biblioteca das Crónicas -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($publicacoes) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(155, 89, 182, 0.15); color: #9b59b6;">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="module-count"><?= count($publicacoes ?? []) ?></h3>
                    <p class="module-label">A Biblioteca das Crónicas</p>
                    <p class="module-desc">Blog</p>
                    <a href="?a=admin_publicacoes" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Rede Estelar -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(198, 164, 63, 0.15); color: #C6A43F;">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="module-count">0</h3>
                    <p class="module-label">A Rede Estelar</p>
                    <p class="module-desc">Parcerias (em breve)</p>
                    <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                        <i class="fas fa-lock"></i> Em breve
                    </button>
                </div>
            </div>
        </div>
    </div>



<style>
/* ============================================
   DASHBOARD - ESTILOS MELHORADOS
   ============================================ */

.dashboard-container {
    padding: 0.5rem;
}

/* Cabeçalho */
.luxor-welcome {
    display: flex;
    align-items: center;
    gap: 15px;
}

.luxor-avatar {
    width: 50px;
    height: 50px;
    background: rgba(198, 164, 63, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(198, 164, 63, 0.3);
    font-size: 1.5rem;
    color: #C6A43F;
    flex-shrink: 0;
}

.luxor-message h5 {
    color: #e0e0e0;
    font-size: 1.1rem;
}

.luxor-message h5 strong {
    color: #ffffff;
}

.luzes-status {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.luzes-contagem {
    font-weight: 600;
    color: #C6A43F;
    font-size: 1rem;
}

.luzes-indicadores {
    display: flex;
    gap: 4px;
}

.luz-indicador {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.1);
}

.luz-indicador.ativa {
    box-shadow: 0 0 20px rgba(198, 164, 63, 0.2);
    transform: scale(1.05);
}

.luz-indicador.inativa {
    opacity: 0.25;
    filter: grayscale(1);
}

/* Jornada */
.progress-jornada {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 5px;
}

.passo-jornada {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 14px;
    border-radius: 10px;
    background: rgba(255,255,255,0.03);
    flex: 1;
    min-width: 80px;
    transition: all 0.3s ease;
}

.passo-jornada.concluido {
    background: rgba(46, 204, 113, 0.08);
}

.passo-jornada.pendente {
    opacity: 0.6;
}

.passo-circulo {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid #555;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    color: #e0e0e0;
    flex-shrink: 0;
}

.passo-jornada.concluido .passo-circulo {
    background: rgba(46, 204, 113, 0.15);
    border-color: #2ecc71;
    color: #2ecc71;
}

.passo-info {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.passo-nome {
    font-size: 0.75rem;
    font-weight: 600;
    color: #e0e0e0;
}

.passo-label {
    font-size: 0.6rem;
    color: #888;
}

.passo-dica {
    font-size: 0.6rem;
    margin-left: auto;
}

.passo-dica small {
    color: #888;
}

.passo-conector {
    flex: 0 0 20px;
    height: 2px;
    background: #333;
}

.passo-conector.ativo {
    background: #2ecc71;
}

/* Luxor Chat */
.luxor-chat-avatar {
    width: 45px;
    height: 45px;
    background: rgba(198, 164, 63, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(198, 164, 63, 0.2);
    flex-shrink: 0;
}

/* Module Cards - TEXTO MAIOR */
.module-card {
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.03);
    height: 100%;
}

.module-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    border-color: rgba(198, 164, 63, 0.2);
}

.module-card .card-body {
    padding: 20px 15px !important;
}

.module-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 10px auto;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.module-count {
    font-size: 1.8rem;
    font-weight: 700;
    color: #e0e0e0;
    margin: 0;
    line-height: 1.2;
}

.module-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #e0e0e0;
    margin: 4px 0 2px 0;
}

.module-desc {
    font-size: 0.7rem;
    color: #888 !important;
    margin-bottom: 10px;
}

.module-completo {
    border-color: rgba(46, 204, 113, 0.2) !important;
}

.module-completo .module-count {
    color: #2ecc71 !important;
}

.module-card .btn-outline-gold {
    font-size: 0.75rem;
    padding: 5px 10px;
    border-radius: 50px;
}

/* Footer */
.dashboard-footer {
    background: rgba(255,255,255,0.02);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.05);
}

/* Responsivo */
@media (max-width: 768px) {
    .luxor-welcome {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .luzes-status {
        justify-content: center;
        margin-top: 10px;
    }
    
    .progress-jornada {
        flex-direction: column;
        align-items: stretch;
    }
    
    .passo-jornada {
        min-width: auto;
        padding: 6px 12px;
    }
    
    .passo-conector {
        display: none;
    }
    
    .passo-dica {
        margin-left: auto;
    }
    
    .module-card .card-body {
        padding: 14px 10px !important;
    }
    
    .module-icon {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
    
    .module-count {
        font-size: 1.5rem;
    }
    
    .module-label {
        font-size: 0.75rem;
    }
    
    .module-desc {
        font-size: 0.6rem;
    }
}

@media (max-width: 576px) {
    .luz-indicador {
        width: 24px;
        height: 24px;
        font-size: 0.65rem;
    }
    
    .luzes-contagem {
        font-size: 0.8rem;
    }
    
    .passo-circulo {
        width: 28px;
        height: 28px;
        font-size: 0.6rem;
    }
    
    .passo-nome {
        font-size: 0.65rem;
    }
    
    .passo-label {
        font-size: 0.5rem;
    }
    
    .module-card .btn-outline-gold {
        font-size: 0.65rem;
        padding: 4px 8px;
    }
}
</style>