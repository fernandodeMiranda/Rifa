<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php foreach ($rifas as $rifa): ?>
        <?php $ind = $indicadores[$rifa->id]; ?>
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold mb-3"><?= htmlspecialchars($rifa->titulo) ?></h2>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="bg-gray-50 rounded p-2">
                    <p class="text-gray-500">Vendidos</p>
                    <p class="font-bold text-lg"><?= $ind['quantidade_vendida'] ?></p>
                </div>
                <div class="bg-gray-50 rounded p-2">
                    <p class="text-gray-500">Livres</p>
                    <p class="font-bold text-lg"><?= $ind['quantidade_livre'] ?></p>
                </div>
                <div class="bg-gray-50 rounded p-2">
                    <p class="text-gray-500">Receita</p>
                    <p class="font-bold text-lg">R$ <?= number_format($ind['receita'], 2, ',', '.') ?></p>
                </div>
                <div class="bg-gray-50 rounded p-2">
                    <p class="text-gray-500">% vendido</p>
                    <p class="font-bold text-lg"><?= $ind['percentual_vendido'] ?>%</p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($rifas)): ?>
        <p class="text-gray-500">Nenhuma rifa cadastrada ainda.</p>
    <?php endif; ?>
</div>
