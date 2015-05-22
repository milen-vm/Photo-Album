<?php

abstract class BaseController {
    protected $controller_name;
    protected $layout_name = DEFAULT_LAYOUT;
    protected $is_view_rendered = false;
    protected $is_post = false;
    
    public function __construct($controller_name) {
        $this->controller_name = $controller_name;
        $this->title = ucfirst($controller_name);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->is_post = true;
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
                include_once $footer_file_name;
            }
        }
        
        $this->is_view_rendered = true;
    }
    
    public function redirectToUrl($url) {
        header('Location: ' . $url);
        die;
    }
    
    public function redirect($controller_Name, $action_name = null, array $params = null) {
        $url = '/' . urlencode($controller_Name);    // '/' . DX_ROOT_PATH . urlencode($controller_Name);
        if ($action_name != null) {
            $url .= '/' . urlencode($action_name);
        }
        
        if ($params != null) {
            $encoded_params = array_map('urlencode', $params);
            $url .= '/' . implode('/', $encoded_params);
        }

        $this->redirectToUrl($url);
    }
    
    public function isLoggedIn() {
        if (isset( $_SESSION['user_name'])) {
            return true;
        }

        return false;
    }
    
    public function getUsername() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user_name'];
        }

        return null;
    }
    
    public function getFullName() {
        if ($this->isLoggedIn()) {
            return $_SESSION['full_name'];
        }

        return null;
    }
    
    public function getUserId() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user_id'];
        }

        return null;
    }
    
    public function authorize() {
        if (!$this->isLoggedIn()) {
            $this->addErrorMessage('Please login first.');
            $this->redirect('account', 'login');
        }
    }
    
    public function addInfoMessage($msg) {
        $this->addMessage($msg, 'info');
    }

    public function addErrorMessage($msg) {
        $this->addMessage($msg, 'warning');
    }

    public function addMessage($msg, $type) {
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        }
        
        array_push($_SESSION['messages'],
            array('text' => $msg, 'type' => $type));
    }
    
    public function makeBase64($images, $main_path) {
        $read_errors = 0;
        $images_data = array();
        foreach ($images as $image) {
            $path = $main_path;
            if (isset($image['album_id'])) {
                $path .= $image['album_id'] . D_S . THUMBS_DIR_NAME . D_S;
            }

            $path .= $image['name'] . '.' . $image['type'];
            if (is_readable($path)) {
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $image['type'] . ';base64,' . base64_encode($data);
                $id = $image['id'];
                $images_data[$id] = $base64;
            } else {
                ++$read_errors;
            }
        }
        
        if ($read_errors > 0) {
            $this->addErrorMessage("Something goes wrong. There is $read_errors broken images.");
        }
        // TODO Do something if $images_data is empty
        return $images_data;
    }
    
    
}
