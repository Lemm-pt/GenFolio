<?php
/**
 * Admin Dashboard
 * 
 * Exibe o painel principal com a narrativa da SevenLux,
 * progresso das luzes, módulos de gestão e elementos interativos.
 * 
 * @package SevenLux
 */
?>
<div class="container-fluid dashboard-container main-content">
    
    <!-- ============================================ -->
    <!-- 🔥 1. LUXOR - MENSAGEM COM ÍCONE (TOPO) -->
    <!-- ============================================ -->
    <?php 
    $cristaisModel = new \core\models\Cristais(CLIENTE_ID);
    $cristais = $cristaisModel->getAll();
    $ativos = $cristaisModel->getAtivos();
    $totalAtivos = $cristaisModel->getContagemAtivos();
    $cristalPrincipal = $cristaisModel->getCristalPrincipal();
    $infoPrincipal = $cristalPrincipal ? $cristaisModel->getInfo($cristalPrincipal) : null;
    ?>

    <div class="luxor-welcome-card mb-4">
        <div class="d-flex align-items-start gap-3">
            <!-- Ícone do Luxor (novo) -->
            <div class="luxor-avatar" onclick="abrirModalGuardião()" style="cursor: pointer;" title="Ver Guardião da Luz">
                <img src="<?= BASE_URL ?>assets/images/icone.png" alt="Luxor"  class="luxor-avatar-img">
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="luxor-name">Luxor</span>
                    <span class="luxor-title">O Último Guardião</span>
                    <span class="luxor-ver-guardiao" onclick="abrirModalGuardião()">
                        <i class="fas fa-eye"></i> Ver Guardião
                    </span>
                </div>
                <p class="luxor-message">
                    "Bem-vindo, Guardião. A antiga luz escolheu o teu caminho.<br>
                    Cada informação que adicionares fortalecerá o teu reino.<br>
                    Cada cristal despertado aproximar-te-á do Cristal Primordial."
                </p>
                <button class="btn-luxor-historia" onclick="toggleLuxorHistoria(this)">
                    <i class="fas fa-chevron-down"></i> Ouvir a mensagem do Luxor
                </button>
                
                <!-- Accordion da história -->
                <div id="luxorHistoria" class="luxor-historia-conteudo" style="display: none;">
                    <p>
                        <em>Há milhares de anos existiu uma civilização que dominava uma tecnologia capaz de unir povos através do conhecimento e da cooperação.</em><br><br>
                        <em>Essa civilização perdeu-se quando a ganância substituiu a colaboração. As Sete Luzes foram separadas.</em><br><br>
                        <em>Cada Guardião moderno recebeu uma delas. A tua missão não é conquistar. É construir.</em><br><br>
                        <em>Quando todas as Luzes despertarem, o Cristal Primordial revelar-te-á um novo conhecimento.</em><br><br>
                        <em>Eu acompanharei toda a tua jornada.</em><br><br>
                        <span style="font-weight: bold; color: #C6A43F;">— Luxor</span>
                    </p>
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
    <!-- 🔥 2. MÓDULOS DO BACKOFFICE (NOMES FORMAIS EM DESTAQUE) -->
    <!-- ============================================ -->
    <div class="row g-3 modules-grid">
        
        <!-- Configurações -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($config->get('logo_parte1')) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(231, 76, 60, 0.15); color: #e74c3c;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="module-count"><?= !empty($config->get('logo_parte1')) ? '✓' : '?' ?></h3>
                    <p class="module-label">Configurações</p>
                    <p class="module-desc">A Essência do Reino</p>
                    <a href="?a=admin_configuracoes" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Serviços -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($servicos) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(46, 204, 113, 0.15); color: #2ecc71;">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <h3 class="module-count"><?= count($servicos ?? []) ?></h3>
                    <p class="module-label">Serviços</p>
                    <p class="module-desc">Os Ofícios do Guardião</p>
                    <a href="?a=admin_servicos" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Produtos -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($produtos) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(241, 196, 15, 0.15); color: #f1c40f;">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3 class="module-count"><?= count($produtos ?? []) ?></h3>
                    <p class="module-label">Produtos</p>
                    <p class="module-desc">A Vitrina dos Artefactos</p>
                    <a href="?a=admin_produtos" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Galeria -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($galeria) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(52, 152, 219, 0.15); color: #3498db;">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="module-count"><?= count($galeria ?? []) ?></h3>
                    <p class="module-label">Galeria</p>
                    <p class="module-desc">O Espelho das Profundezas</p>
                    <a href="?a=admin_galeria" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Publicações -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($publicacoes) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(155, 89, 182, 0.15); color: #9b59b6;">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="module-count"><?= count($publicacoes ?? []) ?></h3>
                    <p class="module-label">Publicações</p>
                    <p class="module-desc">A Biblioteca das Crónicas</p>
                    <a href="?a=admin_publicacoes" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Redes Sociais -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card <?= !empty($social) ? 'module-completo' : '' ?>">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(198, 164, 63, 0.15); color: #C6A43F;">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h3 class="module-count"><?= !empty($social) ? '✓' : '?' ?></h3>
                    <p class="module-label">Redes Sociais</p>
                    <p class="module-desc">Os Ecos do Mundo</p>
                    <a href="?a=admin_social" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestão de Conta -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(108, 117, 125, 0.15); color: #6c757d;">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3 class="module-count">⚙️</h3>
                    <p class="module-label">Gestão de Conta</p>
                    <p class="module-desc">O Reino do Guardião</p>
                    <a href="?a=admin_gestao_conta" class="btn btn-sm btn-outline-gold w-100">
                        <i class="fas fa-edit"></i> Gerir
                    </a>
                </div>
            </div>
        </div>

        <!-- Sistema de Marcações -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card module-card">
                <div class="card-body text-center p-3">
                    <div class="module-icon" style="background: rgba(198, 164, 63, 0.15); color: #C6A43F;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="module-count">📅</h3>
                    <p class="module-label">Sistema de Marcações</p>
                    <p class="module-desc">A Rede Estelar (em breve)</p>
                    <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                        <i class="fas fa-clock"></i> Em breve
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- 🔥 3. LEGADO DA SEVENLUX -->
    <!-- ============================================ -->
    <div class="legado-moderno mb-4 mt-4">
        <button class="legado-toggle" onclick="toggleLegado(this)">
            <span class="legado-icon">🏛️</span>
            <span class="legado-titulo">O Legado da SevenLux</span>
            <span class="legado-setinha"><i class="fas fa-chevron-down"></i></span>
        </button>
        <div class="legado-conteudo" style="display: none;">
            <div class="legado-texto">
                <p><span style="font-weight: bold; color: #C6A43F;">Bem-vindo, Guardião.</span></p>
                <p>Se estás a ler estas palavras é porque foste escolhido para continuar uma antiga missão.</p>
                
                <p>Há muito tempo existiu uma civilização conhecida apenas como <span style="font-weight: bold; color: #C6A43F;">SevenLux</span>.</p>
                <p>Não era poderosa pelas suas muralhas, nem pelas suas riquezas.<br>
                Era poderosa porque as pessoas acreditavam umas nas outras.</p>
                
                <ul class="legado-lista">
                    <li>Comerciavam com justiça.</li>
                    <li>Partilhavam conhecimento.</li>
                    <li>Respeitavam o trabalho de todos.</li>
                </ul>
                
                <p>Cada profissão tinha o seu valor.<br>
                Cada pessoa era uma parte essencial de um todo.</p>
                
                <div class="legado-destaque">
                    <span class="legado-destaque-icon">✦</span>
                    <p><span style="font-weight: bold; color: #C6A43F;">As Sete Luzes</span></p>
                    <p>Para preservar esse equilíbrio foram criadas Sete Luzes.<br>
                    Cada uma guardava um conhecimento diferente.<br>
                    Nenhuma era superior às restantes.<br>
                    Apenas quando brilhavam juntas era possível revelar o verdadeiro poder.</p>
                </div>
                
                <p><span style="font-weight: bold; color: #C6A43F;">A queda</span></p>
                <p>Mas, com o passar do tempo, nasceu a ganância.<br>
                Alguns passaram a desejar toda a riqueza apenas para si.<br>
                A cooperação deu lugar à inveja.<br>
                As Sete Luzes foram separadas.<br>
                E o seu conhecimento perdeu-se no tempo.</p>
                
                <div class="legado-destaque" style="border-left-color: #C6A43F;">
                    <p><span style="font-weight: bold; color: #C6A43F;">O teu papel</span></p>
                    <p>Hoje, séculos depois, esse legado procura novos Guardiões.<br>
                    Não para conquistar o mundo.<br>
                    Mas para provar que ainda é possível construir negócios onde todos crescem juntos.</p>
                    <p>Cada página que criares...<br>
                    Cada serviço que apresentares...<br>
                    Cada fotografia que partilhares...<br>
                    Será uma nova Luz acesa.</p>
                </div>
                
                <div class="legado-destaque" style="border-left-color: #C6A43F; background: rgba(198,164,63,0.05);">
                    <p><span style="font-weight: bold; color: #C6A43F;">A verdadeira recompensa</span></p>
                    <p>Muitos procuram um tesouro escondido.<br>
                    Poucos compreendem que o verdadeiro tesouro nunca foi ouro.<br>
                    Sempre foi o <span style="font-weight: bold; color: #C6A43F;">conhecimento</span>.</p>
                    <p>Quando as Sete Luzes voltarem a brilhar em harmonia...<br>
                    um novo poder será revelado.</p>
                </div>
                
                <div class="legado-filosofia">
                    <p><span style="font-weight: bold; color: #C6A43F;">A filosofia SevenLux</span></p>
                    <p>Um negócio cresce mais quando ajuda outros negócios a crescer.<br>
                    O conhecimento vale mais quando é partilhado.<br>
                    Uma parceria vale mais do que uma rivalidade.<br>
                    A inovação nasce da curiosidade.</p>
                </div>
                
                <div class="legado-final">
                    <p><em>"A força pode construir muralhas.<br>
                    O dinheiro pode comprar ferramentas.<br>
                    A tecnologia pode acelerar o progresso.<br>
                    <span style="font-weight: bold; color: #C6A43F;">Mas apenas a confiança consegue unir pessoas.</span>"</em></p>
                    <p style="margin-top: 12px; color: #C6A43F; font-weight: 600;">
                        — Luxor, o Último Guardião
                    </p>
                </div>
                
                <div class="legado-lema">
                    <p>✨ <span style="font-weight: bold; color: #C6A43F;">"Quando as Sete Luzes brilham em harmonia, o conhecimento ilumina todos."</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- 🔥 4. JORNADA DAS LUZES -->
    <!-- ============================================ -->
    <div class="jornada-container mb-4">
        <div class="jornada-header">
            <span class="jornada-titulo">A Jornada do Guardião</span>
            <span class="jornada-contagem"><?= $totalAtivos ?> de 7 Luzes</span>
        </div>
        <div class="jornada-luzes">
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
                <div class="luz-item <?= $isAtivo ? 'ativa' : 'inativa' ?>" style="--cor: <?= $cristal['cor'] ?>;">
                    <div class="luz-circulo" style="border-color: <?= $cristal['cor'] ?>; background: <?= $isAtivo ? $cristal['cor'] : 'transparent' ?>;">
                        <?php if ($isAtivo): ?>
                            <i class="fas fa-check" style="color: #fff; font-size: 0.6rem;"></i>
                        <?php else: ?>
                            <span style="font-size: 0.6rem; color: #555;">?</span>
                        <?php endif; ?>
                    </div>
                    <div class="luz-info">
                        <span class="luz-nome"><?= $cristal['nome'] ?></span>
                        <span class="luz-label"><?= $labels[$key] ?? '' ?></span>
                    </div>
                    <?php if ($key === 'diamante' && !$isAtivo): ?>
                        <span class="luz-dica">🔗 Crie parcerias</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- 🔥 5. AS SETE LUZES - EXPLICAÇÃO -->
    <!-- ============================================ -->
    <div class="sete-luzes-moderno mb-4">
        <button class="sete-luzes-toggle" onclick="toggleSeteLuzes(this)">
            <span class="sete-luzes-icon">💎</span>
            <span class="sete-luzes-titulo">As Sete Luzes</span>
            <span class="sete-luzes-sub">Conhece o poder de cada cristal</span>
            <span class="sete-luzes-setinha"><i class="fas fa-chevron-down"></i></span>
        </button>
        <div class="sete-luzes-conteudo" style="display: none;">
            <?php foreach ($cristais as $key => $cristal): ?>
                <div class="luz-explicacao" style="border-left-color: <?= $cristal['cor'] ?>;">
                    <div class="luz-explicacao-header">
                        <span class="luz-explicacao-icone" style="color: <?= $cristal['cor'] ?>;">
                            <?= $cristal['emoji'] ?>
                        </span>
                        <span class="luz-explicacao-nome"><?= $cristal['nome'] ?></span>
                        <span class="luz-explicacao-status <?= $cristal['ativo'] ? 'ativo' : 'inativo' ?>">
                            <?= $cristal['ativo'] ? '✨ Ativa' : '🔒 Inativa' ?>
                        </span>
                    </div>
                    <div class="luz-explicacao-body">
                        <p><span style="font-weight: bold; color: #C6A43F;">Elemento:</span> <?= $cristal['elemento'] ?></p>
                        <p><span style="font-weight: bold; color: #C6A43F;">Descrição:</span> <?= $cristal['descricao'] ?></p>
                        <p><span style="font-weight: bold; color: #C6A43F;">Propósito:</span> <?= $cristal['lenda'] ?></p>
                        <p><span style="font-weight: bold; color: #C6A43F;">Categorias:</span> <?= implode(', ', $cristal['categorias'] ?? []) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- 🔥 6. ENIGMA DO LUXOR (NO FINAL) -->
    <!-- ============================================ -->
    <div class="enigma-moderno mb-4">
        <button class="enigma-toggle" onclick="toggleEnigma(this)">
            <span class="enigma-icon">✦</span>
            <span class="enigma-titulo">Enigma do Luxor</span>
            <span class="enigma-setinha"><i class="fas fa-chevron-down"></i></span>
        </button>
        <div class="enigma-conteudo" style="display: none;">
            <p class="enigma-texto">
                "As sete Luzes nunca foram criadas para brilhar separadamente.<br>
                Quando finalmente se unirem, o Cristal Primordial despertará novamente.<br><br>
                <span style="font-size: 0.8rem; color: #888; font-style: normal;">Diz-se que o próprio Luxor, outrora um Guardião como tu, guardou este segredo até ao fim dos tempos.</span>"
            </p>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- 🔥 MODAL PARA IMAGEM DO GUARDIÃO -->
