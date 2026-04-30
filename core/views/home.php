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
            <div class="col-md-4"><div class="servico-card"><i class="fas fa-building fa-3x"></i><h4>Mediação Imobiliária</h4><p>Curadoria de imóveis de prestígio.</p></div></div>
            <div class="col-md-4"><div class="servico-card"><i class="fas fa-chart-line fa-3x"></i><h4>Intermediação de Crédito</h4><p>Melhores soluções bancárias.</p></div></div>
            <div class="col-md-4"><div class="servico-card"><i class="fas fa-hard-hat fa-3x"></i><h4>Construção</h4><p>Projeto chave-na-mão.</p></div></div>
        </div>
    </div>
</section>

<!-- Imóveis em destaque -->
<section id="imoveis" class="py-5 bg-light">
    <div class="container">
       <h2 class="text-center mb-5"><?= $config->get('tipo_servico') == 'imobiliario' ? 'Imóveis em Destaque' : 'Destaques' ?></h2>
        <div class="row">
            <?php foreach($imoveis as $imovel): ?>
            <div class="col-md-4 mb-4">
                <div class="card imovel-card">
                    <?php if($imovel->imagem): ?>
                        <img src="assets/images/<?= $imovel->imagem ?>" class="card-img-top" alt="">
                    <?php else: ?>
                        <div class="bg-secondary text-white text-center p-5">Sem imagem</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5><?= htmlspecialchars($imovel->titulo) ?></h5>
                        <p class="text-gold">€ <?= number_format($imovel->preco, 0, ',', '.') ?></p>
                        <a href="?a=imovel&slug=<?= $imovel->slug ?>" class="btn btn-sm btn-outline-gold">Ver detalhes</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Últimas publicações -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Blog</h2>
        <div class="row">
            <?php foreach($publicacoes as $pub): ?>
            <div class="col-md-4">
                <div class="card blog-card">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($pub->titulo) ?></h5>
                        <p><?= substr(strip_tags($pub->conteudo), 0, 100) ?>...</p>
                        <a href="?a=artigo&slug=<?= $pub->slug ?>" class="text-gold">Ler mais →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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