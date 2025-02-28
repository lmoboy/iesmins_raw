<?php
class View {
    private static $layout = 'default';
    private static $data = [];

    private static function debug_log($message, $type = 'info') {
        if (!DEBUG_VIEW) return;
        
        $log_message = date('[Y-m-d H:i:s]') . " [VIEW] [{$type}] {$message}\n";
        error_log($log_message, 3, DEBUG_LOG_FILE);
    }

    public static function setLayout($layout) {
        self::$layout = $layout;
        self::debug_log("Layout set to: {$layout}");
    }

    public static function render($view, $data = []) {
        self::debug_log("Rendering view: {$view}");
        self::$data = array_merge(self::$data, $data);
        
        if (!empty($data)) {
            self::debug_log("View data: " . json_encode($data));
        }
        
        // Start output buffering
        ob_start();
        
        // Extract data to make variables available in view
        extract(self::$data);
        
        // Include the view file
        $viewFile = 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            self::debug_log("Loading view file: {$viewFile}");
            require_once $viewFile;
        } else {
            $error = "View file {$viewFile} not found";
            self::debug_log($error, 'error');
            throw new Exception($error);
        }
        
        // Get the view content
        $content = ob_get_clean();
        
        // Render the layout with the view content
        $layoutFile = 'views/layouts/' . self::$layout . '.php';
        if (file_exists($layoutFile)) {
            self::debug_log("Loading layout: {$layoutFile}");
            require_once $layoutFile;
        } else {
            self::debug_log("No layout found, outputting raw content", 'warning');
            echo $content;
        }
    }

    public static function partial($partial, $data = []) {
        self::debug_log("Loading partial: {$partial}");
        if (!empty($data)) {
            self::debug_log("Partial data: " . json_encode($data));
        }
        
        extract($data);
        $partialFile = 'views/partials/' . $partial . '.php';
        if (file_exists($partialFile)) {
            self::debug_log("Including partial file: {$partialFile}");
            require_once $partialFile;
        } else {
            $error = "Partial {$partialFile} not found";
            self::debug_log($error, 'error');
            throw new Exception($error);
        }
    }

    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}