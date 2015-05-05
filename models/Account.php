<?php

class Account {
    private $user_name;
    private $first_name;
    private $last_name;
    private $birth_date;
    private $email;
    private $password;
    private $confirm_password;
    private $errors = array();
    
    public function __construct($user_name, $first_name, $last_name, $birth_date,
        $email, $password, $confirm_password) {
        $this->user_name = $user_name;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->birth_date = $birth_date;
        $this->email = $email;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }
        
    public function getUsername() {
        return $this->user_name;
    }
    
    public function getFirstName() {
        return $this->first_name;
    }
    
    public function getLastName() {
        return $this->last_name;
    }
    
    public function getBirthDate() {
        if (empty($this->birth_date)) {
            return null;
        }
        
        return $this->birth_date;
    }
    public function getEmail() {
        return $this->email;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getPasswordHash() {
        return password_hash($this->password, PASSWORD_DEFAULT);
    }
    
    public function getErrors() {
        return $this->errors;
    }
        
    public function isValid() {
        $this->validateUsername();
        $this->validateFirstName();
        $this->validateLastName();
        $this->validateBirthDate();
        $this->validateEmail();
        $this->validatePassword();
        
        return count($this->errors) === 0;
    }
    
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
    
    private function validateFirstName() {
        if (empty($this->first_name)) {
            $this->errors[] = 'First name is required.';
            return;
        }
        
        if (strlen($this->first_name) < FIRST_LAST_NAME_MIN_LENGTH) {
            $this->errors[] = 'First name min lenth is ' .
                FIRST_LAST_NAME_MIN_LENGTH . ' chars.';
            return;
        }
        
        if (!preg_match('/^[A-Za-zА-Яа-я-]+$/', $this->first_name)) {
            $this->errors[] = 'First name can contains only cyrillic ' .
                "or latin leters and '-'.";
        }
    }
    
    private function validateLastName() {
        if (empty($this->last_name)) {
            $this->errors[] = 'Last name is required.';
            return;
        }
        
        if (strlen($this->last_name) < FIRST_LAST_NAME_MIN_LENGTH) {
            $this->errors[] = 'Last name min lenth is ' .
                FIRST_LAST_NAME_MIN_LENGTH . ' chars.';
            return;
        }
        
        if (!preg_match('/^[A-Za-zА-Яа-я-]+$/', $this->first_name)) {
            $this->errors[] = 'Last name can contains only cyrillic ' .
                "or latin leters and '-'.";
        }
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
    
    private function validateEmail() {
        if (empty($this->email)) {
            $this->errors[] = 'Email is required.';
        } else {
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Invalid email format.';
            }
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
}
