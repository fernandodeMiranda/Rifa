<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Flash;
use App\Services\AuthService;

final class AuthController extends Controller
{
    public function __construct(private AuthService $auth = new AuthService())
    {
    }

    public function formLogin(): void
    {
        $this->render('admin/auth/login', [], layout: 'admin');
    }

    public function login(): void
    {
        $usuario = $this->auth->autenticar($this->input('email'), $this->input('senha'));

        if (!$usuario || !in_array($usuario->tipo, ['organizador', 'administrador'], true)) {
            Flash::erro('Credenciais inválidas ou sem permissão de acesso.');
            $this->redirect('/admin/login');
            return;
        }

        $this->redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->redirect('/admin/login');
    }
}
