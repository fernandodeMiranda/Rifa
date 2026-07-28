<?php

namespace App\Services;

use App\Core\Session;
use App\Models\Usuario;
use App\Repositories\UsuarioRepository;

/**
 * Cadastro e autenticação de participantes, organizadores e administradores.
 */
final class AuthService
{
    public function __construct(
        private UsuarioRepository $usuarios = new UsuarioRepository(),
    ) {
    }

    public function cadastrarParticipante(string $nome, string $email, string $telefone, string $senha): Usuario
    {
        if ($this->usuarios->buscarPorEmail($email) !== null) {
            throw new \InvalidArgumentException('Já existe um cadastro com este e-mail.');
        }

        $usuario = new Usuario(
            id: null,
            nome: $nome,
            email: $email,
            telefone: $telefone,
            senhaHash: password_hash($senha, PASSWORD_BCRYPT),
            tipo: 'participante',
        );

        $usuario->id = $this->usuarios->criar($usuario);
        return $usuario;
    }

    public function autenticar(string $email, string $senha): ?Usuario
    {
        $usuario = $this->usuarios->buscarPorEmail($email);

        if (!$usuario || !password_verify($senha, $usuario->senhaHash)) {
            return null;
        }

        Session::set('usuario', [
            'id'    => $usuario->id,
            'nome'  => $usuario->nome,
            'email' => $usuario->email,
            'tipo'  => $usuario->tipo,
        ]);

        return $usuario;
    }

    public function logout(): void
    {
        Session::remove('usuario');
    }
}
