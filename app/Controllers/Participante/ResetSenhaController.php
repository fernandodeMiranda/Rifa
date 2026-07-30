<?php

namespace App\Controllers\Participante;

use App\Core\Controller;
use App\Helpers\Flash;
use App\Services\ResetSenhaService;

final class ResetSenhaController extends Controller
{
    public function __construct(
        private ResetSenhaService $resetService = new ResetSenhaService()
    ) {
    }

    public function formSolicitar(): void
    {
        $this->render('participante/reset-senha/solicitar', [], layout: 'app');
    }

    public function solicitar(): void
    {
        $email = $this->input('email');

        if (empty($email)) {
            Flash::erro('Preencha o e-mail.');
            $this->redirect('/esqueci-senha');
            return;
        }

        try {
            // Mesma mensagem independentemente do e-mail existir ou não,
            // para não revelar quais e-mails estão cadastrados.
            $this->resetService->solicitarReset($email);
            Flash::sucesso('Se o e-mail estiver cadastrado, você receberá um link para redefinir a senha em instantes.');
        } catch (\Throwable) {
            Flash::erro('Não foi possível enviar o e-mail agora. Tente novamente mais tarde.');
        }

        $this->redirect('/login');
    }

    public function formRedefinir(): void
    {
        $token = $this->input('token') ?? '';

        if (empty($token)) {
            Flash::erro('Token não fornecido.');
            $this->redirect('/login');
            return;
        }

        $reset = $this->resetService->validarToken($token);

        if (!$reset) {
            Flash::erro('Token inválido ou expirado.');
            $this->redirect('/login');
            return;
        }

        $this->render('participante/reset-senha/redefinir', [
            'token' => $token,
        ], layout: 'app');
    }

    public function redefinir(): void
    {
        try {
            $token = $this->input('token');
            $novaSenha = $this->input('nova_senha');
            $confirmacao = $this->input('confirmacao');

            if (empty($token) || empty($novaSenha) || empty($confirmacao)) {
                Flash::erro('Preencha todos os campos.');
                $this->redirect("/redefinir-senha?token=$token");
                return;
            }

            if ($novaSenha !== $confirmacao) {
                Flash::erro('As senhas não conferem.');
                $this->redirect("/redefinir-senha?token=$token");
                return;
            }

            if (strlen($novaSenha) < 6) {
                Flash::erro('A senha deve ter no mínimo 6 caracteres.');
                $this->redirect("/redefinir-senha?token=$token");
                return;
            }

            $this->resetService->redefinirSenha($token, $novaSenha);

            Flash::sucesso('Senha redefinida com sucesso! Faça login com sua nova senha.');
            $this->redirect('/login');
        } catch (\InvalidArgumentException | \RuntimeException $e) {
            Flash::erro($e->getMessage());
            $this->redirect('/esqueci-senha');
        }
    }
}
