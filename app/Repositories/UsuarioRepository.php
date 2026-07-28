<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Usuario;
use PDO;

final class UsuarioRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function buscarPorId(int $id): ?Usuario
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Usuario::fromArray($row) : null;
    }

    public function buscarPorEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row ? Usuario::fromArray($row) : null;
    }

    public function criar(Usuario $usuario): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nome, email, telefone, senha_hash, tipo, status)
             VALUES (:nome, :email, :telefone, :senha_hash, :tipo, :status)'
        );

        $stmt->execute([
            'nome'       => $usuario->nome,
            'email'      => $usuario->email,
            'telefone'   => $usuario->telefone,
            'senha_hash' => $usuario->senhaHash,
            'tipo'       => $usuario->tipo,
            'status'     => $usuario->status,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function listarTodos(): array
    {
        $stmt = $this->db->query('SELECT * FROM usuarios ORDER BY nome ASC');
        return array_map(fn ($row) => Usuario::fromArray($row), $stmt->fetchAll());
    }

    public function atualizarTipo(int $id, string $tipo): void
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET tipo = :tipo WHERE id = :id');
        $stmt->execute(['tipo' => $tipo, 'id' => $id]);
    }
}
