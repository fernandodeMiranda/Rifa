<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Router;

$router = new Router();
require __DIR__ . '/../app/routes.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
