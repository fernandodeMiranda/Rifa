<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Helpers\Flash;
use App\Repositories\UsuarioRepository;
use App\Services\PagamentoService;

final class PagamentoController extends Controller
{
    public function __construct(
        private PagamentoService $pagamentoService = new PagamentoService(),
        private UsuarioRepository $usuarios = new UsuarioRepository(),
    ) {
    }

    public function index(): void
    {
        $usuario = Session::usuarioLogado();
        $pendentes = $this->pagamentoService->listarPendentes($usuario['id']);
        $this->render('admin/pagamentos/listar', ['pendentes' => $pendentes], layout: 'admin');
    }

    // RN04/RN05
    public function aprovar(int $comprovanteId): void
    {
        $usuario = $this->usuarios->buscarPorId(Session::usuarioLogado()['id']);

        try {
            $this->pagamentoService->aprovar($comprovanteId, $usuario);
            Flash::sucesso('Pagamento aprovado. Números marcados como pagos.');
        } catch (\RuntimeException $e) {
            Flash::erro($e->getMessage());
        }

        $this->redirect('/admin/pagamentos');
    }

    public function rejeitar(int $comprovanteId): void
    {
        $usuario = $this->usuarios->buscarPorId(Session::usuarioLogado()['id']);
        $motivo = $this->input('motivo', '');

        try {
            $this->pagamentoService->rejeitar($comprovanteId, $usuario, $motivo);
            Flash::sucesso('Comprovante rejeitado. Números liberados.');
        } catch (\RuntimeException $e) {
            Flash::erro($e->getMessage());
        }

        $this->redirect('/admin/pagamentos');
    }
}
