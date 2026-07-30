<section class="max-w-sm mx-auto mt-20 bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Acesso do organizador</h1>
    <form method="POST" action="/admin/login" class="space-y-4">
        <div>
            <label class="block text-sm mb-1">E-mail</label>
            <input type="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1">Senha</label>
            <input type="password" name="senha" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-gray-900 text-white rounded py-2 font-medium hover:bg-gray-800">
            Entrar
        </button>
    </form>
</section>
