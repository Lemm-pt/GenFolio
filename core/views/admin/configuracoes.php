<div class="container mt-4">
    <h2>Configurações do Site</h2>
    
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
    
    <form method="POST" action="?a=admin_salvar_config">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Logotipo - Parte 1 (cor normal)</label>
                <input type="text" name="logo_parte1" class="form-control" value="<?= htmlspecialchars($config->get('logo_parte1', 'Vitrine')) ?>">
                <small class="text-muted">Ex: "Vitrine" – aparecerá em branco</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Logotipo - Parte 2 (cor dourada)</label>
                <input type="text" name="logo_parte2" class="form-control" value="<?= htmlspecialchars($config->get('logo_parte2', '.lemm')) ?>">
                <small class="text-muted">Ex: ".lemm" – aparecerá dourado</small>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Slogan</label>
            <input type="text" name="slogan" class="form-control" value="<?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas para web vitrine')) ?>">
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="2"><?= htmlspecialchars($config->get('meta_description', 'Vitrine.lemm - Soluções digitais para o seu negócio')) ?></textarea>
            <small class="text-muted">Descrição que aparece nos resultados de busca (max 160 caracteres)</small>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Keywords (max 7, separadas por vírgula)</label>
            <input type="text" name="meta_keywords" class="form-control" value="<?= htmlspecialchars($config->get('meta_keywords', 'vitrine,lemm,digital')) ?>">
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email (recebe contactos)</label>
                <input type="email" name="email_contacto" class="form-control" value="<?= htmlspecialchars($config->get('email_contacto', $_SESSION['usuario'] ?? '')) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Telefone</label>
                <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($config->get('telefone', '')) ?>">
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Endereço (para o mapa)</label>
            <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($config->get('endereco', '')) ?>">
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-gold px-4">💾 Guardar Configurações</button>
            <a href="?a=admin" class="btn btn-secondary px-4">↩️ Voltar</a>
        </div>
    </form>
    
    <div class="mt-5 p-4 bg-light rounded">
        <h4>Pré-visualização do Logotipo</h4>
        <div class="text-center p-4" style="background: #1a1a2e; border-radius: 10px;">
            <h2 class="mb-0">
                <span style="color: white;"><?= htmlspecialchars($config->get('logo_parte1', 'Vitrine')) ?></span>
                <span style="color: #C6A43F;"><?= htmlspecialchars($config->get('logo_parte2', '.lemm')) ?></span>
            </h2>
            <p class="text-white-50 mt-2"><?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas')) ?></p>
        </div>
    </div>
</div>