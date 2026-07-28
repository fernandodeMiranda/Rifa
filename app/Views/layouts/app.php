<?php

use App\Core\Session;
use App\Helpers\Flash;

$usuario = Session::usuarioLogado();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Sistema de Rifa' ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    <header class="bg-white shadow-sm">
        <nav class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/rifas" class="font-bold text-lg text-indigo-600">Rifa Eletrônica</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="/rifas" class="hover:text-indigo-600">Rifas</a>
                <?php if ($usuario): ?>
                    <a href="/minhas-compras" class="hover:text-indigo-600">Minhas compras</a>
                    <a href="/logout" class="hover:text-indigo-600">Sair (<?= htmlspecialchars($usuario['nome']) ?>)</a>
                <?php else: ?>
                    <a href="/login" class="hover:text-indigo-600">Entrar</a>
                    <a href="/cadastro" class="hover:text-indigo-600">Cadastrar</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="flex-1 max-w-5xl w-full mx-auto px-4 py-6">
        <?php if ($msg = Flash::consumirSucesso()): ?>
            <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = Flash::consumirErro()): ?>
            <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2 text-sm"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?php $content(); ?>
    </main>

    <footer class="text-center text-xs text-gray-400 py-4">
        &copy; <?= date('Y') ?> Sistema de Rifa Eletrônica
    </footer>
</body>
</html>
