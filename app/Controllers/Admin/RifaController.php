<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Helpers\Flash;
use App\Models\Rifa;
use App\Repositories\RifaRepository;
use App\Services\RifaService;

final class RifaController extends Controller
{
    public function __construct(
        private RifaService $rifaService = new RifaService(),
        private RifaRepository $rifas = new RifaRepository(),
    ) {
    }

    public function index(): void
    {
        $usuario = Session::usuarioLogado();
        $rifas = $this->rifas->listarPorOrganizador($usuario['id']);
        $this->render('admin/rifas/listar', ['rifas' => $rifas], layout: 'admin');
    }

    public function formCriar(): void
    {
        $this->render('admin/rifas/form', [], layout: 'admin');
    }

    public function criar(): void
    {
        $usuario = Session::usuarioLogado();

        $rifa = new Rifa(
            id: null,
            organizadorId: $usuario['id'],
            titulo: $this->input('titulo'),
            descricao: $this->input('descricao'),
            imagemCapa: null,
            precoNumero: (float) $this->input('preco_numero'),
            numeroInicial: (int) $this->input('numero_inicial'),
            numeroFinal: (int) $this->input('numero_final'),
            dataSorteio: $this->input('data_sorteio'),
        );

        $this->rifaService->criar($rifa);

        Flash::sucesso('Rifa criada como rascunho. Publique quando estiver pronta.');
        $this->redirect('/admin/rifas');
    }

    public function publicar(int $id): void
    {
        $this->rifaService->publicar($id);
        Flash::sucesso('Rifa publicada.');
        $this->redirect('/admin/rifas');
    }

    public function encerrar(int $id): void
    {
        $this->rifaService->encerrar($id);
        Flash::sucesso('Rifa encerrada.');
        $this->redirect('/admin/rifas');
    }
}
