<section class="max-w-lg bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Nova rifa</h1>

    <form method="POST" action="/admin/rifas" class="space-y-4">
        <div>
            <label class="block text-sm mb-1">Título</label>
            <input type="text" name="titulo" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1">Descrição</label>
            <textarea name="descricao" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">Número inicial</label>
                <input type="number" name="numero_inicial" value="0" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Número final</label>
                <input type="number" name="numero_final" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
        </div>
        <div>
            <label class="block text-sm mb-1">Preço por número (R$)</label>
            <input type="number" step="0.01" name="preco_numero" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1">Data do sorteio</label>
            <input type="datetime-local" name="data_sorteio" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded py-2 font-medium hover:bg-indigo-700">
            Salvar como rascunho
        </button>
    </form>
</section>
