<h1 class="text-2xl font-bold mb-6">Usuários</h1>

<div class="bg-white rounded-lg shadow divide-y">
    <?php foreach ($usuarios as $usuario): ?>
        <div class="p-4 flex items-center justify-between text-sm">
            <div>
                <p class="font-medium"><?= htmlspecialchars($usuario->nome) ?></p>
                <p class="text-gray-500"><?= htmlspecialchars($usuario->email) ?></p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100"><?= ucfirst($usuario->tipo) ?></span>

                <?php if ($usuario->tipo === 'participante'): ?>
                    <form method="POST" action="/admin/usuarios/<?= $usuario->id ?>/promover">
                        <button class="text-indigo-600 hover:underline">Promover a organizador</button>
                    </form>
                <?php elseif ($usuario->tipo === 'organizador'): ?>
                    <form method="POST" action="/admin/usuarios/<?= $usuario->id ?>/rebaixar">
                        <button class="text-gray-500 hover:underline">Voltar a participante</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($usuarios)): ?>
        <p class="p-4 text-gray-500 text-sm">Nenhum usuário cadastrado.</p>
    <?php endif; ?>
</div>
