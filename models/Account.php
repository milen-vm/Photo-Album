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
    
    private function validateUsername() {
        if (strlen($this->user_name) < USER_NAME_MIN_LENGTH) {
            $this->errors['user_name'] = 'Min username length is ' .
                USER_NAME_MIN_LENGTH .' chars.';
            return;
        }
        
        if (!preg_match('/^[A-Za-z0-9-_]+$/', $this->user_name)) {
            $this->errors['user_name'] = 'Username can contains only latin ' .
                "leters, digits, '-' and '_'.";
        }
    }
    
    private function validateFirstName() {
        if (strlen($this->first_name) < FIRST_LAST_NAME_MIN_LENGTH) {
            $this->errors['first_name'] = 'First name min lenth is ' .
                FIRST_LAST_NAME_MIN_LENGTH . 'chars.';
            return;
        }
        
        if (!preg_match('/^[A-Za-zА-Яа-я-]+$/', $this->first_name)) {
            $this->errors['first_name'] = 'First name can contains only cyrillic ' .
                "or latin leters and '-'.";
        }
    }
    
    private function validateLastName() {
        if (strlen($this->last_name) < FIRST_LAST_NAME_MIN_LENGTH) {
            $this->errors['last_name'] = 'Last name min lenth is ' .
                FIRST_LAST_NAME_MIN_LENGTH . 'chars.';
            return;
        }
        
        if (!preg_match('/^[A-Za-zА-Яа-я-]+$/', $this->first_name)) {
            $this->errors['last_name'] = 'Last name can contains only cyrillic ' .
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
            if (checkdate($date_array[0], $date_array[1], $date_array[2])) {
                if ($date_array[0] < MIN_BIRTH_YEAR) {
                    $this->errors['birth_date'] = 'Invalid birth year.';
                }
            } else {
                $this->errors['birth_date'] = 'Invalid date.';
            }
        } else {
            $this->errors['birth_date'] = 'Invalid date format.';
        }
    }
    
    private function validateEmail() {
        if (empty($this->email)) {
            $this->errors['email'] = 'Email is required.';
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Invalid email format.';
            }
        }
    }
    
    private function validatePassword() {
        if (preg_match('/\s+/', $this->password)) {
            $this->errors['password'] = 'Passwort cannot contains white spaces.';
            return;
        }
        
        if (strlen($this->password) < PASSWORD_MIN_LENGTH) {
            $this->errors['password'] = 'Password min length is ' .
                PASSWORD_MIN_LENGTH . ' symbols.';
            return;
        }
        
        if ($this->password != $this->confirm_password) {
            $this->errors['password'] = 'Confirm password do not match.';
        }
    }
}
