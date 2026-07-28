<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Repositories\RifaRepository;
use App\Services\RifaService;

final class DashboardController extends Controller
{
    public function __construct(
        private RifaRepository $rifas = new RifaRepository(),
        private RifaService $rifaService = new RifaService(),
    ) {
    }

    // Vendidos / livres / receita / % vendido por rifa do organizador logado.
    public function index(): void
    {
        $usuario = Session::usuarioLogado();
        $rifas = $this->rifas->listarPorOrganizador($usuario['id']);

        $indicadores = [];
        foreach ($rifas as $rifa) {
            $indicadores[$rifa->id] = $this->rifaService->dashboard($rifa->id);
        }

        $this->render('admin/dashboard/index', ['rifas' => $rifas, 'indicadores' => $indicadores], layout: 'admin');
    }
}
