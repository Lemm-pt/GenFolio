<footer class="rodape py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <small class="text-white-50">
                    <?= $config->get('logo_parte1', 'Vitrine') ?><?= $config->get('logo_parte2', '.lemm') ?> &copy; <?= date('Y') ?>
                </small>
                <br>
                <small class="text-white-50">
                    <a href="mailto:<?= $config->get('email_contacto', '') ?>" class="text-gold">
                        <?= $config->get('email_contacto', '') ?>
                    </a> | 
                    <?= $config->get('telefone', '') ?>
                </small>
            </div>
        </div>
    </div>
</footer>