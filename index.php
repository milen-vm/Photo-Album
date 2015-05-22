<?php
require_once 'Application.php';
require_once 'includes/config.php';

define('DX_ROOT_DIR', dirname(__FILE__) . '/');
define('DX_ROOT_PATH', basename(dirname(__FILE__)) . '/');      // contains 'Photo-album/'
// define( 'ROOT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . DX_ROOT_PATH );   // http://localhost/Photo-Album/
define( 'ROOT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');

session_start();
$app = Application::getInstance();
$app->start();

function __autoload($class_name) {
    if (file_exists("controllers/$class_name.php")) {
        include "controllers/$class_name.php";
    }
    
    if (file_exists("models/$class_name.php")) {
        include "models/$class_name.php";
    }
    
    if (file_exists("libs/$class_name.php")) {
        include "libs/$class_name.php";
    }
}