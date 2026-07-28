<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Rifa;
use PDO;

final class RifaRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function buscarPorId(int $id): ?Rifa
    {
        $stmt = $this->db->prepare('SELECT * FROM rifas WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Rifa::fromArray($row) : null;
    }

    public function listarPublicadas(): array
    {
        $stmt = $this->db->query("SELECT * FROM rifas WHERE status = 'publicada' ORDER BY data_sorteio ASC");
        return array_map(fn ($row) => Rifa::fromArray($row), $stmt->fetchAll());
    }

    public function listarPorOrganizador(int $organizadorId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM rifas WHERE organizador_id = :organizador_id ORDER BY criado_em DESC');
        $stmt->execute(['organizador_id' => $organizadorId]);
        return array_map(fn ($row) => Rifa::fromArray($row), $stmt->fetchAll());
    }

    public function criar(Rifa $rifa): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO rifas (organizador_id, titulo, descricao, imagem_capa, preco_numero, numero_inicial, numero_final, data_sorteio, status)
             VALUES (:organizador_id, :titulo, :descricao, :imagem_capa, :preco_numero, :numero_inicial, :numero_final, :data_sorteio, :status)'
        );

        $stmt->execute([
            'organizador_id' => $rifa->organizadorId,
            'titulo'         => $rifa->titulo,
            'descricao'      => $rifa->descricao,
            'imagem_capa'    => $rifa->imagemCapa,
            'preco_numero'   => $rifa->precoNumero,
            'numero_inicial' => $rifa->numeroInicial,
            'numero_final'   => $rifa->numeroFinal,
            'data_sorteio'   => $rifa->dataSorteio,
            'status'         => $rifa->status,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function atualizarStatus(int $rifaId, string $status): void
    {
        $coluna = match ($status) {
            'publicada' => 'publicada_em',
            'encerrada' => 'encerrada_em',
            default     => null,
        };

        $sql = $coluna
            ? "UPDATE rifas SET status = :status, {$coluna} = NOW() WHERE id = :id"
            : 'UPDATE rifas SET status = :status WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status, 'id' => $rifaId]);
    }

    // TODO: atualizar(Rifa $rifa), excluir(int $id) — bloqueado se status != rascunho (RN07).
}
