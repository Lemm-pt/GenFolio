<?php if (empty($artigo) || !is_object($artigo)): ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="alert alert-warning">
                Artigo não encontrado.
            </div>
        </div>
    </div>
</div>
<?php return; ?>
<?php endif; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <article>
                <h1 class="mb-3"><?= htmlspecialchars($artigo->titulo) ?></h1>
                <div class="text-muted mb-4">
                    <small>Publicado em <?= date('d/m/Y', strtotime($artigo->created_at)) ?></small>
                </div>
                
                <?php if($artigo->imagem): ?>
                    <img src="<?= BASE_URL ?>assets/images/blog/<?= $artigo->imagem ?>" class="img-fluid rounded mb-4" alt="<?= htmlspecialchars($artigo->titulo) ?>" style="width: 100%; max-height: 100%; object-fit: cover;">
                <?php endif; ?>
                
                <div class="article-content">
                    <?= nl2br(htmlspecialchars_decode($artigo->conteudo)) ?>
                </div>
                
                <hr class="my-5">
                
                <div class="text-center">
                    <div class="text-center">
                       <a href="<?= BASE_URL . CLIENTE_SLUG ?>/blog" class="btn-gold">← Voltar ao Blog</a>
                   </div>
                </div>
            </article>
        </div>
    </div>
</div>

<style>
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #ddd;
}
.article-content p {
    margin-bottom: 1.5rem;
}
.article-content strong {
    color: #C6A43F;
}
</style>