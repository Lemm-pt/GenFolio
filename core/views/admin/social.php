<div class="container mt-4">
    <h2><i class="fas fa-share-alt text-gold"></i> Redes Sociais</h2>
    <p class="text-muted">Adicione os links das suas redes sociais. Preencha a URL para ativar o ícone.</p>
    
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="?a=admin_salvar_social" class="mt-4">
        <div class="row">
            <?php 
            $ordem = ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube', 'tiktok', 'whatsapp', 'pinterest', 'telegram', 'spotify', 'github', 'discord', 'threads'];
            foreach ($ordem as $rede):
                $info = $redes[$rede] ?? null;
                $disponivel = $disponiveis[$rede] ?? null;
                if (!$disponivel) continue;
                
                $url = $info['url'] ?? '';
                $ativo = $info['ativo'] ?? false;
                $icone = $disponivel['icone'];
                $cor = $disponivel['cor'];
                $label = $disponivel['label'];
            ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100 <?= !empty($url) && $ativo ? 'border-success' : '' ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="social-preview" style="width: 40px; height: 40px; background: <?= $cor ?>20; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid <?= $cor ?>40;">
                                <i class="fab <?= $icone ?>" style="color: <?= $cor ?>; font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?= $label ?></h6>
                                <?php if(!empty($url) && $ativo): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inativo</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <input type="url" name="social_<?= $rede ?>" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($url) ?>" 
                                   placeholder="https://<?= $rede ?>.com/seu-perfil"
                                   style="font-size: 0.85rem;">
                        </div>
                        
                        <div class="form-check">
                            <input type="hidden" name="ativo_<?= $rede ?>" value="0">
                            <input type="checkbox" name="ativo_<?= $rede ?>" 
                                   class="form-check-input" id="ativo_<?= $rede ?>" 
                                   value="1" <?= $ativo ? 'checked' : '' ?>
                                   <?= empty($url) ? 'disabled' : '' ?>>
                            <label class="form-check-label small" for="ativo_<?= $rede ?>">
                                <i class="fas fa-eye"></i> Mostrar no site
                            </label>
                        </div>
                        
                        <?php if(!empty($url)): ?>
                            <div class="mt-2">
                                <a href="<?= htmlspecialchars($url) ?>" target="_blank" class="small text-gold">
                                    <i class="fas fa-external-link-alt"></i> Ver perfil
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pré-visualização -->
        <div class="mt-4 p-4 bg-dark rounded">
            <h5><i class="fas fa-eye"></i> Pré-visualização</h5>
            <div class="social-preview-container text-center py-3">
                <?php 
                $socialPreview = new \core\models\Social($_SESSION['cliente_id']);
                echo $socialPreview->render('lg', 'justify-content-center');
                ?>
                <?php if(empty($socialPreview->getAtivas())): ?>
                    <p class="text-muted">Nenhuma rede social ativa. Adicione URLs e ative para ver aqui.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-gold px-4">
                <i class="fas fa-save"></i> Guardar Redes Sociais
            </button>
            <a href="?a=admin" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </form>
</div>

<style>
.social-preview-container .social-icon {
    margin: 0 10px;
    transition: all 0.3s ease;
}
.social-preview-container .social-icon:hover {
    transform: scale(1.15) !important;
}
.card.border-success {
    border-left: 3px solid #28a745 !important;
}
</style>