<?php

class Database {
    private static $db = null;
    
    private function __construct() {
        $db = new mysqli( SERVER_NAME, USER_NAME, SERVER_PASSWORD, DATABASE_NAME );
        $db->set_charset('utf8');
        
        if ($db->connect_errno) {
            die('Connection to database failed: ' . $db->connect_error);
        }
        
        self::$db = $db;
    }
    
    public static function get_instance() {
        static $instance = null;
        
        if( null === $instance ) {
            $instance = new static();
        }
        
        return $instance;
    }
    
    public static function get_db() {
        return self::$db;
    }
}
