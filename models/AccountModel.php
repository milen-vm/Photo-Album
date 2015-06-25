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
         $result = $this->find(
            array(
                'columns' => 'id, full_name, password_hash',
                'where' => 'email = ?'
            ),
            array($email)
        );
        
        if (isset($result[0])) {
            if (password_verify($password, $result[0]['password_hash'])) {
                
                return array(
                    'id' => $result[0]['id'],
                    'full_name' => $result[0]['full_name'],
                    'email' => $email
                );
            }
        }

        return null;
    }

    private function isEntryUnique($key, $entry) {
        $result = $this->find(
            array(
                'columns' => 'COUNT(id)',
                'where' => $key . ' = ?'
            ),
            array($entry)
        );
        
        if (isset($result[0]['COUNT(id)'])) {
            return $result[0]['COUNT(id)'] === 0;
        }

        throw new Exception('Application error');
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
