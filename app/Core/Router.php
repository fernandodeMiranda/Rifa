<?php

namespace App\Core;

/**
 * Router simples baseado em array de rotas (sem framework).
 * Cada rota mapeia [método HTTP, caminho] -> [Controller::class, 'acao'],
 * com uma lista opcional de middlewares a executar antes do controller.
 */
final class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $action, array $middlewares = []): void
    {
        $this->routes[] = [
            'method'      => strtoupper($method),
            'path'        => $path,
            'action'      => $action,
            'middlewares' => $middlewares,
        ];
    }

    public function get(string $path, array $action, array $middlewares = []): void
    {
        $this->add('GET', $path, $action, $middlewares);
    }

    public function post(string $path, array $action, array $middlewares = []): void
    {
        $this->add('POST', $path, $action, $middlewares);
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            $params = $this->match($route['path'], $path);
            if ($params === null) {
                continue;
            }

            foreach ($route['middlewares'] as $middleware) {
                (new $middleware())->handle();
            }

            [$controllerClass, $action] = $route['action'];
            (new $controllerClass())->$action(...array_values($params));
            return;
        }

        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
    }

    /**
     * Suporta parâmetros dinâmicos simples, ex.: /rifas/{id}
     */
    private function match(string $routePath, string $requestPath): ?array
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));

        if (count($routeParts) !== count($requestParts)) {
            return null;
        }

        $params = [];
        foreach ($routeParts as $i => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $params[trim($part, '{}')] = $requestParts[$i];
            } elseif ($part !== $requestParts[$i]) {
                return null;
            }
        }

        return $params;
    }
}
