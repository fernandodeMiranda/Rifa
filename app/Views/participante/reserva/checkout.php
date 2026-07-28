<section class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Resumo da reserva</h1>

    <p class="text-sm text-gray-600">Reserva #<?= $reserva->id ?></p>
    <p class="text-sm text-gray-600">Quantidade de números: <?= $reserva->quantidadeNumeros ?></p>
    <p class="text-sm text-gray-600">Valor total: R$ <?= number_format($reserva->valorTotal, 2, ',', '.') ?></p>
    <p class="text-sm text-red-600 mt-2">
        Reserva expira em <?= date('d/m/Y H:i', strtotime($reserva->expiraEm)) ?> (RN02 — 30 minutos).
    </p>

    <a href="/reservas/<?= $reserva->id ?>/comprovante" class="mt-4 block text-center bg-indigo-600 text-white rounded py-2 font-medium hover:bg-indigo-700">
        Enviar comprovante de pagamento
    </a>
</section>
