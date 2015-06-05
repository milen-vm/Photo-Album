<?php

class Application {
    private static $instance = null;
    
    private function __construct() {
    }
    
    public function start() {
        require_once 'FrontController.php';
        $router = new FrontController();
        $router->parse();
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Application();
        }

        return self::$instance;
    }
}
