<?php

abstract class BaseModel {
    protected $db;
    
    public function __construct() {
        if ($this->db == null) {
            $this->db = new mysqli(SERVER_NAME, USER_NAME, SERVER_PASSWORD, DATABASE_NAME);
            $this->db->set_charset('utf8');
            $this->db->connect_errno;
            if ($this->db->connect_errno) {
                die('Connection to database failed: ' . self::$db->connect_error);
            }
        }
    }
}
