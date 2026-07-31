<?php

/**
 * Configurações gerais da aplicação.
 * Valores sensíveis vêm do .env (ver app/Core/Env.php).
 */

// Suporta dois layouts de deploy: public/ como pasta separada (padrão
// deste repositório) ou seu conteúdo já direto no document root
// (hospedagens com uma única pasta pública, ex.: htdocs/ do InfinityFree).
$baseDoisNiveisAcima = __DIR__ . '/../..';
$publicDir = is_dir($baseDoisNiveisAcima . '/public')
    ? $baseDoisNiveisAcima . '/public'
    : $baseDoisNiveisAcima;

return [
    'app_name'               => 'Sistema de Rifa Eletrônica',
    'app_url'                => env('APP_URL', 'http://localhost:8000'),
    'app_timezone'           => env('APP_TIMEZONE', 'America/Sao_Paulo'),
    'app_debug'               => filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),

    // RN02 - Uma reserva expira automaticamente após 30 minutos.
    'reserva_expira_minutos' => (int) env('RESERVA_EXPIRA_MINUTOS', 30),

    // Upload de comprovantes
    'upload_max_size_mb'     => (int) env('UPLOAD_MAX_SIZE_MB', 5),
    'upload_mimes_aceitos'   => ['image/jpeg', 'image/png', 'application/pdf'],
    'upload_dir'             => $publicDir . '/uploads/comprovantes',
];
