<?php

class FrontController {
    
    // const DEFAULT_CONTROLLER = "home";
    // const DEFAULT_ACTION = "index";
    
    public function parse() {
        $controller_name = DEFAULT_CONTROLLER;
        $action_name = DEFAULT_ACTION;
        $params = array();
        $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $request_home = '/' . DX_ROOT_PATH;
        
        if (strpos($request, $request_home) === 0) {
            $request = substr($request, strlen($request_home));
        }
        
        if ($request) {
            $request_components = explode('/', $request);
            $request_components = array_values(
                array_filter($request_components, function ($val) {
                    return $val != '';
                }
            ));
            
            if (isset($request_components[0])) {
                $controller_name = $request_components[0];
            }
            
            if (isset($request_components[1])) {
                $action_name = $request_components[1];
            }
            
            if (isset($request_components[2])) {
                $params = array_splice($request_components, 2);
            }
            
            $controller_class_name = ucfirst(strtolower($controller_name)) . 'Controller';
            $controller_file_name = 'controllers/' . $controller_class_name . '.php';
            
            if (class_exists($controller_class_name)) {
                $controller = new $controller_class_name($controller_name, $action_name);
            } else {
                die("Cannot find controller '$controller_name' in class '$controller_file_name'");
            }
            
            if (method_exists($controller, $action_name)) {
                call_user_func_array(array($controller, $action_name), $params);
            } else {
                die("Can not find action '$action_name' in controler '$controller_name'.");
            }
        }
    }
}
