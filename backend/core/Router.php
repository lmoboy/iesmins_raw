<?php
class Router {
    private $routes = [];

    private function debug_log($message, $type = 'info') {
        if (!DEBUG_ROUTER) return;
        
        $log_message = date('[Y-m-d H:i:s]') . " [ROUTER] [{$type}] {$message}\n";
        error_log($log_message, 3, DEBUG_LOG_FILE);
    }

    public function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
        $this->debug_log("Route added: {$method} {$path}");
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = isset($_GET['url']) ? '/' . trim($_GET['url'], '/') : '/';
        
        $this->debug_log("Handling request: {$method} {$url}");

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $params = $this->matchPath($route['path'], $url);
                if ($params !== false) {
                    $this->debug_log("Route matched: {$route['method']} {$route['path']}");
                    return call_user_func($route['callback'], $params);
                }
            }
        }

        $this->debug_log("No matching route found for: {$method} {$url}", 'warning');
        http_response_code(404);
        require_once 'views/404.php';
    }

    private function matchPath($routePath, $requestPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));

        if (count($routeParts) !== count($requestParts)) {
            return false;
        }

        $params = [];
        for ($i = 0; $i < count($routeParts); $i++) {
            if (strpos($routeParts[$i], ':') === 0) {
                $paramName = substr($routeParts[$i], 1);
                $params[$paramName] = $requestParts[$i];
            } elseif ($routeParts[$i] !== $requestParts[$i]) {
                return false;
            }
        }

        return !empty($params) ? $params : ($routePath === $requestPath);
    }
}