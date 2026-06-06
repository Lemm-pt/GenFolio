<?php

define('APP_NAME',          'Vitrine');
define('APP_VERSION',       '1.0');
define('BASE_URL',          'http://localhost/sevenlux/public/');

// // Base de Dados
// define('MYSQL_SERVER',      'lhcp3350.webapps.net');
// define('MYSQL_DATABASE',    'vl2tjdok_spacet');
// define('MYSQL_USER',        'vl2tjdok_lemm');
// define('MYSQL_PASS',        'Mackyver');
// define('MYSQL_CHARSET',     'utf8');

// Base de Dados
define('MYSQL_SERVER',      'localhost');
define('MYSQL_DATABASE',    'sevenlux');
define('MYSQL_USER',        'root');
define('MYSQL_PASS',        '');
define('MYSQL_CHARSET',     'utf8');


// mail para para envio validação e Email (para formulário de contacto)
define('EMAIL_HOST',        'smtp-pt.securemail.pro');
define('EMAIL_FROM',        'luciano@lemm.pt');
define('EMAIL_PASS',        'S&mStr&ss');
define('EMAIL_PORT',        465);
define('ESTADO',            ['PENDENTE','EM PROCESSAMENTO','ENVIADA','CANCELADA','CONCLUIDA']);


// AES encriptação
define('AES_KEY',           'qs8BzdLD8N7qJgqJ3qmGsuh8HMhCWqG4');
define('AES_IV',            'WSzH6HcdZAYdQ9be');