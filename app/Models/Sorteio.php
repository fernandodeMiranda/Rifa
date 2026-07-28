<?php

namespace App\Models;

/**
 * Representa um registro da tabela `sorteios`.
 * RN08 - Após o sorteio não é permitido alterar o vencedor (1 sorteio/rifa).
 */
final class Sorteio
{
    public function __construct(
        public ?int $id,
        public int $rifaId,
        public int $numeroVencedorId,
        public int $participanteVencedorId,
        public int $realizadoPor,
        public string $metodo = 'aleatorio_sistema',
        public ?string $semente = null,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            rifaId: (int) $row['rifa_id'],
            numeroVencedorId: (int) $row['numero_vencedor_id'],
            participanteVencedorId: (int) $row['participante_vencedor_id'],
            realizadoPor: (int) $row['realizado_por'],
            metodo: $row['metodo'] ?? 'aleatorio_sistema',
            semente: $row['semente'] ?? null,
        );
    }
}
