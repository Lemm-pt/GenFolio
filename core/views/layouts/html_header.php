<!DOCTYPE html>
<html lang="<?= defined('CLIENTE_LOCALE') ? CLIENTE_LOCALE : 'pt' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($config->get('meta_description', APP_NAME)) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($config->get('meta_keywords', '')) ?>">
    <title><?= $config->get('logo_parte1', 'Seven') ?><?= $config->get('logo_parte2', 'Lux') ?> | <?= $config->get('slogan', 'Soluções Personalizadas') ?></title>
   


    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/seven-lux.css">
    <script>
    // Definir BASE_URL para o JavaScript
    var BASE_URL = '<?= BASE_URL ?>';
   </script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #0a0a1a;
            color: #eee;
        }

            h1, h2, h3, .logo { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
    </style>
</head>
<body>