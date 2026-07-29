<?php

// Suporta dois layouts de deploy: app/ como pasta irmã de public/ (padrão
// deste repositório) ou app/ dentro do próprio document root (hospedagens
// que só expõem uma única pasta pública, ex.: htdocs/ do InfinityFree).
$appDir = is_dir(__DIR__ . '/../app') ? __DIR__ . '/../app' : __DIR__ . '/app';

require_once $appDir . '/bootstrap.php';

use App\Core\Router;

$router = new Router();
require $appDir . '/routes.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
