<section class="max-w-sm mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Entrar</h1>
    <form method="POST" action="/login" class="space-y-4">
        <div>
            <label class="block text-sm mb-1">E-mail</label>
            <input type="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1">Senha</label>
            <input type="password" name="senha" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded py-2 font-medium hover:bg-indigo-700">
            Entrar
        </button>
    </form>
    <p class="text-sm text-center mt-4">
        Não tem conta? <a href="/cadastro" class="text-indigo-600 hover:underline">Cadastre-se</a>
    </p>
    <p class="text-sm text-center mt-2">
        <a href="/esqueci-senha" class="text-indigo-600 hover:underline">Esqueci minha senha</a>
    </p>
</section>