<!-- ============================================ -->
<div id="modalGuardiao" class="modal-guardiao" style="display: none;">
    <div class="modal-guardiao-content">
        <span class="modal-guardiao-close" onclick="fecharModalGuardião()">&times;</span>
        <img src="<?= BASE_URL ?>assets/images/Luxor_o_guardiao_da_luz.png" alt="Luxor - O Guardião da Luz" style="width: 100%; max-width: 500px; height: auto; border-radius: 12px;">
        <div class="modal-guardiao-legenda">
            <h3>🧙 Luxor</h3>
            <p>O Último Guardião da SevenLux</p>
            <p style="font-size: 0.8rem; color: #999; font-style: italic;">"O conhecimento apenas cresce quando é partilhado."</p>
        </div>
    </div>
</div>

<script>
// ============================================================
// FUNÇÕES TOGGLE
// ============================================================

function toggleLuxorHistoria(btn) {
    const historia = document.getElementById('luxorHistoria');
    const icon = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
    if (historia.style.display === 'none') {
        historia.style.display = 'block';
        icon.className = 'fas fa-chevron-up';
        btn.innerHTML = '<i class="fas fa-chevron-up"></i> Fechar mensagem';
    } else {
        historia.style.display = 'none';
        icon.className = 'fas fa-chevron-down';
        btn.innerHTML = '<i class="fas fa-chevron-down"></i> Ouvir a mensagem do Luxor';
    }
}

