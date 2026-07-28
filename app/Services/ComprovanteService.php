<?php

namespace App\Services;

use App\Helpers\Upload;
use App\Models\Comprovante;
use App\Repositories\ComprovanteRepository;
use App\Repositories\ReservaRepository;

/**
 * Envio de comprovante de pagamento pelo participante.
 * RN03 - Após o envio do comprovante o número fica aguardando aprovação.
 */
final class ComprovanteService
{
    public function __construct(
        private ComprovanteRepository $comprovantes = new ComprovanteRepository(),
        private ReservaRepository $reservas = new ReservaRepository(),
    ) {
    }

    public function enviar(int $reservaId, int $participanteId, array $arquivo, ?float $valorInformado): Comprovante
    {
        $reserva = $this->reservas->buscarPorId($reservaId);
        if (!$reserva || $reserva->participanteId !== $participanteId) {
            throw new \RuntimeException('Reserva inválida.');
        }
        if ($reserva->status !== 'ativa') {
            throw new \RuntimeException('Esta reserva não está aguardando comprovante.');
        }

        $caminhoArquivo = Upload::salvarComprovante($arquivo);

        $comprovante = new Comprovante(
            id: null,
            reservaId: $reservaId,
            participanteId: $participanteId,
            arquivoPath: $caminhoArquivo,
            valorInformado: $valorInformado,
        );

        $comprovante->id = $this->comprovantes->criar($comprovante);

        // RN03 - reserva passa a aguardar aprovação do organizador/administrador.
        $this->reservas->atualizarStatus($reservaId, 'aguardando_aprovacao');

        return $comprovante;
    }
}
