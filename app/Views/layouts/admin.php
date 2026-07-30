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
    <title><?= $titulo ?? 'Painel Administrativo' ?> — Rifa</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-gray-200 text-gray-800 min-h-screen flex">

    <aside class="w-56 bg-gray-900 text-gray-200 flex flex-col">
        <div class="px-4 py-4 font-bold text-lg border-b border-gray-800">Painel</div>
        <nav class="flex-1 px-2 py-4 space-y-1 text-sm">
            <a href="/admin/dashboard" class="block px-3 py-2 rounded hover:bg-gray-800">Dashboard</a>
            <a href="/admin/rifas" class="block px-3 py-2 rounded hover:bg-gray-800">Rifas</a>
            <a href="/admin/pagamentos" class="block px-3 py-2 rounded hover:bg-gray-800">Pagamentos</a>
            <?php if ($usuario && $usuario['tipo'] === 'administrador'): ?>
                <a href="/admin/usuarios" class="block px-3 py-2 rounded hover:bg-gray-800">Usuários</a>
            <?php endif; ?>
        </nav>
        <div class="px-4 py-4 border-t border-gray-800 text-xs">
            <?php if ($usuario): ?>
                <p class="mb-2"><?= htmlspecialchars($usuario['nome']) ?> (<?= $usuario['tipo'] ?>)</p>
                <a href="/admin/logout" class="text-gray-400 hover:text-white">Sair</a>
            <?php endif; ?>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <main class="flex-1 px-6 py-6">
            <?php if ($msg = Flash::consumirSucesso()): ?>
                <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            <?php if ($msg = Flash::consumirErro()): ?>
                <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2 text-sm"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>

            <?php $content(); ?>
        </main>
    </div>
</body>
</html>