function toggleEnigma(btn) {
    const conteudo = btn.nextElementSibling;
    const setinha = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
    if (conteudo.style.display === 'none') {
        conteudo.style.display = 'block';
        setinha.className = 'fas fa-chevron-up';
    } else {
        conteudo.style.display = 'none';
        setinha.className = 'fas fa-chevron-down';
    }
}

function toggleLegado(btn) {
    const conteudo = btn.nextElementSibling;
    const setinha = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
    if (conteudo.style.display === 'none') {
        conteudo.style.display = 'block';
        setinha.className = 'fas fa-chevron-up';
    } else {
        conteudo.style.display = 'none';
        setinha.className = 'fas fa-chevron-down';
    }
}

function toggleSeteLuzes(btn) {
    const conteudo = btn.nextElementSibling;
    const setinha = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
    if (conteudo.style.display === 'none') {
        conteudo.style.display = 'block';
        setinha.className = 'fas fa-chevron-up';
    } else {
        conteudo.style.display = 'none';
        setinha.className = 'fas fa-chevron-down';
    }
}

// ============================================================
// MODAL DO GUARDIÃO
// ============================================================

function abrirModalGuardião() {
    document.getElementById('modalGuardiao').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModalGuardião() {
    document.getElementById('modalGuardiao').style.display = 'none';
    document.body.style.overflow = '';
}

// Fechar modal ao clicar fora
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalGuardiao');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalGuardião();
            }
        });
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        fecharModalGuardião();
    }
});
</script>

