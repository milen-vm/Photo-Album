<?php

class AccountModel extends BaseModel {
	private $errors = array();
    
	public function __construct() {
		parent::__construct(array('table' => 'users'));
        session_set_cookie_params(1800, '/', DOMAIN_NAME, false, true);
	}
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function register($user_data) {
        $account = new Account($user_data['email'], $user_data['full_name'], $user_data['birth_date'],
            $user_data['password'], $user_data['confirm_password']);
        
        if ($account->isValid()) {
            $is_email_unique = $this->isEntryUnique('email', $account->getEmail());
            if ($is_email_unique) {
                return $this->registerUser($account);
                
            } else {
                $this->errors[] = 'Email is already taken.';
                return null;
            }
        } else {
            $this->errors = $account->getErrors();
            return null;
        }
    }
        
    public function login($email, $password) {
        $query = 'SELECT id, full_name, password_hash FROM users WHERE email = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        
        if ($stmt->execute()) {
            $stmt->bind_result($id, $full_name, $password_hash);
            $stmt->fetch();
           // while ($stmt->fetch()) {
               // //do stuff with the data
               // echo "$id, $user_name, $password_hash";
           // }
            if (password_verify($password, $password_hash)) {
                
                return array(
                    'id' => $id,
                    'full_name' => $full_name,
                    'email' => $email
                );
            }
        }

        return null;
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
        $pairs = array();
        $pairs['email'] = $account->getEmail();
        $pairs['full_name'] = $account->getFullName();
        $pairs['birth_date'] = $account->getBirthDate();
        $pairs['password_hash'] = $account->getPasswordHash();
        $id = $this->add($pairs);
        
        return $id;
    }
}
