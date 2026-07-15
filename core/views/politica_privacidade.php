<?php
/**
 * Política de Privacidade da SevenLux
 * A responsável pelo tratamento é a SevenLux, não o cliente individual
 */
if (!isset($config)) {
    $config = new \core\models\Configuracao(CLIENTE_ID);
}

$slug = CLIENTE_SLUG ?? 'vitrine-demo';
$ano = date('Y');
$dataAtual = date('d/m/Y');

// Dados da SevenLux (fixos)
$empresaNome = 'SevenLux';
$empresaEmail = 'geral@sevenlux.pt';
$empresaTelefone = '+351 900 000 000';
$empresaEndereco = 'Portugal';
$empresaSite = 'https://sevenlux.pt';
?>

<div class="container py-5" style="padding-top: 100px !important;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="politica-wrapper">
                <!-- Cabeçalho -->
                <div class="politica-header text-center mb-5">
                    <div class="mb-3">
                        <span class="badge bg-gold text-dark p-2" style="font-size: 1rem;">
                            <i class="fas fa-shield-alt"></i> Política de Privacidade
                        </span>
                    </div>
                    <h1 class="display-4">
                        <span style="color: #ffffff;">Seven</span>
                        <span style="color: #C6A43F;">Lux</span>
                    </h1>
                    <p class="lead text-gold">Plataforma de Gestão de Sites</p>
                    <p class="text-muted">
                        <i class="fas fa-calendar-alt"></i> Última atualização: <?= $dataAtual ?>
                    </p>
                    <div class="empresa-badge mt-3">
                        <span class="badge bg-dark p-2" style="border: 1px solid rgba(198, 164, 63, 0.3);">
                            <i class="fas fa-building text-gold"></i> 
                            <?= $empresaNome ?>
                            <span class="text-muted mx-1">|</span>
                            <i class="fas fa-globe text-gold"></i> 
                            <?= $empresaSite ?>
                        </span>
                    </div>
                </div>

                <!-- Aviso para o cliente -->
                <div class="alert alert-info mb-4" style="background: rgba(198, 164, 63, 0.1); border: 1px solid rgba(198, 164, 63, 0.3); border-radius: 12px; color: #e0e0e0;">
                    <div class="d-flex align-items-start gap-3">
                        <i class="fas fa-info-circle fa-2x text-gold mt-1"></i>
                        <div>
                            <strong><i class="fas fa-link"></i> Site: <?= BASE_URL . $slug ?>/</strong>
                            <p class="mb-0 small text-muted">
                                Esta Política de Privacidade aplica-se a todos os sites alojados na plataforma SevenLux, 
                                incluindo o site <strong><?= htmlspecialchars($slug) ?></strong>. A SevenLux é a 
                                responsável pelo tratamento dos dados.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Conteúdo -->
                <div class="politica-conteudo">
                    
                    <!-- 1. Introdução -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-info-circle text-gold"></i> 1. Introdução</h2>
                        <p>
                            A <strong>SevenLux</strong>
                            é a entidade responsável pelo tratamento dos dados pessoais recolhidos através da 
                            plataforma <strong>SevenLux</strong> e de todos os sites alojados na mesma, incluindo 
                            o site <strong><?= htmlspecialchars($slug) ?></strong>.
                        </p>
                        <p>
                            Esta Política de Privacidade explica como recolhemos, utilizamos, partilhamos e 
                            protegemos as suas informações quando visita qualquer site alojado na plataforma SevenLux.
                        </p>
                    </div>

                    <!-- 2. Responsável pelo Tratamento -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-user-tie text-gold"></i> 2. Responsável pelo Tratamento</h2>
                        <div class="card bg-dark p-3" style="background: #1a1a2e !important; border: 1px solid rgba(198, 164, 63, 0.2);">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-building"></i> Empresa:</strong> <?= $empresaNome ?></p>
                                    <p><strong><i class="fas fa-globe"></i> Site:</strong> <a href="<?= $empresaSite ?>" target="_blank" class="text-gold"><?= $empresaSite ?></a></p>
                                    <p><strong><i class="fas fa-tag"></i> Plataforma:</strong> SevenLux</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-envelope"></i> Email:</strong> <a href="mailto:<?= $empresaEmail ?>" class="text-gold"><?= $empresaEmail ?></a></p>
                                    <p><strong><i class="fas fa-phone"></i> Telefone:</strong> <?= $empresaTelefone ?></p>
                                    <p><strong><i class="fas fa-map-marker-alt"></i> Endereço:</strong> <?= $empresaEndereco ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Dados Recolhidos -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-database text-gold"></i> 3. Dados que Recolhemos</h2>
                        <p>Recolhemos os seguintes tipos de dados:</p>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="card h-100 bg-dark" style="background: #1a1a2e !important; border: 1px solid rgba(198, 164, 63, 0.15);">
                                    <div class="card-body">
                                        <h6 class="text-gold"><i class="fas fa-user"></i> Dados Pessoais</h6>
                                        <ul class="list-unstyled small">
                                            <li><i class="fas fa-check text-success"></i> Nome</li>
                                            <li><i class="fas fa-check text-success"></i> Email</li>
                                            <li><i class="fas fa-check text-success"></i> Telefone</li>
                                            <li><i class="fas fa-check text-success"></i> Mensagens enviadas</li>
                                            <li><i class="fas fa-check text-success"></i> Dados de registo (slug, país, cidade)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 bg-dark" style="background: #1a1a2e !important; border: 1px solid rgba(198, 164, 63, 0.15);">
                                    <div class="card-body">
                                        <h6 class="text-gold"><i class="fas fa-chart-line"></i> Dados de Navegação</h6>
                                        <ul class="list-unstyled small">
                                            <li><i class="fas fa-check text-success"></i> Endereço IP</li>
                                            <li><i class="fas fa-check text-success"></i> Navegador e dispositivo</li>
                                            <li><i class="fas fa-check text-success"></i> Páginas visitadas</li>
                                            <li><i class="fas fa-check text-success"></i> Tempo de visita</li>
                                            <li><i class="fas fa-check text-success"></i> URL de origem (referer)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Como Utilizamos os Dados -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-cogs text-gold"></i> 4. Como Utilizamos os Dados</h2>
                        <p>Utilizamos os dados recolhidos para:</p>
                        <ul class="lista-check">
                            <li><i class="fas fa-check-circle text-gold"></i> Responder às suas mensagens e pedidos</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Melhorar a experiência no site</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Enviar informações solicitadas</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Cumprir obrigações legais</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Analisar o tráfego e comportamento dos utilizadores</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Gerir a plataforma e os sites alojados</li>
                        </ul>
                    </div>

                    <!-- 5. Gestão da Conta - NOVO -->
                    <div class="politica-secao" style="border-left: 3px solid #C6A43F; padding-left: 20px;">
                        <h2><i class="fas fa-user-cog text-gold"></i> 5. Gestão da Sua Conta</h2>
                        <p>
                            Como utilizador da plataforma SevenLux, tem controlo sobre a sua conta e dados. 
                            Pode gerir a sua conta através do painel administrativo:
                        </p>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="card h-100 bg-dark" style="background: #1a1a2e !important; border: 1px solid rgba(40, 167, 69, 0.3);">
                                    <div class="card-body text-center">
                                        <i class="fas fa-pause-circle fa-3x text-warning"></i>
                                        <h6 class="mt-2 text-warning">Pausar Conta</h6>
                                        <p class="small text-muted">
                                            Pausar temporariamente o seu site. O conteúdo é mantido, mas o site 
                                            fica inacessível ao público.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100 bg-dark" style="background: #1a1a2e !important; border: 1px solid rgba(255, 193, 7, 0.3);">
                                    <div class="card-body text-center">
                                        <i class="fas fa-toggle-off fa-3x text-secondary"></i>
                                        <h6 class="mt-2 text-secondary">Desativar Conta</h6>
                                        <p class="small text-muted">
                                            Desativar a conta. O site fica offline e os dados são preservados 
                                            por um período determinado.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100 bg-dark" style="background: #1a1a2e !important; border: 1px solid rgba(220, 53, 69, 0.3);">
                                    <div class="card-body text-center">
                                        <i class="fas fa-trash-alt fa-3x text-danger"></i>
                                        <h6 class="mt-2 text-danger">Apagar Conta</h6>
                                        <p class="small text-muted">
                                            Eliminar permanentemente a sua conta e todos os dados associados. 
                                            Esta ação é <strong>irreversível</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 p-3" style="background: rgba(198, 164, 63, 0.05); border-radius: 10px; border: 1px solid rgba(198, 164, 63, 0.1);">
                            <p class="small mb-0 text-muted">
                                <i class="fas fa-info-circle text-gold"></i> 
                                Para gerir a sua conta, aceda ao painel administrativo do seu site 
                                (<strong><?= BASE_URL . $slug ?>/admin</strong>) e vá a 
                                <strong>Configurações → Gestão de Conta</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- 6. Cookies -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-cookie-bite text-gold"></i> 6. Cookies</h2>
                        <p>
                            Utilizamos cookies para melhorar a sua experiência no site. Os cookies são pequenos 
                            ficheiros de texto que são armazenados no seu dispositivo. Pode controlar a utilização 
                            de cookies através das configurações do seu navegador.
                        </p>
                        <div class="card bg-dark p-3 mt-2" style="background: #1a1a2e !important; border: 1px solid rgba(198, 164, 63, 0.15);">
                            <div class="row text-center">
                                <div class="col-6 col-md-3">
                                    <i class="fas fa-cookie fa-2x text-gold"></i>
                                    <p class="small mt-1">Sessão</p>
                                </div>
                                <div class="col-6 col-md-3">
                                    <i class="fas fa-cookie fa-2x text-gold"></i>
                                    <p class="small mt-1">Preferências</p>
                                </div>
                                <div class="col-6 col-md-3">
                                    <i class="fas fa-cookie fa-2x text-gold"></i>
                                    <p class="small mt-1">Analíticos</p>
                                </div>
                                <div class="col-6 col-md-3">
                                    <i class="fas fa-cookie fa-2x text-gold"></i>
                                    <p class="small mt-1">Segurança</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Partilha de Dados -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-share-alt text-gold"></i> 7. Partilha de Dados</h2>
                        <p>
                            Não vendemos, alugamos ou partilhamos os seus dados pessoais com terceiros, 
                            exceto nos seguintes casos:
                        </p>
                        <ul class="lista-check">
                            <li><i class="fas fa-check-circle text-gold"></i> Quando exigido por lei</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Para proteger os nossos direitos</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Com prestadores de serviços essenciais (ex: hosting, email, servidores)</li>
                            <li><i class="fas fa-check-circle text-gold"></i> Com o proprietário do site (cliente) para gestão da plataforma</li>
                        </ul>
                    </div>

                    <!-- 8. Segurança -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-shield-alt text-gold"></i> 8. Segurança</h2>
                        <p>
                            Implementamos medidas de segurança adequadas para proteger os seus dados contra 
                            acesso não autorizado, alteração, divulgação ou destruição. Utilizamos:
                        </p>
                        <div class="row g-2 mt-2">
                            <div class="col-auto">
                                <span class="badge bg-success p-2">🔒 HTTPS</span>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-info p-2">🛡️ Firewall</span>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-warning p-2">🔐 Criptografia</span>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-secondary p-2">📋 Auditoria</span>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-primary p-2">🔄 Backups</span>
                            </div>
                        </div>
                    </div>

                    <!-- 9. Direitos do Utilizador -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-gavel text-gold"></i> 9. Os Seus Direitos</h2>
                        <p>De acordo com o RGPD, tem direito a:</p>
                        <div class="row g-2 mt-2">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-2 p-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <i class="fas fa-check-circle text-gold mt-1"></i>
                                    <span>Aceder aos seus dados pessoais</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-2 p-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <i class="fas fa-check-circle text-gold mt-1"></i>
                                    <span>Retificar dados incorretos</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-2 p-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <i class="fas fa-check-circle text-gold mt-1"></i>
                                    <span>Solicitar a eliminação dos dados</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-2 p-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <i class="fas fa-check-circle text-gold mt-1"></i>
                                    <span>Retirar consentimento a qualquer momento</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-2 p-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <i class="fas fa-check-circle text-gold mt-1"></i>
                                    <span>Opor-se ao processamento dos dados</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-2 p-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <i class="fas fa-check-circle text-gold mt-1"></i>
                                    <span>Portabilidade dos dados</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 10. Contacto -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-envelope text-gold"></i> 10. Contacto</h2>
                        <p>
                            Se tiver dúvidas sobre esta Política de Privacidade ou sobre o tratamento dos 
                            seus dados pessoais, entre em contacto connosco:
                        </p>
                        <div class="card bg-dark p-3" style="background: #1a1a2e !important; border: 1px solid rgba(198, 164, 63, 0.2);">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-building"></i> Empresa:</strong> <?= $empresaNome ?></p>
                                    <p><strong><i class="fas fa-globe"></i> Site:</strong> <a href="<?= $empresaSite ?>" target="_blank" class="text-gold"><?= $empresaSite ?></a></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-envelope"></i> Email:</strong> <a href="mailto:<?= $empresaEmail ?>" class="text-gold"><?= $empresaEmail ?></a></p>
                                    <p><strong><i class="fas fa-phone"></i> Telefone:</strong> <?= $empresaTelefone ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 11. Atualizações -->
                    <div class="politica-secao">
                        <h2><i class="fas fa-sync-alt text-gold"></i> 11. Atualizações desta Política</h2>
                        <p>
                            Esta Política de Privacidade pode ser atualizada periodicamente. 
                            A data da última atualização está indicada no topo desta página. 
                            Recomendamos que reveja esta página regularmente para se manter informado.
                        </p>
                    </div>

                </div>

                <!-- Rodapé da política -->
                <div class="politica-footer text-center mt-5 pt-4 border-top border-secondary">
                    <p class="small text-muted">
                        <i class="fas fa-copyright"></i> <?= $ano ?> <strong class="text-gold">SevenLux</strong> | 
                        <a href="<?= BASE_URL . $slug ?>/" class="text-gold">Voltar ao site</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   POLÍTICA DE PRIVACIDADE
   ============================================ */
