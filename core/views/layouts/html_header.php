<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($config->get('meta_description', APP_NAME)) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($config->get('meta_keywords', '')) ?>">
    <title><?= $config->get('logo_parte1', 'Vitrine') ?><?= $config->get('logo_parte2', '.lemm') ?> | <?= $config->get('slogan', 'Soluções Personalizadas') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/app.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a1a;
            color: #eee;
        }
    </style>
</head>
<body>