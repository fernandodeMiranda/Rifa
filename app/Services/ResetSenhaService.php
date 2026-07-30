<?php

namespace App\Services;

use App\Models\ResetSenha;
use App\Repositories\ResetSenhaRepository;
use App\Repositories\UsuarioRepository;

final class ResetSenhaService
{
    private ResetSenhaRepository $resets;
    private UsuarioRepository $usuarios;

    public function __construct()
    {
        $this->resets = new ResetSenhaRepository();
        $this->usuarios = new UsuarioRepository();
    }

    public function solicitarReset(string $email): string
    {
        $usuario = $this->usuarios->buscarPorEmail($email);

        if (!$usuario) {
            throw new \InvalidArgumentException('E-mail não encontrado.');
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

        return $token;
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
