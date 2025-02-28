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
            if ($route['method'] === $method && $this->matchPath($route['path'], $url)) {
                $this->debug_log("Route matched: {$route['method']} {$route['path']}");
                return call_user_func($route['callback']);
            }
        }

        $this->debug_log("No matching route found for: {$method} {$url}", 'warning');
        http_response_code(404);
        require_once 'views/404.php';
    }

    private function matchPath($routePath, $requestPath) {
        $routePath = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $routePath = str_replace('/', '\/', $routePath);
        $matched = preg_match('/^' . $routePath . '$/', $requestPath);
        
        if ($matched) {
            $this->debug_log("Path matched: {$requestPath} matches pattern {$routePath}");
        } else {
            $this->debug_log("Path not matched: {$requestPath} does not match pattern {$routePath}", 'debug');
        }
        
        return $matched;
    }
}