<style>
/* ============================================
   DASHBOARD - ESTILOS COMPLETOS
   ============================================ */

.dashboard-container {
    padding: 0.5rem;
}

/* ============================================
   1. LUXOR WELCOME
   ============================================ */
.luxor-welcome-card {
    background: rgba(255,255,255,0.02);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(198, 164, 63, 0.1);
}

.luxor-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(198, 164, 63, 0.2);
    flex-shrink: 0;
    transition: all 0.3s ease;
    background: rgba(198, 164, 63, 0.05);
}

.luxor-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.luxor-avatar:hover {
    transform: scale(1.05);
    border-color: #C6A43F;
    box-shadow: 0 0 25px rgba(198, 164, 63, 0.15);
}

.luxor-ver-guardiao {
    font-size: 0.6rem;
    color: #666;
    cursor: pointer;
    padding: 2px 10px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.05);
    transition: all 0.3s ease;
}

.luxor-ver-guardiao:hover {
    color: #C6A43F;
    border-color: rgba(198, 164, 63, 0.3);
    background: rgba(198, 164, 63, 0.05);
}

.luxor-name {
    font-weight: 700;
    color: #e0e0e0;
    font-size: 1.1rem;
}

.luxor-title {
    font-size: 0.65rem;
    color: #666;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.luxor-message {
    color: #b0b0c0;
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 6px 0 10px 0;
}

