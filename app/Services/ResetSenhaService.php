<?php

namespace App\Services;

use App\Models\ResetSenha;
use App\Repositories\ResetSenhaRepository;
use App\Repositories\UsuarioRepository;

final class ResetSenhaService
{
    private ResetSenhaRepository $resets;
    private UsuarioRepository $usuarios;
    private EmailService $email;

    public function __construct()
    {
        $this->resets = new ResetSenhaRepository();
        $this->usuarios = new UsuarioRepository();
        $this->email = new EmailService();
    }

    public function solicitarReset(string $email): void
    {
        $usuario = $this->usuarios->buscarPorEmail($email);

        if (!$usuario) {
            // Não revela se o e-mail existe ou não (evita enumeração de usuários).
            return;
        }

        // Apaga resets anteriores do mesmo usuário
        $this->resets->apagarPorUsuario($usuario->id);

        // Gera um token seguro
        $token = bin2hex(random_bytes(32));

        // Token válido por 1 hora
        $expiraEm = new \DateTime('+1 hour');

        $reset = new ResetSenha(
            id: null,
            usuarioId: $usuario->id,
            token: $token,
            expiraEm: $expiraEm,
        );

        $this->resets->criar($reset);

        $config = require __DIR__ . '/../Config/config.php';
        $link = rtrim($config['app_url'], '/') . '/redefinir-senha?token=' . $token;

        $corpo = "<p>Olá, {$usuario->nome}!</p>"
            . "<p>Recebemos uma solicitação para redefinir sua senha.</p>"
            . "<p><a href=\"{$link}\">Clique aqui para redefinir sua senha</a></p>"
            . "<p>Este link é válido por 1 hora. Se você não solicitou isso, ignore este e-mail.</p>";

        $this->email->enviar($usuario->email, $usuario->nome, 'Redefinição de senha', $corpo);
    }

    public function validarToken(string $token): ?ResetSenha
    {
        $reset = $this->resets->buscarPorToken($token);

        if (!$reset || !$reset->estaValido()) {
            return null;
        }

        return $reset;
    }

    public function redefinirSenha(string $token, string $novaSenha): bool
    {
        $reset = $this->validarToken($token);

        if (!$reset) {
            throw new \InvalidArgumentException('Token inválido ou expirado.');
        }

        $usuario = $this->usuarios->buscarPorId($reset->usuarioId);

        if (!$usuario) {
            throw new \RuntimeException('Usuário não encontrado.');
        }

        // Atualiza a senha
        $usuario->senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);
        $this->usuarios->atualizar($usuario);

        // Deleta o token usado
        $this->resets->apagar($reset->id);

        return true;
    }
}
