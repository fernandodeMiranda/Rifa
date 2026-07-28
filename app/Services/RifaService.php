<?php

namespace App\Services;

use App\Models\Rifa;
use App\Repositories\NumeroRifaRepository;
use App\Repositories\RifaRepository;

/**
 * Criação, publicação e encerramento de rifas.
 * RN07 - Uma rifa encerrada não pode sofrer alterações.
 */
final class RifaService
{
    public function __construct(
        private RifaRepository $rifas = new RifaRepository(),
        private NumeroRifaRepository $numeros = new NumeroRifaRepository(),
    ) {
    }

    public function criar(Rifa $rifa): Rifa
    {
        $rifa->id = $this->rifas->criar($rifa);

        // Gera automaticamente a grade de números (numero_inicial..numero_final).
        $this->numeros->gerarParaRifa($rifa->id, $rifa->numeroInicial, $rifa->numeroFinal);

        return $rifa;
    }

    public function publicar(int $rifaId): void
    {
        $rifa = $this->buscarOuFalhar($rifaId);
        $this->garantirQuePodeAlterar($rifa);

        $this->rifas->atualizarStatus($rifaId, 'publicada');
    }

    public function encerrar(int $rifaId): void
    {
        $rifa = $this->buscarOuFalhar($rifaId);
        $this->garantirQuePodeAlterar($rifa);

        $this->rifas->atualizarStatus($rifaId, 'encerrada');
    }

    public function dashboard(int $rifaId): array
    {
        $numeros = $this->numeros->listarPorRifa($rifaId);
        $total = count($numeros);
        $vendidos = count(array_filter($numeros, fn ($n) => $n->status === 'pago'));
        $livres = count(array_filter($numeros, fn ($n) => $n->status === 'livre'));

        $rifa = $this->buscarOuFalhar($rifaId);

        return [
            'quantidade_vendida'   => $vendidos,
            'quantidade_livre'     => $livres,
            'receita'              => $vendidos * $rifa->precoNumero,
            'percentual_vendido'   => $total > 0 ? round(($vendidos / $total) * 100, 2) : 0.0,
        ];
    }

    private function buscarOuFalhar(int $rifaId): Rifa
    {
        $rifa = $this->rifas->buscarPorId($rifaId);

        if (!$rifa) {
            throw new \RuntimeException('Rifa não encontrada.');
        }

        return $rifa;
    }

    private function garantirQuePodeAlterar(Rifa $rifa): void
    {
        if (!$rifa->podeSerAlterada()) {
            throw new \RuntimeException('Rifa encerrada não pode sofrer alterações.');
        }
    }
}
