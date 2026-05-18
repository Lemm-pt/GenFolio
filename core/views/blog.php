<div class="container py-5">
    <div class="text-center mb-5">
        <h1>Blog</h1>
        <p class="lead">Últimas publicações</p>
    </div>
    
    <div class="row">
        <?php if(!empty($publicacoes)): ?>
            <?php foreach($publicacoes as $pub): ?>
            <div class="col-md-4 mb-4">
                <div class="card blog-card h-100">
                    <?php if($pub->imagem): ?>
                        <img src="<?= BASE_URL ?>assets/images/blog/<?= $pub->imagem ?>" class="card-img-top" style="height: 100%; object-fit: cover;" alt="<?= htmlspecialchars($pub->titulo) ?>">
                    <?php else: ?>
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-newspaper fa-3x text-white"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <small class="text-gold"><?= date('d/m/Y', strtotime($pub->created_at)) ?></small>
                        <h4 class="mt-2"><?= htmlspecialchars($pub->titulo) ?></h4>
                        <p><?= htmlspecialchars(substr(strip_tags($pub->conteudo), 0, 120)) ?>...</p>
                     <a href="?a=artigo&slug=<?= $pub->slug ?>" class="text-gold">Ler mais →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                <h3>Nenhuma publicação ainda</h3>
                <p>Em breve, novidades por aqui!</p>
            </div>
        <?php endif; ?>
    </div>
</div>