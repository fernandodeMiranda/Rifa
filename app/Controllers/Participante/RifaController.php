<?php

namespace App\Controllers\Participante;

use App\Core\Controller;
use App\Repositories\NumeroRifaRepository;
use App\Repositories\RifaRepository;
use App\Services\RifaService;

final class RifaController extends Controller
{
    public function __construct(
        private RifaRepository $rifas = new RifaRepository(),
        private NumeroRifaRepository $numeros = new NumeroRifaRepository(),
        private RifaService $rifaService = new RifaService(),
    ) {
    }

    // Vitrine de rifas publicadas.
    public function index(): void
    {
        $rifas = $this->rifas->listarPublicadas();

        $indicadores = [];
        foreach ($rifas as $rifa) {
            $indicadores[$rifa->id] = $this->rifaService->dashboard($rifa->id);
        }

        $this->render('participante/rifas/listar', ['rifas' => $rifas, 'indicadores' => $indicadores]);
    }

    // Detalhe da rifa + grade de números (livre/reservado/pago).
    public function show(int $id): void
    {
        $rifa = $this->rifas->buscarPorId($id);
        $numeros = $this->numeros->listarPorRifa($id);

        $this->render('participante/rifas/detalhe', ['rifa' => $rifa, 'numeros' => $numeros]);
    }
}
