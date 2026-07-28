<section class="max-w-sm mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Criar conta</h1>
    <form method="POST" action="/cadastro" class="space-y-4">
        <div>
            <label class="block text-sm mb-1">Nome</label>
            <input type="text" name="nome" required class="w-full border rounded px-3 py-2">
            <?php if (!empty($erros['nome'])): ?><p class="text-xs text-red-600"><?= $erros['nome'] ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm mb-1">E-mail</label>
            <input type="email" name="email" required class="w-full border rounded px-3 py-2">
            <?php if (!empty($erros['email'])): ?><p class="text-xs text-red-600"><?= $erros['email'] ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm mb-1">Telefone</label>
            <input type="text" name="telefone" required class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1">Senha</label>
            <input type="password" name="senha" required class="w-full border rounded px-3 py-2">
            <?php if (!empty($erros['senha'])): ?><p class="text-xs text-red-600"><?= $erros['senha'] ?></p><?php endif; ?>
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded py-2 font-medium hover:bg-indigo-700">
            Cadastrar
        </button>
    </form>
    <p class="text-sm text-center mt-4">
        Já tem conta? <a href="/login" class="text-indigo-600">Entrar</a>
    </p>
</section>
