<?php

namespace App\Models;

/**
 * Representa um registro da tabela `comprovantes`.
 * status: pendente | aprovado | rejeitado
 */
final class Comprovante
{
    public function __construct(
        public ?int $id,
        public int $reservaId,
        public int $participanteId,
        public string $arquivoPath,
        public ?float $valorInformado = null,
        public string $status = 'pendente',
        public ?string $observacao = null,
        public ?int $analisadoPor = null,
        public ?string $rifaTitulo = null,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            reservaId: (int) $row['reserva_id'],
            participanteId: (int) $row['participante_id'],
            arquivoPath: $row['arquivo_path'],
            valorInformado: isset($row['valor_informado']) ? (float) $row['valor_informado'] : null,
            status: $row['status'] ?? 'pendente',
            observacao: $row['observacao'] ?? null,
            analisadoPor: isset($row['analisado_por']) ? (int) $row['analisado_por'] : null,
            rifaTitulo: $row['rifa_titulo'] ?? null,
        );
    }
}
