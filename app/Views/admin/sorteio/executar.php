<section class="max-w-md bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Realizar sorteio</h1>
    <p class="text-sm text-gray-500 mb-4">
        Apenas números com pagamento aprovado (RN06) participam. Esta ação não pode ser desfeita (RN08).
    </p>

    <form method="POST" action="/admin/rifas/<?= $rifaId ?>/sorteio">
        <button type="submit" class="w-full bg-gray-900 text-white rounded py-2 font-medium hover:bg-gray-800">
            Confirmar e sortear
        </button>
    </form>
</section>
