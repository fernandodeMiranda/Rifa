<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Reserva;
use PDO;

final class ReservaRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function buscarPorId(int $id): ?Reserva
    {
        $stmt = $this->db->prepare('SELECT * FROM reservas WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Reserva::fromArray($row) : null;
    }

    public function criar(Reserva $reserva): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reservas (rifa_id, participante_id, status, quantidade_numeros, valor_total, expira_em)
             VALUES (:rifa_id, :participante_id, 'ativa', :quantidade_numeros, :valor_total, :expira_em)"
        );

        $stmt->execute([
            'rifa_id'            => $reserva->rifaId,
            'participante_id'    => $reserva->participanteId,
            'quantidade_numeros' => $reserva->quantidadeNumeros,
            'valor_total'        => $reserva->valorTotal,
            'expira_em'          => $reserva->expiraEm,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function atualizarStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE reservas SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    // RN02 - usado pelo job de expiração (scripts/expirar_reservas.php).
    public function listarExpiradas(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM reservas WHERE status = 'ativa' AND expira_em < NOW()"
        );
        return array_map(fn ($row) => Reserva::fromArray($row), $stmt->fetchAll());
    }

    public function listarPorParticipante(int $participanteId): array
    {
        $stmt = $this->db->prepare(
            'SELECT reservas.*, rifas.titulo AS rifa_titulo
             FROM reservas
             JOIN rifas ON rifas.id = reservas.rifa_id
             WHERE reservas.participante_id = :participante_id
             ORDER BY reservas.criado_em DESC'
        );
        $stmt->execute(['participante_id' => $participanteId]);
        return array_map(fn ($row) => Reserva::fromArray($row), $stmt->fetchAll());
    }
}
