<h1 class="text-2xl font-bold mb-6">Pagamentos pendentes</h1>

<div class="bg-white rounded-lg shadow divide-y divide-gray-300">
    <?php foreach ($pendentes as $comprovante): ?>
        <div class="p-4 flex items-center justify-between text-sm">
            <div>
                <p class="font-medium">Comprovante #<?= $comprovante->id ?> — Reserva #<?= $comprovante->reservaId ?> - <?= htmlspecialchars($comprovante->rifaTitulo ?? '') ?></p>
                <a href="/uploads/comprovantes/<?= htmlspecialchars($comprovante->arquivoPath) ?>" target="_blank" class="text-indigo-600 hover:underline">
                    Ver arquivo enviado
                </a>
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="/admin/pagamentos/<?= $comprovante->id ?>/aprovar">
                    <button class="bg-green-600 text-white rounded px-3 py-1 hover:bg-green-700">Aprovar</button>
                </form>
                <form method="POST" action="/admin/pagamentos/<?= $comprovante->id ?>/rejeitar">
                    <input type="hidden" name="motivo" value="Comprovante não confere">
                    <button class="bg-red-600 text-white rounded px-3 py-1 hover:bg-red-700">Rejeitar</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($pendentes)): ?>
        <p class="p-4 text-gray-500 text-sm">Nenhum comprovante pendente.</p>
    <?php endif; ?>
</div>
