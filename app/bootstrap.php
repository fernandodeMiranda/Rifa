<?php

/**
 * Ponto único de inicialização da aplicação:
 * variáveis de ambiente, autoloader, timezone e sessão.
 */

require_once __DIR__ . '/Core/Env.php';
require_once __DIR__ . '/Core/Autoloader.php';

// Procura .env em dois locais possíveis:
// 1. Um nível acima de app/ (layout: pasta irma)
// 2. No mesmo nível de app/ dentro de document root (layout: pasta unica)
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    $envPath = __DIR__ . '/.env';
}
Env::load($envPath);
Autoloader::register(__DIR__);

$config = require __DIR__ . '/Config/config.php';
date_default_timezone_set($config['app_timezone']);

if ($config['app_debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

\App\Core\Session::start();
