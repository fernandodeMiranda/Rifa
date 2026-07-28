<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\NumeroRifa;
use PDO;

final class NumeroRifaRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * Gera em lote os números de uma rifa recém-criada
     * (numero_inicial..numero_final), todos com status 'livre'.
     */
    public function gerarParaRifa(int $rifaId, int $numeroInicial, int $numeroFinal): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO numeros_rifa (rifa_id, numero, status) VALUES (:rifa_id, :numero, 'livre')"
        );

        $this->db->beginTransaction();
        for ($numero = $numeroInicial; $numero <= $numeroFinal; $numero++) {
            $stmt->execute(['rifa_id' => $rifaId, 'numero' => $numero]);
        }
        $this->db->commit();
    }

    public function listarPorRifa(int $rifaId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM numeros_rifa WHERE rifa_id = :rifa_id ORDER BY numero ASC');
        $stmt->execute(['rifa_id' => $rifaId]);
        return array_map(fn ($row) => NumeroRifa::fromArray($row), $stmt->fetchAll());
    }

    public function buscarDisponiveis(int $rifaId, array $numeros): array
    {
        if (empty($numeros)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($numeros), '?'));
        $stmt = $this->db->prepare(
            "SELECT * FROM numeros_rifa WHERE rifa_id = ? AND numero IN ({$placeholders}) AND status = 'livre' FOR UPDATE"
        );
        $stmt->execute([$rifaId, ...$numeros]);
        return array_map(fn ($row) => NumeroRifa::fromArray($row), $stmt->fetchAll());
    }

    public function reservar(array $numeroIds, int $reservaId, int $participanteId): void
    {
        $placeholders = implode(',', array_fill(0, count($numeroIds), '?'));
        $stmt = $this->db->prepare(
            "UPDATE numeros_rifa
             SET status = 'reservado', reserva_id = ?, participante_id = ?, reservado_em = NOW()
             WHERE id IN ({$placeholders})"
        );
        $stmt->execute([$reservaId, $participanteId, ...$numeroIds]);
    }

    public function marcarComoPago(int $reservaId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE numeros_rifa SET status = 'pago', pago_em = NOW() WHERE reserva_id = ?"
        );
        $stmt->execute([$reservaId]);
    }

    public function liberar(int $reservaId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE numeros_rifa
             SET status = 'livre', reserva_id = NULL, participante_id = NULL, reservado_em = NULL
             WHERE reserva_id = ?"
        );
        $stmt->execute([$reservaId]);
    }

    public function listarPagosPorRifa(int $rifaId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM numeros_rifa WHERE rifa_id = ? AND status = 'pago'");
        $stmt->execute([$rifaId]);
        return array_map(fn ($row) => NumeroRifa::fromArray($row), $stmt->fetchAll());
    }

    public function marcarComoPremiado(int $numeroId): void
    {
        $stmt = $this->db->prepare("UPDATE numeros_rifa SET status = 'premiado' WHERE id = ?");
        $stmt->execute([$numeroId]);
    }
}
