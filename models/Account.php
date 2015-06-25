<?php

class Account {
    private $email;
    private $full_name;
    private $birth_date;
    private $password;
    private $confirm_password;
    private $errors = array();
    
    public function __construct($email, $full_name, $birth_date, $password, $confirm_password) {
        $this->email = $email;
        $this->full_name = $full_name;
        $this->birth_date = $birth_date;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getFullName() {
        return $this->full_name;
    }
    
    public function getBirthDate() {
        if (empty($this->birth_date)) {
            return null;
        }
        
        return $this->birth_date;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getPasswordHash() {
        $options = array('cost' => PASSWORD_CRYPT_COST);
        return password_hash($this->password, PASSWORD_DEFAULT, $options);
    }
    
    public function getErrors() {
        return $this->errors;
    }
        
    public function isValid() {
        $this->validateEmail();
        $this->validateFullName();
        $this->validateBirthDate();
        $this->validatePassword();
        
        return count($this->errors) === 0;
    }
    
    private function validateEmail() {
        if (empty($this->email)) {
            $this->errors[] = 'Email is required.';
        } else {
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Invalid email format.';
            }
        }
    }
    
    private function validateFullName() {
        if (empty($this->full_name)) {
            $this->errors[] = 'First name is required.';
            return;
        }
        
        $length = mb_strlen($this->full_name, 'UTF-8');
        if ($length < FULL_NAME_MIN_LENGTH) {
            $this->errors[] = 'Full name min lenth is ' .
                FULL_NAME_MIN_LENGTH . ' chars.';
            return;
        }
        
        if ($length > FULL_NAME_MAX_LENGTH) {
            $this->errors[] = 'Full name max lenth is ' .
                FULL_NAME_MAX_LENGTH . ' chars. Your name is ' .
                $length . ' chars long.';
            return;
        }
        
        // if (!preg_match('/^[A-Za-z-\s+]+$/', $this->full_name)) {
            // $this->errors[] = 'Full name can contains only ' .
                // "latin leters and '-'.";
        // }
    }
    
    private function validateBirthDate() {
        if (empty($this->birth_date) || $this->birth_date === null) {
            return;
        }
        
        $date = preg_replace('/\s+/', '', $this->birth_date);
        $date_array  = explode('-', $date);
        if (count($date_array) === 3) {
            $date_array = array_map('intval', $date_array);
            // bool checkdate ( int $month , int $day , int $year )
            if (checkdate($date_array[1], $date_array[2], $date_array[0])) {
                if ($date_array[0] < MIN_BIRTH_YEAR) {
                    $this->errors[] = 'Invalid birth year.';
                }
            } else {
                $this->errors[] = 'Invalid birth date.';
            }
        } else {
            $this->errors[] = 'Invalid date format.';
        }
    }
    
    private function validatePassword() {
        if (empty($this->password)) {
            $this->errors[] = 'Password is required.';
            return;
        }
        
        if (preg_match('/\s+/', $this->password)) {
            $this->errors[] = 'Passwort cannot contains white spaces.';
            return;
        }
        
        if (strlen($this->password) < PASSWORD_MIN_LENGTH) {
            $this->errors[] = 'Password min length is ' .
                PASSWORD_MIN_LENGTH . ' symbols.';
            return;
        }
        
        if ($this->password != $this->confirm_password) {
            $this->errors[] = 'Confirm password do not match.';
        }
    }
    // Not in use
    private function validateUsername() {
        if (empty($this->user_name)) {
            $this->errors[] = 'Username is required.';
            return;
        }
        
        if (strlen($this->user_name) < USER_NAME_MIN_LENGTH) {
            $this->errors[] = 'Min username length is ' .
                USER_NAME_MIN_LENGTH .' chars.';
            return;
        }
        
        if (!preg_match('/^[A-Za-z0-9-_]+$/', $this->user_name)) {
            $this->errors[] = 'Username can contains only latin ' .
                "leters, digits, '-' and '_'.";
        }
    }
}
