<?php

namespace App\Controllers\Participante;

use App\Core\Controller;
use App\Helpers\Flash;
use App\Helpers\Validator;
use App\Services\AuthService;

final class AuthController extends Controller
{
    public function __construct(private AuthService $auth = new AuthService())
    {
    }

    public function formCadastro(): void
    {
        $this->render('participante/auth/cadastro');
    }

    public function cadastrar(): void
    {
        $erros = Validator::validate($_POST, [
            'nome'     => 'required|min:3',
            'email'    => 'required|email',
            'telefone' => 'required',
            'senha'    => 'required|min:6',
        ]);

        if (!empty($erros)) {
            $this->render('participante/auth/cadastro', ['erros' => $erros]);
            return;
        }

        $this->auth->cadastrarParticipante(
            $this->input('nome'),
            $this->input('email'),
            $this->input('telefone'),
            $this->input('senha')
        );

        Flash::sucesso('Cadastro realizado com sucesso. Faça login para continuar.');
        $this->redirect('/login');
    }

    public function formLogin(): void
    {
        $this->render('participante/auth/login');
    }

    public function login(): void
    {
        $usuario = $this->auth->autenticar($this->input('email'), $this->input('senha'));

        if (!$usuario) {
            Flash::erro('E-mail ou senha inválidos.');
            $this->redirect('/login');
            return;
        }

        $this->redirect('/rifas');
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->redirect('/login');
    }
}
