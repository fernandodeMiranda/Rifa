<?php

/**
 * Configuração de conexão com o banco de dados (PDO / MySQL).
 * Valores sensíveis vêm do .env — ver .env.example.
 */

return [
    'driver'   => 'mysql',
    'host'     => env('DB_HOST', '127.0.0.1'),
    'port'     => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'rifa'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset'  => 'utf8mb4',
];