.btn-luxor-historia {
    background: none;
    border: 1px solid rgba(198, 164, 63, 0.2);
    color: #C6A43F;
    padding: 4px 16px;
    border-radius: 50px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-luxor-historia:hover {
    background: rgba(198, 164, 63, 0.1);
    border-color: #C6A43F;
}

.luxor-historia-conteudo {
    margin-top: 12px;
    padding: 16px 20px;
    background: rgba(198, 164, 63, 0.04);
    border-radius: 10px;
    border-left: 3px solid #C6A43F;
}

.luxor-historia-conteudo p {
    font-size: 0.9rem;
    color: #c0c0c0;
    line-height: 1.8;
    margin: 0;
}

/* ============================================
   2. MODULE CARDS
   ============================================ */
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
    padding: 18px 12px !important;
}

.module-icon {
    width: 44px;
    height: 44px;
    margin: 0 auto 8px auto;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.module-count {
    font-size: 1.6rem;
    font-weight: 700;
    color: #e0e0e0;
    margin: 0;
    line-height: 1.2;
}

.module-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #e0e0e0;
    margin: 4px 0 2px 0;
}

.module-desc {
    font-size: 0.65rem;
    color: #777 !important;
    margin-bottom: 10px;
}

.module-completo {
    border-color: rgba(46, 204, 113, 0.2) !important;
}

.module-completo .module-count {
    color: #2ecc71 !important;
}

