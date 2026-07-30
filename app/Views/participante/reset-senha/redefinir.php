<div class="max-w-md mx-auto mt-20 p-6 bg-white rounded-lg border border-gray-300">
    <h1 class="text-2xl font-bold mb-6 text-center">Redefinir Senha</h1>

    <form method="POST" action="/redefinir-senha" class="space-y-4">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>" />

        <div>
            <label for="nova_senha" class="block text-sm font-medium mb-2">Nova Senha</label>
            <input
                type="password"
                id="nova_senha"
                name="nova_senha"
                required
                minlength="6"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                placeholder="••••••"
            />
            <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
        </div>

        <div>
            <label for="confirmacao" class="block text-sm font-medium mb-2">Confirmar Senha</label>
            <input
                type="password"
                id="confirmacao"
                name="confirmacao"
                required
                minlength="6"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                placeholder="••••••"
            />
        </div>

        <button
            type="submit"
            class="w-full bg-indigo-600 text-white font-medium py-2 rounded-lg hover:bg-indigo-700 transition"
        >
            Redefinir Senha
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-4">
        <a href="/login" class="text-indigo-600 hover:underline">Voltar ao login</a>
    </p>
</div>
