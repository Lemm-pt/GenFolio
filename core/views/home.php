<section class="hero">
    <div class="container text-center text-white">
        <h1><span class="logo-parte1"><?= $config->get('logo_parte1', 'Jo') ?></span><span style="color:#C6A43F"><?= $config->get('logo_parte2', 'Folio') ?></span></h1>
        <p class="lead"><?= $config->get('nome_site', 'JoFolio') ?> – <?= ucfirst($config->get('tipo_servico', 'imobiliario')) === 'imobiliario' ? 'Consultoria Imobiliária & Financeira' : 'Soluções Personalizadas' ?></p>
        <a href="#servicos" class="btn-gold">Conheça os Serviços</a>
    </div>
</section>

<!-- Serviços -->
<section id="servicos" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Serviços</h2>
        <div class="row">
            <?php if(!empty($servicos)): ?>
                <?php foreach($servicos as $servico): ?>
                <div class="col-md-4">
                    <div class="servico-card text-center">
                        <i class="fas <?= $servico->icone ?> fa-3x mb-3" style="color: #C6A43F;"></i>
                        <h4><?= htmlspecialchars($servico->titulo) ?></h4>
                        <p><?= htmlspecialchars($servico->descricao) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>Adicione serviços no backoffice.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- PRODUTOS EM DESTAQUE -->
<section id="produtos" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5"><?= $config->get('tipo_servico', 'imobiliario') == 'imobiliario' ? 'Produtos em Destaque' : 'Destaques' ?></h2>
        <div class="row">
            <?php if(!empty($produtos)): ?>
                <?php foreach($produtos as $produto): ?>
                <div class="col-md-4 mb-4">
                  <div class="card produto-card h-100">
                      <?php if($produto->imagem): ?>
                          <img src="<?= BASE_URL ?>assets/images/produtos/<?= $produto->imagem ?>" class="card-img-top" style="height: 100%; width: 100%; object-fit: cover;" alt="<?= htmlspecialchars($produto->nome) ?>">
                      <?php else: ?>
                          <div class="bg-secondary text-white text-center d-flex align-items-center justify-content-center" style="height: 220px;">
                              <i class="fas fa-image fa-3x"></i>
                              <span class="ms-2">Sem imagem</span>
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
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Sem produtos em destaque no momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Últimas publicações -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Blog</h2>
        <div class="row">
            <?php if(!empty($publicacoes)): ?>
                <?php foreach($publicacoes as $pub): ?>
                <div class="col-md-4 mb-4">
                    <div class="card blog-card h-100">
                        <?php if($pub->imagem): ?>
                            <img src="<?= BASE_URL ?>assets/images/blog/<?= $pub->imagem ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5><?= htmlspecialchars($pub->titulo) ?></h5>
                            <p><?= htmlspecialchars(substr(strip_tags($pub->conteudo), 0, 100)) ?>...</p>
                            <a href="?a=artigo&slug=<?= $pub->slug ?>" class="text-gold">Ler mais →</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>Nenhuma publicação ainda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Formulário de contacto -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <h2 class="text-center mb-4">Fale Connosco</h2>
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