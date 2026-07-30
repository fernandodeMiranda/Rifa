<?php

/**
 * Configuração de envio de e-mail (SMTP via PHPMailer).
 * Valores sensíveis vêm do .env — ver .env.example.
 */

return [
    'smtp_host'       => env('SMTP_HOST', 'smtp.gmail.com'),
    'smtp_port'       => (int) env('SMTP_PORT', '587'),
    'smtp_username'   => env('SMTP_USERNAME', ''),
    'smtp_password'   => env('SMTP_PASSWORD', ''),
    'smtp_encryption' => env('SMTP_ENCRYPTION', 'tls'),
    'from_email'      => env('MAIL_FROM_EMAIL', env('SMTP_USERNAME', '')),
    'from_name'       => env('MAIL_FROM_NAME', 'Sistema de Rifa'),
];
