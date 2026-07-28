<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Sorteio;
use PDO;

final class SorteioRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function buscarPorRifa(int $rifaId): ?Sorteio
    {
        $stmt = $this->db->prepare('SELECT * FROM sorteios WHERE rifa_id = :rifa_id');
        $stmt->execute(['rifa_id' => $rifaId]);
        $row = $stmt->fetch();

        return $row ? Sorteio::fromArray($row) : null;
    }

    // RN08 - UNIQUE(rifa_id) garante, a nível de banco, um único sorteio por rifa.
    public function criar(Sorteio $sorteio): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO sorteios (rifa_id, numero_vencedor_id, participante_vencedor_id, metodo, semente, realizado_por)
             VALUES (:rifa_id, :numero_vencedor_id, :participante_vencedor_id, :metodo, :semente, :realizado_por)'
        );

        $stmt->execute([
            'rifa_id'                  => $sorteio->rifaId,
            'numero_vencedor_id'       => $sorteio->numeroVencedorId,
            'participante_vencedor_id' => $sorteio->participanteVencedorId,
            'metodo'                   => $sorteio->metodo,
            'semente'                  => $sorteio->semente,
            'realizado_por'            => $sorteio->realizadoPor,
        ]);

        return (int) $this->db->lastInsertId();
    }
}
