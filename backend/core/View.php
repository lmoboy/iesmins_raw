<?php
class View {
    private static $layout = 'default';
    private static $data = [];

    public static function setLayout($layout) {
        self::$layout = $layout;
    }

    public static function render($view, $data = []) {
        self::$data = array_merge(self::$data, $data);
        
        // Start output buffering
        ob_start();
        
        // Extract data to make variables available in view
        extract(self::$data);
        
        // Include the view file
        $viewFile = 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("View file {$viewFile} not found");
        }
        
        // Get the view content
        $content = ob_get_clean();
        
        // Render the layout with the view content
        $layoutFile = 'views/layouts/' . self::$layout . '.php';
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            echo $content;
        }
    }

    public static function partial($partial, $data = []) {
        extract($data);
        $partialFile = 'views/partials/' . $partial . '.php';
        if (file_exists($partialFile)) {
            require_once $partialFile;
        } else {
            throw new Exception("Partial {$partialFile} not found");
        }
    }

    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}