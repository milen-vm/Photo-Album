<?php

abstract class BaseController {
    protected $controller_name;
    protected $layout_name = DEFAULT_LAYOUT;
    protected $is_view_rendered = false;
    protected $is_post = false;
    
    public function __construct($controller_name) {
        $this->controller_name = $controller_name;
        $this->title = $controller_name;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->$is_post = true;
        }
    }
    
    public function index() {
        // Implement the default action in the subclasses
    }
    
    public function renderView($view_name = 'index', $include_layout = true) {
        if (!$this->is_view_rendered) {
            $view_file_name = DX_ROOT_DIR . 'views/' . $this->controller_name . '/' .
                $view_name . '.php';
            if ($include_layout) {
                $header_file_name = DX_ROOT_DIR . 'views/layouts/' . $this->layout_name .
                    '/header.php';
                include_once $header_file_name;
            }
            
            include_once $view_file_name;
            if ($include_layout) {
                $footer_file_name = DX_ROOT_DIR . 'views/layouts/' . $this->layout_name .
                    '/footer.php';
            }
        }
        
        $this->is_view_rendered = true;
    }
    
}
