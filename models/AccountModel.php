<?php

class AccountModel extends BaseModel {
	
	public function __construct($argument) {
		parent::__construct();
        session_set_cookie_params(1800, DX_ROOT_DIR);
	}
    
    public function register() {
        
    }
    
    public function login() {
        
    }
}
