<!-- Hero Section -->
<section class="hero" style="padding-top: 40px; min-height: 60vh; display: flex; align-items: center;">
    <div class="container text-center text-white">
        <h1>
            <span class="logo-part1"><?= htmlspecialchars($config->get('logo_parte1', 'Seven')) ?></span>
            <span style="color:#C6A43F"><?= htmlspecialchars($config->get('logo_parte2', 'Lux')) ?></span>
        </h1>
        <p class="lead"><?= htmlspecialchars($config->get('slogan', 'Soluções Personalizadas')) ?></p>

        <!-- Dynamic descriptive text -->
        <?php if ($config->get('texto_descritivo')): ?>
            <p class="mt-3 text-white-50" style="max-width: 700px; margin-left: auto; margin-right: auto; font-size: 1.1rem;">
                <?= nl2br(htmlspecialchars($config->get('texto_descritivo'))) ?>
            </p>
        <?php endif; ?>

        <a href="#servicos" class="btn-gold mt-4 d-inline-block">Conheça os Serviços</a>
    </div>
</section>

<!-- Services Section (only if there are services) -->
<?php if (!empty($servicos)): ?>
    <section id="servicos" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Serviços</h2>
            <div class="row">
                <?php foreach ($servicos as $servico): ?>
                    <div class="col-md-4 mb-4">
                        <div class="servico-card text-center h-100 d-flex flex-column">
                            <div class="flex-grow-1">
                                <i class="fas <?= $servico->icone ?> fa-3x mb-3" style="color: #C6A43F;"></i>
                                <h4><?= htmlspecialchars($servico->titulo) ?></h4>
                                <p><?= htmlspecialchars($servico->descricao) ?></p>
                            </div>
                            <!-- More info button -->
                            <button type="button"
                                    class="btn btn-sm btn-outline-gold mt-3 btn-info-servico"
                                    data-servico="<?= htmlspecialchars($servico->titulo) ?>">
                                <i class="fas fa-info-circle"></i> Mais informações
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Products Section (only if there are products) -->
<?php if (!empty($produtos)): ?>
    <section id="produtos" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center text-dark mb-5">Produtos</h2>
            <div class="row">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card produto-card h-100">
                            <?php if ($produto->imagem): ?>
                                <img src="<?= BASE_URL ?>assets/images/produtos/<?= $produto->imagem ?>"
                                     class="card-img-top"
                                     style="height: 220px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 220px;">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($produto->nome) ?></h5>
                                <p class="card-text flex-grow-1">
                                    <?= htmlspecialchars(substr($produto->descricao ?? '', 0, 100)) ?>...
                                </p>

                                <?php if ($produto->preco): ?>
                                    <p class="text-gold fw-bold mb-3">
                                     <?php 
                                     $moeda = defined('CLIENTE_CURRENCY') ? CLIENTE_CURRENCY : 'EUR';
                                     $simbolo = \core\classes\LocaleHelper::getCurrencySymbol($moeda);
                                     echo $simbolo . ' ' . number_format($produto->preco, 2, ',', '.');
                                     ?>
                                 </p>
                                <?php endif; ?>

                                <!-- Minimalist buttons -->
                                <div class="d-flex gap-2 mt-2">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-gold flex-grow-1 text-center btn-info-produto"
                                            data-produto="<?= htmlspecialchars($produto->nome) ?>">
                                        <i class="fas fa-info-circle"></i> Informações
                                    </button>
                                    <a href="tel:<?= preg_replace('/[^0-9+]/', '', $config->get('telefone', '')) ?>"
                                       class="btn btn-sm btn-gold flex-grow-1 text-center">
                                        <i class="fas fa-phone-alt"></i> Encomendar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Gallery Section (only if there are photos) -->
<?php if (!empty($galeria)): ?>
    <section id="galeria" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Galeria</h2>
            <div class="row">
                <?php foreach ($galeria as $foto): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="galeria-card">
                            <img src="<?= BASE_URL ?>assets/images/galeria/<?= $foto->imagem ?>"
                                 class="img-fluid rounded"
                                 style="height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                 onclick="abrirModalImagem('<?= BASE_URL ?>assets/images/galeria/<?= $foto->imagem ?>', '<?= htmlspecialchars($foto->legenda ?? '') ?>')">
                            <?php if ($foto->legenda): ?>
                                <p class="text-center mt-2 small"><?= htmlspecialchars($foto->legenda) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Modal for large image view -->
    <div id="modalImagem"
         style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; cursor: pointer;"
         onclick="fecharModalImagem()">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%;">
            <img id="modalImagemSrc" src="" style="max-width: 100%; max-height: 90vh; border-radius: 10px;">
            <p id="modalImagemLegenda" style="color: white; text-align: center; margin-top: 10px;"></p>
        </div>
        <button style="position: absolute; top: 20px; right: 30px; background: none; border: none; color: white; font-size: 40px; cursor: pointer;">&times;</button>
    </div>

    <script>
        function abrirModalImagem(src, legenda) {
            document.getElementById('modalImagemSrc').src = src;
            document.getElementById('modalImagemLegenda').innerHTML = legenda;
            document.getElementById('modalImagem').style.display = 'block';
        }

        function fecharModalImagem() {
            document.getElementById('modalImagem').style.display = 'none';
        }
    </script>