.module-card .btn-outline-gold {
    font-size: 0.7rem;
    padding: 4px 10px;
    border-radius: 50px;
}

.module-card .btn-outline-secondary {
    font-size: 0.7rem;
    padding: 4px 10px;
    border-radius: 50px;
    color: #666;
    border-color: #333;
}

/* ============================================
   3. LEGADO
   ============================================ */
.legado-moderno {
    background: rgba(255,255,255,0.02);
    border-radius: 10px;
    border: 1px solid rgba(198, 164, 63, 0.08);
    overflow: hidden;
}

.legado-toggle {
    width: 100%;
    background: none;
    border: none;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: background 0.3s ease;
    color: #b0b0c0;
    text-align: left;
}

.legado-toggle:hover {
    background: rgba(198, 164, 63, 0.04);
}

.legado-icon {
    font-size: 1.1rem;
    opacity: 0.7;
}

.legado-titulo {
    font-size: 0.85rem;
    font-weight: 600;
    color: #e0e0e0;
    flex: 1;
}

.legado-setinha {
    font-size: 0.7rem;
    color: #555;
}

.legado-conteudo {
    padding: 0 18px 20px 18px;
    animation: slideDown 0.4s ease-out;
}

.legado-texto {
    font-size: 0.9rem;
    color: #c0c0c0;
    line-height: 1.8;
}

.legado-texto p {
    margin: 0 0 8px 0;
}

.legado-texto span {
    color: #e8e8f0;
}

.legado-lista {
    list-style: none;
    padding: 0;
    margin: 8px 0 12px 0;
}

.legado-lista li {
    padding: 2px 0 2px 24px;
    position: relative;
    color: #b0b0c0;
}

.legado-lista li::before {
    content: "✦";
    position: absolute;
    left: 0;
    color: #C6A43F;
    font-size: 0.7rem;
    opacity: 0.6;
}

.legado-destaque {
    background: rgba(198, 164, 63, 0.04);
    border-left: 3px solid rgba(198, 164, 63, 0.3);
    padding: 12px 16px;
    margin: 12px 0;
    border-radius: 0 6px 6px 0;
}

.legado-destaque p {
    margin: 0 0 4px 0;
}

.legado-destaque-icon {
    color: #C6A43F;
    font-size: 0.8rem;
    opacity: 0.6;
}

.legado-filosofia {
    background: rgba(198, 164, 63, 0.03);
    border: 1px solid rgba(198, 164, 63, 0.06);
    padding: 14px 18px;
    border-radius: 8px;
    margin: 12px 0;
    text-align: center;
}

.legado-filosofia p {
    margin: 0;
    font-size: 0.85rem;
    color: #b0b0c0;
}

.legado-final {
    text-align: center;
    padding: 16px 0 8px 0;
    border-top: 1px solid rgba(255,255,255,0.03);
    margin-top: 12px;
}

.legado-final em {
    color: #b0b0c0;
    font-style: italic;
    line-height: 1.8;
}

.legado-final strong {
    color: #C6A43F;
}

.legado-lema {
    text-align: center;
    padding: 12px 0 4px 0;
    font-size: 0.9rem;
    color: #C6A43F;
}

.legado-lema p {
    margin: 0;
}

/* ============================================
   4. JORNADA DAS LUZES
   ============================================ */
.jornada-container {
    background: rgba(255,255,255,0.02);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.05);
    padding: 16px 20px;
}

.jornada-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.jornada-titulo {
    font-size: 0.85rem;
    font-weight: 600;
    color: #e0e0e0;
}

.jornada-contagem {
    font-size: 0.7rem;
    color: #C6A43F;
    background: rgba(198, 164, 63, 0.1);
    padding: 2px 12px;
    border-radius: 20px;
}

.jornada-luzes {
    display: flex;
    flex-wrap: wrap;
    gap: 12px 20px;
}

.luz-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 0;
    flex: 1 0 auto;
    min-width: 100px;
    opacity: 0.6;
    transition: opacity 0.3s ease;
}

.luz-item.ativa {
    opacity: 1;
}

.luz-item.inativa {
    opacity: 0.35;
}

