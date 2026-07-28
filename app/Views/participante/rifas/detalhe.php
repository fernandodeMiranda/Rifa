<h1 class="text-2xl font-bold mb-2"><?= htmlspecialchars($rifa->titulo) ?></h1>
<p class="text-gray-500 mb-6"><?= htmlspecialchars($rifa->descricao ?? '') ?></p>

<form method="POST" action="/rifas/<?= $rifa->id ?>/reservar">
    <div class="grid grid-cols-8 sm:grid-cols-10 gap-2 mb-6">
        <?php foreach ($numeros as $numero): ?>
            <?php
                $cores = [
                    'livre'     => 'bg-white border-gray-300 hover:border-indigo-500 cursor-pointer has-[:checked]:bg-indigo-600 has-[:checked]:text-white has-[:checked]:border-indigo-600 has-[:checked]:font-bold',
                    'reservado' => 'bg-yellow-100 border-yellow-300 cursor-not-allowed',
                    'pago'      => 'bg-green-100 border-green-300 cursor-not-allowed',
                    'cancelado' => 'bg-gray-100 border-gray-300 cursor-not-allowed',
                    'premiado'  => 'bg-indigo-500 text-white border-indigo-600 cursor-not-allowed',
                ];
                $classe = $cores[$numero->status] ?? $cores['livre'];
            ?>
            <label class="border rounded text-center text-xs py-2 select-none <?= $classe ?>">
                <?php if ($numero->status === 'livre'): ?>
                    <input type="checkbox" name="numeros[]" value="<?= $numero->numero ?>" class="hidden">
                <?php endif; ?>
                <?= str_pad((string) $numero->numero, 3, '0', STR_PAD_LEFT) ?>
            </label>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="bg-indigo-600 text-white rounded px-6 py-2 font-medium hover:bg-indigo-700">
        Reservar números selecionados
    </button>
</form>
