<h1 class="text-2xl font-bold mb-6">Rifas disponíveis</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    <?php foreach ($rifas as $rifa): ?>
        <?php $percentual = $indicadores[$rifa->id]['percentual_vendido'] ?? 0; ?>
        <a href="/rifas/<?= $rifa->id ?>" class="block bg-white rounded-lg shadow hover:shadow-md transition p-4">
            <h2 class="font-semibold text-lg"><?= htmlspecialchars($rifa->titulo) ?></h2>
            <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($rifa->descricao ?? '') ?></p>
            <p class="mt-3 text-indigo-600 font-medium">R$ <?= number_format($rifa->precoNumero, 2, ',', '.') ?> / número</p>
            <p class="text-xs text-gray-400 mt-1">Sorteio em <?= date('d/m/Y H:i', strtotime($rifa->dataSorteio)) ?></p>

            <div class="mt-3">
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                    <span>Vendido</span>
                    <span class="font-medium text-gray-700"><?= number_format($percentual, 2, ',', '.') ?>%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: <?= min(100, $percentual) ?>%"></div>
                </div>
            </div>
        </a>
    <?php endforeach; ?>

    <?php if (empty($rifas)): ?>
        <p class="text-gray-500">Nenhuma rifa publicada no momento.</p>
    <?php endif; ?>
</div>
