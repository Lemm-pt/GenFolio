<div class="container mt-4">
    <h2><?= isset($publicacao) ? 'Editar Publicação' : 'Nova Publicação' ?></h2>
    
    <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Título *</label>
            <input type="text" name="titulo" class="form-control" value="<?= $publicacao->titulo ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Conteúdo *</label>
            <textarea name="conteudo" class="form-control" rows="10" required><?= $publicacao->conteudo ?? '' ?></textarea>
            <small>Suporta HTML: use &lt;strong&gt; para negrito, &lt;br&gt; para quebra de linha</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Imagem de destaque</label>
            <?php if(isset($publicacao) && $publicacao->imagem): ?>
                <div class="mb-2">
                    <img src="<?= BASE_URL ?>assets/images/blog/<?= $publicacao->imagem ?>" style="height: 100px;">
                    <p class="small text-muted">Imagem atual</p>
                </div>
            <?php endif; ?>
            <input type="file" name="imagem" class="form-control" accept="image/*">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="publicado" class="form-check-input" id="publicado" <?= (isset($publicacao) && $publicacao->publicado) ? 'checked' : 'checked' ?>>
            <label class="form-check-label" for="publicado">Publicar imediatamente</label>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="?a=admin_publicacoes" class="btn btn-secondary">Cancelar</a>
    </form>
</div>