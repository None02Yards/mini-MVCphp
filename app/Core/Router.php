

<?php
// app/Core/Router.php

class Router
{
    private array $routes = [
        'GET'  => [],
        'POST' => []
    ];

    public function get(string $uri, string $action): void
    {
        $this->routes['GET'][$this->normalize($uri)] = $action;
    }

    public function post(string $uri, string $action): void
    {
        $this->routes['POST'][$this->normalize($uri)] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $this->normalize(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            return;
        }

        [$controller, $methodName] = explode('@', $this->routes[$method][$uri]);

        $controllerFile = CONTROLLER_PATH . "/$controller.php";

        if (!file_exists($controllerFile)) {
            throw new Exception("Controller $controller not found");
        }

        require_once $controllerFile;

        if (!class_exists($controller)) {
            throw new Exception("Controller class $controller not defined");
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $methodName)) {
            throw new Exception("Method $methodName not found in controller $controller");
        }

        call_user_func([$controllerInstance, $methodName]);
    }

    private function normalize(string $uri): string
    {
        return rtrim($uri, '/') ?: '/';
    }
}
