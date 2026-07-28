<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Flash;
use App\Services\UsuarioService;

/**
 * Gestão de usuários — restrita a administrador (ver rota em app/routes.php).
 * Permite promover um participante a organizador para que ele possa
 * criar e gerenciar rifas.
 */
final class UsuarioController extends Controller
{
    public function __construct(private UsuarioService $usuarioService = new UsuarioService())
    {
    }

    public function index(): void
    {
        $usuarios = $this->usuarioService->listarTodos();
        $this->render('admin/usuarios/listar', ['usuarios' => $usuarios], layout: 'admin');
    }

    public function promover(int $id): void
    {
        try {
            $this->usuarioService->promoverAOrganizador($id);
            Flash::sucesso('Usuário promovido a organizador.');
        } catch (\RuntimeException $e) {
            Flash::erro($e->getMessage());
        }

        $this->redirect('/admin/usuarios');
    }

    public function rebaixar(int $id): void
    {
        try {
            $this->usuarioService->rebaixarAParticipante($id);
            Flash::sucesso('Usuário voltou a ser participante.');
        } catch (\RuntimeException $e) {
            Flash::erro($e->getMessage());
        }

        $this->redirect('/admin/usuarios');
    }
}
