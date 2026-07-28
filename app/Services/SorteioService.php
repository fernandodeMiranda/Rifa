<?php

namespace App\Services;

use App\Models\Sorteio;
use App\Repositories\NumeroRifaRepository;
use App\Repositories\RifaRepository;
use App\Repositories\SorteioRepository;

/**
 * Execução do sorteio e divulgação do resultado.
 * RN06 - Somente números pagos participam do sorteio.
 * RN08 - Após o sorteio não é permitido alterar o vencedor.
 */
final class SorteioService
{
    public function __construct(
        private SorteioRepository $sorteios = new SorteioRepository(),
        private NumeroRifaRepository $numeros = new NumeroRifaRepository(),
        private RifaRepository $rifas = new RifaRepository(),
    ) {
    }

    public function executar(int $rifaId, int $realizadoPor): Sorteio
    {
        // RN08 - garante que não existe sorteio anterior para esta rifa.
        if ($this->sorteios->buscarPorRifa($rifaId) !== null) {
            throw new \RuntimeException('Esta rifa já possui um sorteio realizado.');
        }

        $rifa = $this->rifas->buscarPorId($rifaId);
        if (!$rifa) {
            throw new \RuntimeException('Rifa não encontrada.');
        }

        // RN06 - somente números pagos concorrem.
        $numerosPagos = $this->numeros->listarPagosPorRifa($rifaId);
        if (empty($numerosPagos)) {
            throw new \RuntimeException('Nenhum número pago para realizar o sorteio.');
        }

        $semente = bin2hex(random_bytes(16));
        mt_srand(crc32($semente));
        $vencedor = $numerosPagos[array_rand($numerosPagos)];

        $sorteio = new Sorteio(
            id: null,
            rifaId: $rifaId,
            numeroVencedorId: $vencedor->id,
            participanteVencedorId: $vencedor->participanteId,
            realizadoPor: $realizadoPor,
            semente: $semente,
        );

        $sorteio->id = $this->sorteios->criar($sorteio);

        $this->numeros->marcarComoPremiado($vencedor->id);
        $this->rifas->atualizarStatus($rifaId, 'encerrada');

        return $sorteio;
    }

    public function resultado(int $rifaId): ?Sorteio
    {
        return $this->sorteios->buscarPorRifa($rifaId);
    }
}
