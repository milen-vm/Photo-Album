<?php

class AccountModel extends BaseModel {
	private $errors = array();
    
	public function __construct() {
		parent::__construct(array('table' => 'users'));
        session_set_cookie_params(1800, '/', 'localhost', false, true);
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
        
    public function login($username, $password) {
        $query = 'SELECT id, user_name, first_name, last_name, password_hash FROM users WHERE user_name = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $username);
        
        if ($stmt->execute()) {
            $stmt->bind_result($id, $user_name, $first_name, $last_name, $password_hash);
            $stmt->fetch();
           // while ($stmt->fetch()) {
               // //do stuff with the data
               // echo "$id, $user_name, $password_hash";
           // }
            if (password_verify($password, $password_hash)) {
                $this->full_name = "$first_name $last_name";
                $this->user_id = $id;
                return true;
            }
        }

        return false;
    }

    private function isEntryUnique($key, $entry) {
        $query = 'SELECT COUNT(id) FROM users WHERE ' . $key . ' = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $entry);
        
        if ($stmt->execute()) {
            $stmt->bind_result($result);
            $stmt->fetch();
            return $result === 0;
        }
        
        // $this->errors[] = $stmt->error;
        throw new Exception($stmt->error);
    }

    private function registerUser($account) {
        $query = 'INSERT INTO users (user_name, first_name, last_name,' .
            ' birth_date, email, password_hash) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssss', $account->getUsername(), $account->getFirstName(),
            $account->getLastName(), $account->getBirthDate(),
            $account->getEmail(), $account->getPasswordHash());
        if ($stmt->execute()) {
            return true;
        }
        
        // $this->errors[] = $stmt->error;
        throw new Exception($stmt->error);
    }
}
