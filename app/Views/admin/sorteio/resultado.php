<section class="max-w-md bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Resultado do sorteio</h1>

    <?php if ($sorteio): ?>
        <p class="text-sm text-gray-600">Número vencedor (ID interno): <?= $sorteio->numeroVencedorId ?></p>
        <p class="text-sm text-gray-600">Realizado em: <?= date('d/m/Y H:i') ?></p>
        <p class="text-sm text-gray-600">Método: <?= htmlspecialchars($sorteio->metodo) ?></p>
    <?php else: ?>
        <p class="text-gray-500 text-sm">Sorteio ainda não realizado.</p>
    <?php endif; ?>
</section>
