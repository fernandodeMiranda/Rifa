<?php

/**
 * Definição de todas as rotas da aplicação.
 * Recebe $router (App\Core\Router) já instanciado pelo front controller.
 */

use App\Controllers\Admin\AuthController as AdminAuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\PagamentoController;
use App\Controllers\Admin\RifaController as AdminRifaController;
use App\Controllers\Admin\SorteioController;
use App\Controllers\Admin\UsuarioController;
use App\Controllers\Participante\AuthController;
use App\Controllers\Participante\ComprovanteController;
use App\Controllers\Participante\ReservaController;
use App\Controllers\Participante\ResetSenhaController;
use App\Controllers\Participante\RifaController;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\OrganizadorMiddleware;

/** @var \App\Core\Router $router */

// ---- Participante ----------------------------------------------------
$router->get('/cadastro', [AuthController::class, 'formCadastro']);
$router->post('/cadastro', [AuthController::class, 'cadastrar']);
$router->get('/login', [AuthController::class, 'formLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/esqueci-senha', [ResetSenhaController::class, 'formSolicitar']);
$router->post('/esqueci-senha', [ResetSenhaController::class, 'solicitar']);
$router->get('/redefinir-senha', [ResetSenhaController::class, 'formRedefinir']);
$router->post('/redefinir-senha', [ResetSenhaController::class, 'redefinir']);

$router->get('/rifas', [RifaController::class, 'index']);
$router->get('/rifas/{id}', [RifaController::class, 'show']);

$router->get('/minhas-compras', [ReservaController::class, 'minhasCompras'], [AuthMiddleware::class]);
$router->post('/rifas/{rifaId}/reservar', [ReservaController::class, 'store'], [AuthMiddleware::class]);
$router->get('/reservas/{reservaId}/comprovante', [ReservaController::class, 'formComprovante'], [AuthMiddleware::class]);
$router->post('/reservas/{reservaId}/cancelar', [ReservaController::class, 'cancelar'], [AuthMiddleware::class]);
$router->post('/reservas/{reservaId}/comprovante', [ComprovanteController::class, 'store'], [AuthMiddleware::class]);

// ---- Admin / Organizador ----------------------------------------------
$router->get('/admin/login', [AdminAuthController::class, 'formLogin']);
$router->post('/admin/login', [AdminAuthController::class, 'login']);
$router->get('/admin/logout', [AdminAuthController::class, 'logout']);

$router->get('/admin/dashboard', [DashboardController::class, 'index'], [OrganizadorMiddleware::class]);

// Gestão de usuários — só administrador pode promover/rebaixar organizadores.
$router->get('/admin/usuarios', [UsuarioController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/usuarios/{id}/promover', [UsuarioController::class, 'promover'], [AdminMiddleware::class]);
$router->post('/admin/usuarios/{id}/rebaixar', [UsuarioController::class, 'rebaixar'], [AdminMiddleware::class]);

$router->get('/admin/rifas', [AdminRifaController::class, 'index'], [OrganizadorMiddleware::class]);
$router->get('/admin/rifas/nova', [AdminRifaController::class, 'formCriar'], [OrganizadorMiddleware::class]);
$router->post('/admin/rifas', [AdminRifaController::class, 'criar'], [OrganizadorMiddleware::class]);
$router->post('/admin/rifas/{id}/publicar', [AdminRifaController::class, 'publicar'], [OrganizadorMiddleware::class]);
$router->post('/admin/rifas/{id}/encerrar', [AdminRifaController::class, 'encerrar'], [OrganizadorMiddleware::class]);

$router->get('/admin/rifas/{rifaId}/sorteio', [SorteioController::class, 'formExecutar'], [OrganizadorMiddleware::class]);
$router->post('/admin/rifas/{rifaId}/sorteio', [SorteioController::class, 'executar'], [OrganizadorMiddleware::class]);
$router->get('/admin/rifas/{rifaId}/sorteio/resultado', [SorteioController::class, 'resultado'], [OrganizadorMiddleware::class]);

$router->get('/admin/pagamentos', [PagamentoController::class, 'index'], [OrganizadorMiddleware::class]);
$router->post('/admin/pagamentos/{comprovanteId}/aprovar', [PagamentoController::class, 'aprovar'], [OrganizadorMiddleware::class]);
$router->post('/admin/pagamentos/{comprovanteId}/rejeitar', [PagamentoController::class, 'rejeitar'], [OrganizadorMiddleware::class]);
