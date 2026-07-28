<?php

namespace App\Middlewares;

use App\Core\Session;

/**
 * Permite acesso a organizador (dono da rifa) OU administrador.
 * RN04 - Somente administrador ou organizador podem aprovar pagamentos.
 */
final class OrganizadorMiddleware
{
    public function handle(): void
    {
        Session::start();
        $usuario = Session::usuarioLogado();

        if (!$usuario || !in_array($usuario['tipo'], ['organizador', 'administrador'], true)) {
            header('Location: /admin/login');
            exit;
        }
    }
}
