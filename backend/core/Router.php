<?php
class Router {
    private $routes = [];

    public function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = isset($_GET['url']) ? '/' . trim($_GET['url'], '/') : '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $url)) {
                return call_user_func($route['callback']);
            }
        }

        http_response_code(404);
        require_once 'views/404.php';
    }

    private function matchPath($routePath, $requestPath) {
        $routePath = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $routePath = str_replace('/', '\/', $routePath);
        return preg_match('/^' . $routePath . '$/', $requestPath);
    }
}