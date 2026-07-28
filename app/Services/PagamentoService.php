<?php

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\ComprovanteRepository;
use App\Repositories\NumeroRifaRepository;
use App\Repositories\ReservaRepository;

/**
 * Aprovação/rejeição de comprovantes pelo organizador ou administrador.
 * RN04 - Somente administrador ou organizador podem aprovar pagamentos.
 * RN05 - Após aprovação o número deve passar para o estado de "Pago".
 */
final class PagamentoService
{
    public function __construct(
        private ComprovanteRepository $comprovantes = new ComprovanteRepository(),
        private ReservaRepository $reservas = new ReservaRepository(),
        private NumeroRifaRepository $numeros = new NumeroRifaRepository(),
    ) {
    }

    public function listarPendentes(int $organizadorId): array
    {
        return $this->comprovantes->listarPendentesPorOrganizador($organizadorId);
    }

    public function aprovar(int $comprovanteId, Usuario $analista): void
    {
        $this->garantirPermissao($analista);

        $comprovante = $this->comprovantes->buscarPorId($comprovanteId);
        if (!$comprovante) {
            throw new \RuntimeException('Comprovante não encontrado.');
        }

        $this->comprovantes->atualizarStatus($comprovanteId, 'aprovado', $analista->id);
        $this->reservas->atualizarStatus($comprovante->reservaId, 'confirmada');

        // RN05 - números da reserva passam a "pago".
        $this->numeros->marcarComoPago($comprovante->reservaId);
    }

    public function rejeitar(int $comprovanteId, Usuario $analista, string $motivo): void
    {
        $this->garantirPermissao($analista);

        $comprovante = $this->comprovantes->buscarPorId($comprovanteId);
        if (!$comprovante) {
            throw new \RuntimeException('Comprovante não encontrado.');
        }

        $this->comprovantes->atualizarStatus($comprovanteId, 'rejeitado', $analista->id, $motivo);
        $this->reservas->atualizarStatus($comprovante->reservaId, 'rejeitada');

        // Libera os números para nova tentativa de compra.
        $this->numeros->liberar($comprovante->reservaId);
    }

    private function garantirPermissao(Usuario $usuario): void
    {
        if (!in_array($usuario->tipo, ['administrador', 'organizador'], true)) {
            throw new \RuntimeException('Usuário sem permissão para aprovar pagamentos.');
        }
    }
}
