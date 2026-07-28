<?php

namespace App\Controllers\Participante;

use App\Core\Controller;
use App\Core\Session;
use App\Helpers\Flash;
use App\Repositories\ReservaRepository;
use App\Services\ReservaService;

final class ReservaController extends Controller
{
    public function __construct(
        private ReservaService $reservaService = new ReservaService(),
        private ReservaRepository $reservas = new ReservaRepository(),
    ) {
    }

    // RN01-RN06 refletidos no status de cada reserva do participante.
    public function minhasCompras(): void
    {
        $usuario = Session::usuarioLogado();
        $reservas = $this->reservas->listarPorParticipante($usuario['id']);

        $this->render('participante/conta/minhas-compras', ['reservas' => $reservas]);
    }

    // Confirma a escolha de números e cria a reserva temporária (RN02).
    public function store(int $rifaId): void
    {
        $usuario = Session::usuarioLogado();
        $numerosEscolhidos = array_map('intval', $this->input('numeros', []));

        try {
            $reserva = $this->reservaService->reservarNumeros($rifaId, $usuario['id'], $numerosEscolhidos);
            $this->redirect("/reservas/{$reserva->id}/comprovante");
        } catch (\RuntimeException $e) {
            Flash::erro($e->getMessage());
            $this->redirect("/rifas/{$rifaId}");
        }
    }

    public function formComprovante(int $reservaId): void
    {
        $this->render('participante/reserva/comprovante', ['reservaId' => $reservaId]);
    }

    public function cancelar(int $reservaId): void
    {
        $this->reservaService->cancelar($reservaId);
        Flash::sucesso('Reserva cancelada. Os números foram liberados.');
        $this->redirect('/rifas');
    }
}
