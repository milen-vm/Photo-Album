<?php

abstract class BaseModel {
    protected static $db;
    
    public function __construct() {
        if (self::$db == null) {
            self::$db = new mysqli(SERVER_NAME, USER_NAME, SERVER_PASSWORD, DATABASE_NAME);
            self::$db->set_charset('utf8');
            if (self::$db->connect_errno) {
                die('Connection to database failed: ' . self::$db->connect_error);
            }
        }
    }
}
