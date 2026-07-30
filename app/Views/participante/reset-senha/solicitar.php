<div class="max-w-md mx-auto mt-20 p-6 bg-white rounded-lg border border-gray-300">
    <h1 class="text-2xl font-bold mb-6 text-center">Esqueci Minha Senha</h1>

    <form method="POST" action="/esqueci-senha" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium mb-2">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                placeholder="seu@email.com"
            />
        </div>

        <button
            type="submit"
            class="w-full bg-indigo-600 text-white font-medium py-2 rounded-lg hover:bg-indigo-700 transition"
        >
            Enviar Link de Reset
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-4">
        Lembrou a senha?
        <a href="/login" class="text-indigo-600 hover:underline">Faça login</a>
    </p>
</div>
