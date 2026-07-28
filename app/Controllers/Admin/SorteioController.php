<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Helpers\Flash;
use App\Services\SorteioService;

final class SorteioController extends Controller
{
    public function __construct(private SorteioService $sorteioService = new SorteioService())
    {
    }

    public function formExecutar(int $rifaId): void
    {
        $this->render('admin/sorteio/executar', ['rifaId' => $rifaId], layout: 'admin');
    }

    // RN06/RN08
    public function executar(int $rifaId): void
    {
        $usuario = Session::usuarioLogado();

        try {
            $this->sorteioService->executar($rifaId, $usuario['id']);
            Flash::sucesso('Sorteio realizado com sucesso.');
        } catch (\RuntimeException $e) {
            Flash::erro($e->getMessage());
        }

        $this->redirect("/admin/rifas/{$rifaId}/sorteio/resultado");
    }

    public function resultado(int $rifaId): void
    {
        $sorteio = $this->sorteioService->resultado($rifaId);
        $this->render('admin/sorteio/resultado', ['sorteio' => $sorteio], layout: 'admin');
    }
}
