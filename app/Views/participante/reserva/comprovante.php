<section class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Enviar comprovante</h1>

    <form method="POST" action="/reservas/<?= $reservaId ?>/comprovante" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm mb-1">Comprovante (imagem ou PDF)</label>
            <input type="file" name="comprovante" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-sm">
        </div>
        <div>
            <label class="block text-sm mb-1">Valor pago (opcional)</label>
            <input type="number" step="0.01" name="valor_informado" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded py-2 font-medium hover:bg-indigo-700">
            Enviar comprovante
        </button>
    </form>

    <p class="text-xs text-gray-400 mt-4">
        RN03 — Após o envio, o número aguarda aprovação do organizador.
    </p>
</section>
