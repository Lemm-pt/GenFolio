<div class="container mt-4">
    <h2>Painel de Controlo</h2>
    <p class="text-muted">Bem-vindo, <?= $_SESSION['admin_user'] ?? 'Admin' ?>! Configure o seu site abaixo.</p>
    
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?= count($servicos) ?></h3>
                    <p>Serviços</p>
                    <a href="?a=admin_servicos" class="btn btn-sm btn-gold">Gerir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?= count($galeria) ?></h3>
                    <p>Galeria (max 7)</p>
                    <a href="?a=admin_galeria" class="btn btn-sm btn-gold">Gerir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?= count($produtos) ?></h3>
                    <p>Produtos (max 6)</p>
                    <a href="?a=admin_produtos" class="btn btn-sm btn-gold">Gerir</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?= count($publicacoes) ?></h3>
                    <p>Publicações (max 7)</p>
                    <a href="?a=admin_publicacoes" class="btn btn-sm btn-gold">Gerir</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-gold text-dark">Configurações Rápidas</div>
                <div class="card-body">
                    <p><strong>Logotipo:</strong> <?= htmlspecialchars($config->get('logo_parte1', 'Vitrine')) ?><?= htmlspecialchars($config->get('logo_parte2', '.lemm')) ?></p>
                    <p><strong>Slogan:</strong> <?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas')) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($config->get('email_contacto', 'Não definido')) ?></p>
                    <p><strong>Telefone:</strong> <?= htmlspecialchars($config->get('telefone', 'Não definido')) ?></p>
                    <a href="?a=admin_configuracoes" class="btn btn-sm btn-primary">Configurações Completas</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-gold text-dark">Pré-visualização</div>
                <div class="card-body text-center">
                    <h2>
                        <span style="color: white;"><?= htmlspecialchars($config->get('logo_parte1', 'Vitrine')) ?></span>
                        <span style="color: #C6A43F;"><?= htmlspecialchars($config->get('logo_parte2', '.lemm')) ?></span>
                    </h2>
                    <p class="text-muted"><?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>