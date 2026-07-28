<?php

namespace App\Models;

/**
 * Representa um registro da tabela `reservas`.
 * status: ativa | aguardando_aprovacao | confirmada | expirada | cancelada | rejeitada
 */
final class Reserva
{
    public function __construct(
        public ?int $id,
        public int $rifaId,
        public int $participanteId,
        public int $quantidadeNumeros,
        public float $valorTotal,
        public string $expiraEm,
        public string $status = 'ativa',
        public ?string $rifaTitulo = null,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            rifaId: (int) $row['rifa_id'],
            participanteId: (int) $row['participante_id'],
            quantidadeNumeros: (int) $row['quantidade_numeros'],
            valorTotal: (float) $row['valor_total'],
            expiraEm: $row['expira_em'],
            status: $row['status'] ?? 'ativa',
            rifaTitulo: $row['rifa_titulo'] ?? null,
        );
    }

    // RN02 - Uma reserva expira automaticamente após 30 minutos.
    public function expirou(): bool
    {
        return $this->status === 'ativa' && strtotime($this->expiraEm) < time();
    }
}
