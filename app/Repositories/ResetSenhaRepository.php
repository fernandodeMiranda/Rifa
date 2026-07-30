<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\ResetSenha;

final class ResetSenhaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function criar(ResetSenha $reset): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reset_senhas (usuario_id, token, expira_em)
             VALUES (:usuario_id, :token, :expira_em)"
        );

        $stmt->execute([
            'usuario_id' => $reset->usuarioId,
            'token'      => $reset->token,
            'expira_em'  => $reset->expiraEm->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function buscarPorToken(string $token): ?ResetSenha
    {
        $stmt = $this->db->prepare('SELECT * FROM reset_senhas WHERE token = :token');
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch();

        return $row ? ResetSenha::fromArray($row) : null;
    }

    public function apagar(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM reset_senhas WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function apagarPorUsuario(int $usuarioId): void
    {
        $stmt = $this->db->prepare('DELETE FROM reset_senhas WHERE usuario_id = :usuario_id');
        $stmt->execute(['usuario_id' => $usuarioId]);
    }

    public function apagarExpirados(): void
    {
        $stmt = $this->db->prepare('DELETE FROM reset_senhas WHERE expira_em < NOW()');
        $stmt->execute();
    }
}