.luz-circulo {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid #555;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.luz-item.ativa .luz-circulo {
    box-shadow: 0 0 12px var(--cor, #C6A43F);
}

.luz-info {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.luz-nome {
    font-size: 0.75rem;
    font-weight: 600;
    color: #e0e0e0;
}

.luz-label {
    font-size: 0.55rem;
    color: #888;
}

.luz-dica {
    font-size: 0.55rem;
    color: #C6A43F;
    margin-left: auto;
}

/* ============================================
   5. AS SETE LUZES
   ============================================ */
.sete-luzes-moderno {
    background: rgba(255,255,255,0.02);
    border-radius: 10px;
    border: 1px solid rgba(198, 164, 63, 0.08);
    overflow: hidden;
}

.sete-luzes-toggle {
    width: 100%;
    background: none;
    border: none;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: background 0.3s ease;
    color: #b0b0c0;
    text-align: left;
}

.sete-luzes-toggle:hover {
    background: rgba(198, 164, 63, 0.04);
}

.sete-luzes-icon {
    font-size: 1.1rem;
    opacity: 0.7;
}

.sete-luzes-titulo {
    font-size: 0.85rem;
    font-weight: 600;
    color: #e0e0e0;
}

.sete-luzes-sub {
    font-size: 0.6rem;
    color: #666;
    font-weight: 400;
    margin-left: 4px;
}

.sete-luzes-setinha {
    font-size: 0.7rem;
    color: #555;
    margin-left: auto;
}

.sete-luzes-conteudo {
    padding: 0 18px 20px 18px;
    animation: slideDown 0.4s ease-out;
}

.luz-explicacao {
    background: rgba(255,255,255,0.02);
    border-left: 3px solid #555;
    padding: 12px 16px;
    margin-bottom: 10px;
    border-radius: 0 8px 8px 0;
    transition: all 0.3s ease;
}

.luz-explicacao:last-child {
    margin-bottom: 0;
}

.luz-explicacao:hover {
    background: rgba(255,255,255,0.04);
}

.luz-explicacao-header {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.luz-explicacao-icone {
    font-size: 1.2rem;
}

.luz-explicacao-nome {
    font-weight: 600;
    color: #e0e0e0;
    font-size: 0.85rem;
}

.luz-explicacao-status {
    font-size: 0.6rem;
    padding: 1px 10px;
    border-radius: 20px;
    margin-left: auto;
}

.luz-explicacao-status.ativo {
    color: #2ecc71;
    background: rgba(46, 204, 113, 0.1);
}

.luz-explicacao-status.inativo {
    color: #666;
    background: rgba(255,255,255,0.03);
}

.luz-explicacao-body {
    margin-top: 6px;
    padding-left: 2px;
}

.luz-explicacao-body p {
    margin: 2px 0;
    font-size: 0.8rem;
    color: #b0b0c0;
    line-height: 1.5;
}

.luz-explicacao-body span {
    color: #d0d0d0;
}

/* ============================================
   6. ENIGMA MODERNO
   ============================================ */
.enigma-moderno {
    background: rgba(255,255,255,0.02);
    border-radius: 10px;
    border: 1px solid rgba(198, 164, 63, 0.08);
    overflow: hidden;
}

.enigma-toggle {
    width: 100%;
    background: none;
    border: none;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: background 0.3s ease;
    color: #b0b0c0;
    text-align: left;
}

.enigma-toggle:hover {
    background: rgba(198, 164, 63, 0.04);
}

.enigma-icon {
    font-size: 1rem;
    color: #C6A43F;
    opacity: 0.6;
    font-weight: 300;
}

.enigma-titulo {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #888;
    flex: 1;
}

.enigma-setinha {
    font-size: 0.7rem;
    color: #555;
}

.enigma-conteudo {
    padding: 0 18px 16px 18px;
    animation: slideDown 0.3s ease-out;
}

.enigma-texto {
    font-size: 0.85rem;
    color: #b0b0c0;
    font-style: italic;
    margin: 0;
    line-height: 1.6;
    border-left: 2px solid rgba(198, 164, 63, 0.2);
    padding-left: 16px;
}

/* ============================================
   MODAL DO GUARDIÃO
   ============================================ */
.modal-guardiao {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeIn 0.3s ease-out;
}

.modal-guardiao-content {
    background: #1a1a2e;
    border-radius: 16px;
    max-width: 550px;
    width: 100%;
    padding: 20px;
    position: relative;
    border: 1px solid rgba(198, 164, 63, 0.15);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.modal-guardiao-close {
    position: absolute;
    top: 12px;
    right: 18px;
    font-size: 28px;
    color: #888;
    cursor: pointer;
    transition: color 0.3s ease;
    line-height: 1;
}

.modal-guardiao-close:hover {
    color: #fff;
}

.modal-guardiao-content img {
    width: 100%;
    max-width: 500px;
    height: auto;
    border-radius: 12px;
    display: block;
    margin: 0 auto;
}

.modal-guardiao-legenda {
    text-align: center;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,0.05);
}

.modal-guardiao-legenda h3 {
    color: #e0e0e0;
    font-size: 1.2rem;
    margin: 0 0 4px 0;
}

.modal-guardiao-legenda p {
    color: #b0b0c0;
    margin: 2px 0;
    font-size: 0.9rem;
}

/* ============================================
   ANIMAÇÕES
   ============================================ */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* ============================================
   RESPONSIVO
   ============================================ */
@media (max-width: 768px) {
    .luxor-welcome-card .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .luxor-avatar {
        margin: 0 auto;
        width: 50px;
        height: 50px;
    }
    
    .luxor-ver-guardiao {
        display: inline-block;
        margin: 4px auto;
    }
    
    .jornada-luzes {
        flex-direction: column;
        gap: 6px;
    }
    
    .luz-item {
        min-width: auto;
    }
    
    .sete-luzes-toggle {
        flex-wrap: wrap;
        gap: 6px;
    }
    
    .sete-luzes-sub {
        display: none;
    }
    
    .luz-explicacao-header {
        flex-wrap: wrap;
    }
    
    .luz-explicacao-status {
        margin-left: 0;
    }
    
    .module-card .card-body {
        padding: 14px 8px !important;
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
        font-size: 0.8rem;
    }
    
    .module-desc {
        font-size: 0.55rem;
    }
    
    .enigma-toggle,
    .legado-toggle {
        padding: 12px 14px;
    }
    
    .enigma-titulo {
        font-size: 0.6rem;
    }
    
    .legado-titulo {
        font-size: 0.8rem;
    }
    
    .legado-texto {
        font-size: 0.85rem;
    }
    
    .modal-guardiao-content {
        padding: 16px;
        margin: 10px;
    }
}

@media (max-width: 576px) {
    .luxor-message {
        font-size: 0.85rem;
    }
    
    .luxor-historia-conteudo p {
        font-size: 0.8rem;
    }
    
    .jornada-container {
        padding: 12px 14px;
    }
    
    .jornada-titulo {
        font-size: 0.75rem;
    }
    
    .jornada-contagem {
        font-size: 0.65rem;
    }
    
    .luz-item {
        gap: 6px;
    }
    
    .luz-circulo {
        width: 24px;
        height: 24px;
    }
    
    .luz-nome {
        font-size: 0.65rem;
    }
    
    .luz-label {
        font-size: 0.5rem;
    }
    
    .module-card .btn-outline-gold,
    .module-card .btn-outline-secondary {
        font-size: 0.6rem;
        padding: 3px 8px;
    }
    
    .enigma-conteudo {
        padding: 0 14px 12px 14px;
    }
    
    .enigma-texto {
        font-size: 0.8rem;
        padding-left: 12px;
    }
    
    .legado-conteudo {
        padding: 0 14px 16px 14px;
    }
    
    .legado-destaque {
        padding: 10px 12px;
    }
    
    .legado-filosofia {
        padding: 10px 12px;
    }
    
    .legado-final em {
        font-size: 0.85rem;
    }
    
    .luz-explicacao-body p {
        font-size: 0.75rem;
    }
    
    .sete-luzes-conteudo {
        padding: 0 12px 16px 12px;
    }
    
    .luz-explicacao {
        padding: 10px 12px;
    }
}
</style>