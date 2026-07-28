<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;

/**
 * Gestão de usuários pelo administrador — promoção de participante
 * a organizador (habilita criar rifas e acessar o painel privado).
 */
final class UsuarioService
{
    public function __construct(
        private UsuarioRepository $usuarios = new UsuarioRepository(),
    ) {
    }

    public function listarTodos(): array
    {
        return $this->usuarios->listarTodos();
    }

    public function promoverAOrganizador(int $usuarioId): void
    {
        $usuario = $this->usuarios->buscarPorId($usuarioId);
        if (!$usuario) {
            throw new \RuntimeException('Usuário não encontrado.');
        }
        if ($usuario->isAdministrador()) {
            throw new \RuntimeException('Administrador não precisa ser promovido.');
        }

        $this->usuarios->atualizarTipo($usuarioId, 'organizador');
    }

    public function rebaixarAParticipante(int $usuarioId): void
    {
        $usuario = $this->usuarios->buscarPorId($usuarioId);
        if (!$usuario) {
            throw new \RuntimeException('Usuário não encontrado.');
        }
        if ($usuario->isAdministrador()) {
            throw new \RuntimeException('Não é possível rebaixar um administrador por aqui.');
        }

        $this->usuarios->atualizarTipo($usuarioId, 'participante');
    }
}
