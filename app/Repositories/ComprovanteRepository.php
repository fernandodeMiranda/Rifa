<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Comprovante;
use PDO;

final class ComprovanteRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function buscarPorId(int $id): ?Comprovante
    {
        $stmt = $this->db->prepare('SELECT * FROM comprovantes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Comprovante::fromArray($row) : null;
    }

    public function criar(Comprovante $comprovante): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO comprovantes (reserva_id, participante_id, arquivo_path, valor_informado, status)
             VALUES (:reserva_id, :participante_id, :arquivo_path, :valor_informado, 'pendente')"
        );

        $stmt->execute([
            'reserva_id'      => $comprovante->reservaId,
            'participante_id' => $comprovante->participanteId,
            'arquivo_path'    => $comprovante->arquivoPath,
            'valor_informado' => $comprovante->valorInformado,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function listarPendentesPorOrganizador(int $organizadorId): array
    {
        $stmt = $this->db->prepare(
            "SELECT comprovantes.*, rifas.titulo AS rifa_titulo
             FROM comprovantes
             JOIN reservas ON reservas.id = comprovantes.reserva_id
             JOIN rifas ON rifas.id = reservas.rifa_id
             WHERE comprovantes.status = 'pendente' AND rifas.organizador_id = :organizador_id
             ORDER BY comprovantes.enviado_em ASC"
        );
        $stmt->execute(['organizador_id' => $organizadorId]);
        return array_map(fn ($row) => Comprovante::fromArray($row), $stmt->fetchAll());
    }

    // RN04/RN05 - aprovação/rejeição feita por administrador ou organizador.
    public function atualizarStatus(int $id, string $status, int $analisadoPor, ?string $observacao = null): void
    {
        $stmt = $this->db->prepare(
            'UPDATE comprovantes
             SET status = :status, analisado_por = :analisado_por, observacao = :observacao, analisado_em = NOW()
             WHERE id = :id'
        );

        $stmt->execute([
            'status'        => $status,
            'analisado_por' => $analisadoPor,
            'observacao'    => $observacao,
            'id'            => $id,
        ]);
    }
}
