<footer class="rodape text-center py-3">
    <div class="container">
        <small>
            <?= $config->get('logo_parte1', 'Vitrine') ?><?= $config->get('logo_parte2', '.lemm') ?> &copy; <?= date('Y') ?> | 
            <a href="mailto:<?= $config->get('email_contacto', '') ?>" class="text-gold"><?= $config->get('email_contacto', '') ?></a> | 
            <?= $config->get('telefone', '') ?>
        </small>
    </div>
</footer>