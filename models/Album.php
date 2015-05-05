<?php

class Album {
    private $name;
    private $description;
    private $is_private;
    private $errors = array();
    
    public function __construct($name, $description, $is_private) {
        $this->name = trim($name);
        $this->description = trim($description);
        $this->is_private = $is_private;
    }
        
    public function getName() {
        return $this->name;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function getIsPrivate() {
        return $this->is_private;
    }
    
    public function getErrors() {
        return $this->errors;
    }
        
    public function isValid() {
        $this->validateName();
        $this->validateDescription();
        $this->validateIsPrivate();
        
        return count($this->errors) === 0;
    }
    
    private function validateName() {
        if (empty($this->name)) {
            $this->errors[] = 'Album name is required.';
            return;
        }
        
        if (!preg_match('/^[A-Za-z0-9-_ ]+$/', $this->name)) {
            $this->errors[] = 'Album name can contains only latin ' .
                "leters, digits, whitespace, '-' and '_'.";
        }
    }
    
    private function validateDescription() {   
        if (empty($this->description)) {
            $this->description = null;
            return;
        }
        
        // if (!preg_match('/^[A-Za-zА-Яа-я-]+$/', $this->first_name)) {
            // $this->errors[] = 'First name can contains only cyrillic ' .
                // "or latin leters and '-'.";
        // }
    }
    
    private function validateIsPrivate() {
        if (empty($this->is_private)) {
            $this->is_private = null;
            return;
        }
        
        if (intval($this->is_private) != 1 && intval($this->is_private) != 0) {
            $this->is_private = null;
            $this->errors[] = 'Invalid privacy value. Your album is set to public.';
        }
    }
}
