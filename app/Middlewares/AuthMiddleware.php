<?php

namespace App\Middlewares;

use App\Core\Session;

/**
 * Garante que exista um usuário autenticado (qualquer tipo) na sessão.
 * Usado nas rotas do participante (escolher números, enviar comprovante...).
 */
final class AuthMiddleware
{
    public function handle(): void
    {
        Session::start();

        if (!Session::usuarioLogado()) {
            header('Location: /login');
            exit;
        }
    }
}
