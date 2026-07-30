<h1 class="text-2xl font-bold mb-6">Minhas compras</h1>

<div class="bg-white rounded-lg shadow divide-y divide-gray-300">
    <?php foreach ($reservas as $reserva): ?>
        <div class="p-4 flex items-center justify-between text-sm">
            <div>
                <p class="font-medium">Reserva #<?= $reserva->id ?> - <?= htmlspecialchars($reserva->rifaTitulo ?? '') ?></p>
                <p class="text-gray-500"><?= $reserva->quantidadeNumeros ?> número(s) — R$ <?= number_format($reserva->valorTotal, 2, ',', '.') ?></p>
            </div>
            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100"><?= ucfirst(str_replace('_', ' ', $reserva->status)) ?></span>
        </div>
    <?php endforeach; ?>

    <?php if (empty($reservas)): ?>
        <p class="p-4 text-gray-500 text-sm">Você ainda não fez nenhuma reserva.</p>
    <?php endif; ?>
</div>
