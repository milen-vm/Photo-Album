<?php

class AccountModel extends BaseModel {
	private $errors = array();
    
	public function __construct() {
		parent::__construct();
        session_set_cookie_params(1800, '/');
	}
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function register($user_name, $first_name, $last_name, $birth_date,
            $email, $password, $confirm_password) {
        $account = new Account($user_name, $first_name, $last_name, $birth_date,
        $email, $password, $confirm_password);
        
        if ($account->isValid()) {
            $is_user_name_unique = $this->isEntryUnique('user_name', $account->getUsername());
            
            if ($is_user_name_unique) {
                $is_email_unique = $this->isEntryUnique('email', $account->getEmail());
                
                if ($is_email_unique) {
                    return $this->registerUser($account);
                    
                } else {
                    $this->errors[] = 'Email is already taken.';
                    return false;
                }
                
            } else {
                $this->errors[] = 'Username is already taken.';
                return false;
            }
        } else {
            $this->errors = $account->getErrors();
            return false;
        }
    }
        
    public function login() {
        
    }

    private function isEntryUnique($key, $entry) {
        $query = 'SELECT COUNT(id) FROM users WHERE ' . $key . ' = ?';
        $stmt = self::$db->prepare($query);
        $stmt->bind_param('s', $entry);
        $stmt->execute();
        if ($stmt->execute()) {
            $stmt->bind_result($result);
            $stmt->fetch();
            return $result === 0;
        }
        
        throw new Exception($stmt->error);
    }

    private function registerUser($account) {
        $query = 'INSERT INTO users (user_name, first_name, last_name,' .
            ' birth_date, email, password_hash) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = self::$db->prepare($query);
        $stmt->bind_param('ssssss', $account->getUsername(), $account->getFirstName(),
            $account->getLastName(), $account->getBirthDate(),
            $account->getEmail(), $account->getPasswordHash());
        if ($stmt->execute()) {
            return true;
        }
        
        // $this->errors['db'] = $stmt->error;
        return false;
    }
}
