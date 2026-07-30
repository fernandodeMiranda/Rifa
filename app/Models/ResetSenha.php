<?php

namespace App\Models;

final class ResetSenha
{
    public function __construct(
        public ?int $id,
        public int $usuarioId,
        public string $token,
        public \DateTime $expiraEm,
        public ?\DateTime $criadoEm = null,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            usuarioId: (int) $row['usuario_id'],
            token: $row['token'],
            expiraEm: new \DateTime($row['expira_em']),
            criadoEm: isset($row['criado_em']) ? new \DateTime($row['criado_em']) : null,
        );
    }

    public function estaValido(): bool
    {
        return new \DateTime() < $this->expiraEm;
    }
}
