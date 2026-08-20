<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Router
{
    /** @var array<string, array<string, array{handler: array{0: class-string, 1: string}, middleware: list<class-string>}>> */
    private array $routes = [];

    /** @param array{0: class-string, 1: string} $handler */
    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    /** @param array{0: class-string, 1: string} $handler */
    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    /** @param array{0: class-string, 1: string} $handler */
    private function add(string $method, string $path, array $handler, array $middleware): void
    {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = $this->requestPath();
        
        // Debug incoming route matching
        error_log("Router dispatch: Method = " . $method . ", Path = " . $path);

        $route = $this->routes[$method][$path] ?? null;

        if ($route === null) {
            error_log("Router 404 mismatch: Method = " . $method . ", Path = " . $path);
            http_response_code(404);
            View::render('errors/404', ['title' => 'Page not found'], 'auth');
            return;
        }

        foreach ($route['middleware'] as $middleware) {
            (new $middleware())->handle();
        }

        [$controllerClass, $controllerMethod] = $route['handler'];
        $controller = new $controllerClass();

        if (!method_exists($controller, $controllerMethod)) {
            throw new RuntimeException('Route handler is not available.');
        }

        $controller->{$controllerMethod}();
    }

    private function requestPath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        if (str_ends_with($scriptName, '.php')) {
            $scriptDirectory = str_replace('\\', '/', dirname($scriptName));
            if ($scriptDirectory !== '/' && $scriptDirectory !== '.' && str_starts_with($path, $scriptDirectory)) {
                $path = substr($path, strlen($scriptDirectory));
            }
        }

        return '/' . trim($path, '/');
    }
}
