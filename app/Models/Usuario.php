<?php

namespace App\Models;

/**
 * Representa um registro da tabela `usuarios`.
 * tipo: participante | organizador | administrador
 */
final class Usuario
{
    public function __construct(
        public ?int $id,
        public string $nome,
        public string $email,
        public string $telefone,
        public string $senhaHash,
        public string $tipo = 'participante',
        public string $status = 'ativo',
        public ?string $criadoEm = null,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            nome: $row['nome'],
            email: $row['email'],
            telefone: $row['telefone'],
            senhaHash: $row['senha_hash'],
            tipo: $row['tipo'] ?? 'participante',
            status: $row['status'] ?? 'ativo',
            criadoEm: $row['criado_em'] ?? null,
        );
    }

    public function isAdministrador(): bool
    {
        return $this->tipo === 'administrador';
    }

    public function isOrganizador(): bool
    {
        return $this->tipo === 'organizador';
    }
}