.politica-wrapper {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 20px;
    padding: 40px;
    border: 1px solid rgba(198, 164, 63, 0.1);
    backdrop-filter: blur(10px);
}

.politica-header h1 {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
}

.empresa-badge .badge {
    background: rgba(198, 164, 63, 0.15) !important;
    border: 1px solid rgba(198, 164, 63, 0.3);
    font-size: 0.9rem;
    padding: 10px 20px;
}

.politica-secao {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.politica-secao:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.politica-secao h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: #e0e0e0;
    margin-bottom: 20px;
}

.politica-secao h2 i {
    margin-right: 10px;
}

.politica-secao p {
    color: #c0c0c0;
    line-height: 1.8;
}

.politica-secao .card {
    background: rgba(255, 255, 255, 0.03) !important;
}

.lista-check {
    list-style: none;
    padding: 0;
}

.lista-check li {
    padding: 8px 0;
    color: #c0c0c0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.lista-check li i {
    font-size: 1.1rem;
    min-width: 24px;
}

.badge.bg-gold {
    background: #C6A43F !important;
    color: #0a0a1a !important;
}

/* Links */
.politica-conteudo a:not(.text-gold) {
    color: #C6A43F;
    text-decoration: none;
}

.politica-conteudo a:not(.text-gold):hover {
    text-decoration: underline;
}

/* Responsivo */
@media (max-width: 768px) {
    .politica-wrapper {
        padding: 20px;
    }
    
    .politica-header h1 {
        font-size: 2rem;
    }
    
    .politica-secao h2 {
        font-size: 1.2rem;
    }
    
    .empresa-badge .badge {
        font-size: 0.75rem;
        padding: 6px 12px;
    }
}

@media (max-width: 576px) {
    .politica-wrapper {
        padding: 15px;
        border-radius: 12px;
    }
    
    .politica-header h1 {
        font-size: 1.5rem;
    }
}
</style>