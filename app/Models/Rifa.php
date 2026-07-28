<?php

namespace App\Models;

/**
 * Representa um registro da tabela `rifas`.
 * status: rascunho | publicada | encerrada | cancelada
 */
final class Rifa
{
    public function __construct(
        public ?int $id,
        public int $organizadorId,
        public string $titulo,
        public ?string $descricao,
        public ?string $imagemCapa,
        public float $precoNumero,
        public int $numeroInicial,
        public int $numeroFinal,
        public string $dataSorteio,
        public string $status = 'rascunho',
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            organizadorId: (int) $row['organizador_id'],
            titulo: $row['titulo'],
            descricao: $row['descricao'] ?? null,
            imagemCapa: $row['imagem_capa'] ?? null,
            precoNumero: (float) $row['preco_numero'],
            numeroInicial: (int) $row['numero_inicial'],
            numeroFinal: (int) $row['numero_final'],
            dataSorteio: $row['data_sorteio'],
            status: $row['status'] ?? 'rascunho',
        );
    }

    // RN07 - Uma rifa encerrada não pode sofrer alterações.
    public function podeSerAlterada(): bool
    {
        return !in_array($this->status, ['encerrada', 'cancelada'], true);
    }
}
