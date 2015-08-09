<?php

class Account {
    private $email;
    private $fullName;
    private $birthDate;
    private $password;
    private $errors = array();
    
    public function __construct($email, $fullName, $birthDate, $password, $confirmedPassword) {
        $this->setEmail($email);
        $this->setFullName($fullName);
        $this->setBirthDate($birthDate);
        $this->setPassword($password, $confirmedPassword);
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        if (empty($email)) {
            $this->errors[] = 'Email is required.';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->errors[] = 'Invalid email format.';
        } else {
            $this->email = $email;
        }
    }
    
    public function getFullName() {
        return $this->fullName;
    }
    
    public function setFullName($fullName) {
        if (empty($fullName)) {
            $this->errors[] = 'Full name is required.';
            return;
        }
        
        $length = mb_strlen($fullName, 'UTF-8');
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
        
        $this->fullName = $fullName;
        
        // if (!preg_match('/^[A-Za-z-\s+]+$/', $this->fullName)) {
            // $this->errors[] = 'Full name can contains only ' .
                // "latin leters and '-'.";
        // }
    }
    
    public function getBirthDate() {
        if (empty($this->birthDate)) {
            return null;
        }
        
        return $this->birthDate;
    }
    
    public function setBirthDate($birthDate) {
        if (empty($birthDate) || $birthDate === null) {
            return;
        }
        
        $date = preg_replace('/\s+/', '', $birthDate);
        $dateArray  = explode('-', $date);

        if (count($dateArray) === 3) {
            $dateArray = array_map('intval', $dateArray);
            // bool checkdate ( int $month , int $day , int $year )
            if (checkdate($dateArray[1], $dateArray[2], $dateArray[0])) {
                if ($dateArray[0] < MIN_BIRTH_YEAR) {
                    $this->errors[] = 'Invalid birth year.';
                    return;
                }
            } else {
                $this->errors[] = 'Invalid birth date.';
                return;
            }
        } else {
            $this->errors[] = 'Invalid date format.';
            return;
        }
        
        $this->birthDate = $birthDate;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function setPassword($password, $confirmedPassword) {
         if (empty($password)) {
            $this->errors[] = 'Password is required.';
            return;
        }
        
        if (preg_match('/\s+/', $password)) {
            $this->errors[] = 'Passwort cannot contains white spaces.';
            return;
        }
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $this->errors[] = 'Password min length is ' .
                PASSWORD_MIN_LENGTH . ' symbols.';
            return;
        }
        
        if ($password != $confirmedPassword) {
            $this->errors[] = 'Confirm password do not match.';
            return;
        }
        
        $this->password = $password;
    }
    
    public function getPasswordHash() {
        $options = array('cost' => PASSWORD_CRYPT_COST);
        return password_hash($this->password, PASSWORD_DEFAULT, $options);
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function isValid() {
        return count($this->errors) === 0;
    }
}
