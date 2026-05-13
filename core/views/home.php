<!-- Hero Section -->
<section class="hero" style="padding-top: 40px; min-height: 60vh; display: flex; align-items: center;">
    <div class="container text-center text-white">
        <h1><span class="logo-part1"><?= htmlspecialchars($config->get('logo_parte1', 'Vitrine')) ?></span><span style="color:#C6A43F"><?= htmlspecialchars($config->get('logo_parte2', '.lemm')) ?></span></h1>
        <p class="lead"><?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas')) ?></p>
        <a href="#servicos" class="btn-gold mt-4 d-inline-block">Conheça os Serviços</a>
    </div>
</section>

<!-- SERVIÇOS (só aparece se houver) -->
<?php if(!empty($servicos)): ?>
<section id="servicos" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Serviços</h2>
        <div class="row">
            <?php foreach($servicos as $servico): ?>
            <div class="col-md-4 mb-4">
                <div class="servico-card text-center h-100">
                    <i class="fas <?= $servico->icone ?> fa-3x mb-3" style="color: #C6A43F;"></i>
                    <h4><?= htmlspecialchars($servico->titulo) ?></h4>
                    <p><?= htmlspecialchars($servico->descricao) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- PRODUTOS (só aparece se houver) -->
<?php if(!empty($produtos)): ?>
<section id="produtos" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center text-dark mb-5">Produtos</h2>
        <div class="row">
            <?php foreach($produtos as $produto): ?>
            <div class="col-md-4 mb-4">
                <div class="card produto-card h-100">
                    <?php if($produto->imagem): ?>
                        <img src="<?= BASE_URL ?>assets/images/produtos/<?= $produto->imagem ?>" class="card-img-top" style="height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 220px;">
                            <i class="fas fa-image fa-3x text-white"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5><?= htmlspecialchars($produto->nome) ?></h5>
                        <p><?= htmlspecialchars(substr($produto->descricao ?? '', 0, 100)) ?>...</p>
                        <?php if($produto->preco): ?>
                            <p class="text-gold fw-bold">€ <?= number_format($produto->preco, 2, ',', '.') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- MAPA - ONDE ESTAMOS -->
<section id="mapa" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Onde Estamos</h2>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="map-container">
                    <iframe 
                        src="https://maps.google.com/maps?q=<?= urlencode($config->get('endereco', 'Esposende, Portugal')) ?>&output=embed"
                        width="100%" 
                        height="400" 
                        style="border:0; border-radius: 15px;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
                <p class="text-center mt-3">
                    <i class="fas fa-map-marker-alt text-gold"></i> 
                    <?= htmlspecialchars($config->get('endereco', 'Esposende, Portugal')) ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CONTACTO -->
<section id="contacto" class="py-5 bg-dark text-white">
    <div class="container">
        <h2 class="text-center mb-4">Contacto</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if(isset($_SESSION['msg_sucesso'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['msg_sucesso']; unset($_SESSION['msg_sucesso']); ?></div>
                <?php elseif(isset($_SESSION['msg_erro'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['msg_erro']; unset($_SESSION['msg_erro']); ?></div>
                <?php endif; ?>
                <form action="?a=contacto" method="POST">
                    <input type="text" name="nome" class="form-control mb-3" placeholder="Seu nome" required>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Seu email" required>
                    <input type="tel" name="telefone" class="form-control mb-3" placeholder="Telefone">
                    <textarea name="mensagem" rows="4" class="form-control mb-3" placeholder="Mensagem" required></textarea>
                    <button type="submit" class="btn-gold w-100">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </div>
</section>