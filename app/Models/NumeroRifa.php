<?php

namespace App\Models;

/**
 * Representa um registro da tabela `numeros_rifa`.
 *
 * RN01 - Um número pode assumir apenas um estado:
 *        livre | reservado | pago | cancelado | premiado
 */
final class NumeroRifa
{
    public const STATUS_LIVRE = 'livre';
    public const STATUS_RESERVADO = 'reservado';
    public const STATUS_PAGO = 'pago';
    public const STATUS_CANCELADO = 'cancelado';
    public const STATUS_PREMIADO = 'premiado';

    public function __construct(
        public ?int $id,
        public int $rifaId,
        public int $numero,
        public string $status = self::STATUS_LIVRE,
        public ?int $participanteId = null,
        public ?int $reservaId = null,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            rifaId: (int) $row['rifa_id'],
            numero: (int) $row['numero'],
            status: $row['status'] ?? self::STATUS_LIVRE,
            participanteId: isset($row['participante_id']) ? (int) $row['participante_id'] : null,
            reservaId: isset($row['reserva_id']) ? (int) $row['reserva_id'] : null,
        );
    }

    public function estaDisponivel(): bool
    {
        return $this->status === self::STATUS_LIVRE;
    }
}
