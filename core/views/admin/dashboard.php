<div class="container mt-4">
    <h2 class="mb-4">Painel de Controlo</h2>
    <p class="text-muted mb-4">Bem-vindo administrador da <strong><?= $_SESSION['cliente_slug'] ?? 'Admin' ?></strong>! Configure o seu site abaixo.</p>
    
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center dashboard-card">
                <div class="card-body">
                    <h3 class="text-gold"><?= isset($servicos) ? count($servicos) : 0 ?></h3>
                    <p class="text-dark">Serviços</p>
                    <a href="?a=admin_servicos" class="btn btn-sm btn-gold">Gerir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center dashboard-card">
                <div class="card-body">
                    <h3 class="text-gold"><?= isset($galeria) ? count($galeria) : 0 ?></h3>
                    <p class="text-dark">Galeria (max 7)</p>
                    <a href="?a=admin_galeria" class="btn btn-sm btn-gold">Gerir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center dashboard-card">
                <div class="card-body">
                    <h3 class="text-gold"><?= isset($produtos) ? count($produtos) : 0 ?></h3>
                    <p class="text-dark">Produtos (max 7)</p>
                    <a href="?a=admin_produtos" class="btn btn-sm btn-gold">Gerir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center dashboard-card">
                <div class="card-body">
                    <h3 class="text-gold"><?= isset($publicacoes) ? count($publicacoes) : 0 ?></h3>
                    <p class="text-dark">Publicações (max 7)</p>
                    <a href="?a=admin_publicacoes" class="btn btn-sm btn-gold">Gerir</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cog"></i> Configurações Rápidas
                </div>
                <div class="card-body">
                    <p><strong>Logotipo:</strong> <span class="text-dark"><?= isset($config) ? htmlspecialchars($config->get('logo_parte1', 'Vitrine')) : 'Vitrine' ?><?= isset($config) ? htmlspecialchars($config->get('logo_parte2', '.lemm')) : '.lemm' ?></span></p>
                    <p><strong>Slogan:</strong> <span class="text-dark"><?= isset($config) ? htmlspecialchars($config->get('slogan', 'Soluções Personalizadas')) : 'Soluções Personalizadas' ?></span></p>
                    <p><strong>Email:</strong> <span class="text-dark"><?= isset($config) ? htmlspecialchars($config->get('email_contacto', 'Não definido')) : 'Não definido' ?></span></p>
                    <p><strong>Telefone:</strong> <span class="text-dark"><?= isset($config) ? htmlspecialchars($config->get('telefone', 'Não definido')) : 'Não definido' ?></span></p>
                    <a href="?a=admin_configuracoes" class="btn btn-sm btn-gold mt-2">Configurações Completas</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-eye"></i> Pré-visualização
                </div>
                <div class="card-body text-center">
                    <h3 class="mb-0">
                        <span class="text-dark"><?= isset($config) ? htmlspecialchars($config->get('logo_parte1', 'Vitrine')) : 'Vitrine' ?></span>
                        <span class="text-gold"><?= isset($config) ? htmlspecialchars($config->get('logo_parte2', '.lemm')) : '.lemm' ?></span>
                    </h3>
                    <p class="text-muted mt-2"><?= isset($config) ? htmlspecialchars($config->get('slogan', 'Soluções Personalizadas')) : 'Soluções Personalizadas' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>