<?php endif; ?>




<!-- ============================================ -->
<!-- 🔥 HORÁRIO DE ATENDIMENTO -->
<!-- ============================================ -->
<?php 
$horarioModel = new \core\models\Horario(CLIENTE_ID);
if ($horarioModel->isAtivo()) {
    include('horario.php'); 
}
?>

<!-- Map Section - Where we are -->
<section id="mapa" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Onde Estamos</h2>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="map-container">
                    <iframe src="https://maps.google.com/maps?q=<?= urlencode($config->get('endereco', 'Esposende, Portugal')) ?>&output=embed"
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

<!-- Contact Section -->
<section id="contacto" class="py-5 bg-dark text-white">
    <div class="container">
        <h2 class="text-center mb-4">Contacto</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($_SESSION['msg_sucesso'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['msg_sucesso'];
                        unset($_SESSION['msg_sucesso']); ?></div>
                <?php elseif (isset($_SESSION['msg_erro'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['msg_erro'];
                        unset($_SESSION['msg_erro']); ?></div>
                <?php endif; ?>
                <form action="?a=contacto" method="POST">
                    
                    <?php // para evitar os BOT automáticos ?>
                    <input type="text" name="empresa_interna_777" style="display:none">

                     <?php  //  Humano → 20 segundos, 30 segundos, 1 minuto.  Bot → 0.1 segundos.
                          $_SESSION['contact_form_time'] = time();
                      ?>

                    <input type="text" name="nome" class="form-control mb-3" placeholder="Seu nome" required>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Seu email" required>
                    <input type="tel" name="telefone" class="form-control mb-3" placeholder="Telefone">
                    <textarea name="mensagem" rows="4" class="form-control mb-3" placeholder="Mensagem" required></textarea>
                    <button type="submit" class="btn-gold w-100">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Smooth scroll to contact section with pre-filled message (Services & Products)
        document.addEventListener('DOMContentLoaded', function () {
            function preencherMensagemComItem(tipo, nome) {
                const textarea = document.querySelector('#contacto textarea[name="mensagem"]');
                if (textarea && nome) {
                    // Check if the message does not already contain the item name
                    if (!textarea.value.includes(nome)) {
                        if (tipo === 'servico') {
                            textarea.value = `Olá, gostaria de mais informações sobre o serviço: ${nome}\n\n`;
                        } else {
                            textarea.value = `Olá, gostaria de mais informações sobre o produto: ${nome}\n\n`;
                        }
                    }
                }
            }

            // ============================================
            // SERVICES BUTTONS
            // ============================================
            const botoesServicos = document.querySelectorAll('.btn-info-servico');
            console.log("Services buttons found:", botoesServicos.length);

            botoesServicos.forEach(botao => {
                botao.addEventListener('click', function (e) {
                    e.preventDefault();
                    const nomeServico = this.dataset.servico;

                    // Pre-fill message immediately
                    preencherMensagemComItem('servico', nomeServico);

                    // Smooth scroll to contact section
                    const secaoContacto = document.getElementById('contacto');
                    if (secaoContacto) {
                        const offset = 80;
                        const elementPosition = secaoContacto.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - offset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });

                        // Focus on name field after scroll
                        setTimeout(function () {
                            const campoNome = document.querySelector('#contacto input[name="nome"]');
                            if (campoNome) campoNome.focus();
                        }, 500);
                    }
                });
            });

            // ============================================
            // PRODUCTS BUTTONS
            // ============================================
            const botoesProdutos = document.querySelectorAll('.btn-info-produto');
            console.log("Products buttons found:", botoesProdutos.length);

            botoesProdutos.forEach(botao => {
                botao.addEventListener('click', function (e) {
                    e.preventDefault();
                    const nomeProduto = this.dataset.produto;

                    // Pre-fill message immediately
                    preencherMensagemComItem('produto', nomeProduto);

                    // Smooth scroll to contact section
                    const secaoContacto = document.getElementById('contacto');
                    if (secaoContacto) {
                        const offset = 80;
                        const elementPosition = secaoContacto.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - offset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });

                        // Focus on name field after scroll
                        setTimeout(function () {
                            const campoNome = document.querySelector('#contacto input[name="nome"]');
                            if (campoNome) campoNome.focus();
                        }, 500);
                    }
                });
            });

            // ============================================
            // URL PARAMETER FALLBACK
            // ============================================
            function getUrlParameter(name) {
                const query = window.location.search.substring(1);
                const params = query.split('&');
                for (let i = 0; i < params.length; i++) {
                    const [key, value] = params[i].split('=');
                    if (decodeURIComponent(key) === name) {
                        return value ? decodeURIComponent(value.replace(/\+/g, ' ')) : null;
                    }
                }
                return null;
            }

            const produtoUrl = getUrlParameter('produto');
            const servicoUrl = getUrlParameter('servico');

            if (produtoUrl) {
                setTimeout(function () {
                    preencherMensagemComItem('produto', produtoUrl);
                }, 500);
            }

            if (servicoUrl) {
                setTimeout(function () {
                    preencherMensagemComItem('servico', servicoUrl);
                }, 500);
            }
        });
    </script>
</section>