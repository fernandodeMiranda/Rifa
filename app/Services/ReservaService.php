<?php

namespace App\Services;

use App\Models\Reserva;
use App\Repositories\NumeroRifaRepository;
use App\Repositories\RifaRepository;
use App\Repositories\ReservaRepository;

/**
 * Escolha e reserva temporária de números.
 * RN02 - Uma reserva expira automaticamente após 30 minutos.
 */
final class ReservaService
{
    public function __construct(
        private ReservaRepository $reservas = new ReservaRepository(),
        private NumeroRifaRepository $numeros = new NumeroRifaRepository(),
        private RifaRepository $rifas = new RifaRepository(),
        private int $minutosExpiracao = 30,
    ) {
    }

    public function reservarNumeros(int $rifaId, int $participanteId, array $numerosEscolhidos): Reserva
    {
        if (empty($numerosEscolhidos)) {
            throw new \RuntimeException('Selecione ao menos um número para reservar.');
        }

        $rifa = $this->rifas->buscarPorId($rifaId);
        if (!$rifa || $rifa->status !== 'publicada') {
            throw new \RuntimeException('Rifa indisponível para venda.');
        }

        $disponiveis = $this->numeros->buscarDisponiveis($rifaId, $numerosEscolhidos);
        if (count($disponiveis) !== count($numerosEscolhidos)) {
            throw new \RuntimeException('Um ou mais números escolhidos já não estão livres.');
        }

        $reserva = new Reserva(
            id: null,
            rifaId: $rifaId,
            participanteId: $participanteId,
            quantidadeNumeros: count($numerosEscolhidos),
            valorTotal: count($numerosEscolhidos) * $rifa->precoNumero,
            expiraEm: date('Y-m-d H:i:s', strtotime("+{$this->minutosExpiracao} minutes")),
        );

        $reserva->id = $this->reservas->criar($reserva);

        $numeroIds = array_map(fn ($n) => $n->id, $disponiveis);
        $this->numeros->reservar($numeroIds, $reserva->id, $participanteId);

        return $reserva;
    }

    public function cancelar(int $reservaId): void
    {
        $this->numeros->liberar($reservaId);
        $this->reservas->atualizarStatus($reservaId, 'cancelada');
    }

    // RN02 - Executado periodicamente por scripts/expirar_reservas.php (cron).
    public function expirarReservasVencidas(): int
    {
        $expiradas = $this->reservas->listarExpiradas();

        foreach ($expiradas as $reserva) {
            $this->numeros->liberar($reserva->id);
            $this->reservas->atualizarStatus($reserva->id, 'expirada');
        }

        return count($expiradas);
    }
}
