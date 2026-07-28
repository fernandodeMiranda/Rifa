<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Minhas rifas</h1>
    <a href="/admin/rifas/nova" class="bg-indigo-600 text-white rounded px-4 py-2 text-sm font-medium hover:bg-indigo-700">
        + Nova rifa
    </a>
</div>

<div class="bg-white rounded-lg shadow divide-y">
    <?php foreach ($rifas as $rifa): ?>
        <div class="p-4 flex items-center justify-between text-sm">
            <div>
                <p class="font-medium"><?= htmlspecialchars($rifa->titulo) ?></p>
                <p class="text-gray-500">Sorteio em <?= date('d/m/Y H:i', strtotime($rifa->dataSorteio)) ?></p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100"><?= ucfirst($rifa->status) ?></span>
                <?php if ($rifa->status === 'rascunho'): ?>
                    <form method="POST" action="/admin/rifas/<?= $rifa->id ?>/publicar">
                        <button class="text-indigo-600 hover:underline">Publicar</button>
                    </form>
                <?php endif; ?>
                <?php if ($rifa->status === 'publicada'): ?>
                    <form method="POST" action="/admin/rifas/<?= $rifa->id ?>/encerrar">
                        <button class="text-red-600 hover:underline">Encerrar</button>
                    </form>
                    <a href="/admin/rifas/<?= $rifa->id ?>/sorteio" class="text-gray-700 hover:underline">Sortear</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($rifas)): ?>
        <p class="p-4 text-gray-500 text-sm">Nenhuma rifa criada ainda.</p>
    <?php endif; ?>
</div>
