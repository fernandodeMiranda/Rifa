<?php

namespace App\Controllers\Participante;

use App\Core\Controller;
use App\Core\Session;
use App\Helpers\Flash;
use App\Services\ComprovanteService;

final class ComprovanteController extends Controller
{
    public function __construct(private ComprovanteService $comprovanteService = new ComprovanteService())
    {
    }

    // RN03 - após o envio, a reserva fica aguardando aprovação.
    public function store(int $reservaId): void
    {
        $usuario = Session::usuarioLogado();
        $valorInformado = $this->input('valor_informado') !== null
            ? (float) $this->input('valor_informado')
            : null;

        try {
            $this->comprovanteService->enviar($reservaId, $usuario['id'], $_FILES['comprovante'], $valorInformado);
            Flash::sucesso('Comprovante enviado. Aguarde a aprovação do organizador.');
            $this->redirect('/minhas-compras');
        } catch (\Throwable $e) {
            Flash::erro($e->getMessage());
            $this->redirect("/reservas/{$reservaId}/comprovante");
        }
    }
}
