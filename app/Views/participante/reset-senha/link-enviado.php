<div class="max-w-md mx-auto mt-20 p-6 bg-green-100 rounded-lg border border-green-300">
    <h1 class="text-2xl font-bold mb-4 text-green-800">✓ Link Enviado</h1>

    <p class="text-green-700 mb-6">
        Um link para redefinir sua senha foi enviado para <strong><?= htmlspecialchars($email) ?></strong>.
    </p>

    <p class="text-sm text-gray-600 mb-6">
        Verifique seu e-mail (incluindo a pasta de spam) e clique no link fornecido.
    </p>

    <!-- Para desenvolvimento/teste: mostrar o link direto -->
    <div class="bg-yellow-50 p-4 rounded border border-yellow-200 mb-4">
        <p class="text-xs text-yellow-700 mb-2">
            <strong>Para teste:</strong> Copie este link e abra no navegador:
        </p>
        <code class="text-xs break-all text-gray-800">
            <?= htmlspecialchars($_SERVER['HTTP_HOST']) ?>/redefinir-senha?token=<?= htmlspecialchars($token) ?>
        </code>
    </div>

    <p class="text-center text-sm">
        <a href="/login" class="text-indigo-600 hover:underline">Voltar ao login</a>
    </p>
</div>
