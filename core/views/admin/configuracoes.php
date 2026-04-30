<h2>Configurações do Site</h2>
<?php if(isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Logotipo - Parte 1 (cor normal)</label>
            <input type="text" name="logo_parte1" class="form-control" value="<?= htmlspecialchars($config['logo_parte1'] ?? 'Jo') ?>">
            <small class="text-muted">Ex: "Jo" – aparecerá normal</small>
        </div>
        <div class="col-md-6 mb-3">
            <label>Logotipo - Parte 2 (cor dourada)</label>
            <input type="text" name="logo_parte2" class="form-control" value="<?= htmlspecialchars($config['logo_parte2'] ?? 'Folio') ?>">
            <small class="text-muted">Ex: "Folio" – aparecerá dourado</small>
        </div>
        <div class="col-md-6 mb-3">
            <label>Email para contacto (recebe mensagens)</label>
            <input type="email" name="email_contacto" class="form-control" value="<?= htmlspecialchars($config['email_contacto'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label>Telefone (rodapé e contacto)</label>
            <input type="text" name="telefone_contacto" class="form-control" value="<?= htmlspecialchars($config['telefone_contacto'] ?? '') ?>">
        </div>
        <div class="col-md-12 mb-3">
            <label>Nome do site</label>
            <input type="text" name="nome_site" class="form-control" value="<?= htmlspecialchars($config['nome_site'] ?? 'JoFolio') ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label>Facebook URL</label>
            <input type="url" name="facebook_url" class="form-control" value="<?= htmlspecialchars($config['facebook_url'] ?? '#') ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label>Instagram URL</label>
            <input type="url" name="instagram_url" class="form-control" value="<?= htmlspecialchars($config['instagram_url'] ?? '#') ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label>LinkedIn URL</label>
            <input type="url" name="linkedin_url" class="form-control" value="<?= htmlspecialchars($config['linkedin_url'] ?? '#') ?>">
        </div>
        <div class="col-md-12 mb-3">
            <label>Tipo de serviço (para tornar genérico)</label>
            <select name="tipo_servico" class="form-control">
                <option value="imobiliario" <?= ($config['tipo_servico'] ?? '') == 'imobiliario' ? 'selected' : '' ?>>Imobiliário (Imóveis)</option>
                <option value="saude" <?= ($config['tipo_servico'] ?? '') == 'saude' ? 'selected' : '' ?>>Saúde (Procedimentos)</option>
                <option value="beleza" <?= ($config['tipo_servico'] ?? '') == 'beleza' ? 'selected' : '' ?>>Beleza (Serviços)</option>
                <option value="consultoria" <?= ($config['tipo_servico'] ?? '') == 'consultoria' ? 'selected' : '' ?>>Consultoria (Projetos)</option>
            </select>
            <small>Isto muda os rótulos na front-end (ex: "Imóveis", "Procedimentos", etc.)</small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Configurações</button>
    <a href="?a=admin" class="btn btn-secondary">Voltar</a>
</form>