<?php

namespace App\Core;

/**
 * Controller base: helpers de renderização de view e redirecionamento,
 * usados por todos os controllers de Participante e Admin.
 */
abstract class Controller
{
    protected function render(string $view, array $data = [], ?string $layout = 'app'): void
    {
        extract($data);
        $viewPath = __DIR__ . "/../Views/{$view}.php";

        if ($layout !== null) {
            $content = function () use ($viewPath, $data) {
                extract($data);
                require $viewPath;
            };
            require __DIR__ . "/../Views/layouts/{$layout}.php";
            return;
        }

        require $viewPath;
    }

    protected function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
