<?php

/**
 * Job de expiração de reservas (RN02 - expira após 30 minutos).
 * Executar via cron a cada poucos minutos, ex.:
 *   * /5 * * * * php /caminho/para/rifa/scripts/expirar_reservas.php
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Services\ReservaService;

$service = new ReservaService();
$total = $service->expirarReservasVencidas();

echo "[" . date('Y-m-d H:i:s') . "] {$total} reserva(s) expirada(s).\n";
