<?php

namespace App\Middlewares;

use App\Core\Session;

/**
 * RN04 - Somente administrador (ou organizador, via OrganizadorMiddleware)
 * podem acessar ações de aprovação de pagamento, sorteio, etc.
 */
final class AdminMiddleware
{
    public function handle(): void
    {
        Session::start();
        $usuario = Session::usuarioLogado();

        if (!$usuario || $usuario['tipo'] !== 'administrador') {
            header('Location: /admin/login');
            exit;
        }
    }
}
