<?php

class AccountModel extends BaseModel {
	
	public function __construct() {
		parent::__construct();
        session_set_cookie_params(1800, '/');
	}
    
    public function register($user_name, $first_name, $last_name, $birth_date,
        $email, $password, $confirm_password) {
        $account = new Account($user_name, $first_name, $last_name, $birth_date,
        $email, $password, $confirm_password);
    }
    
    public function login() {
        
    }
}
