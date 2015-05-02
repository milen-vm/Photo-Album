<?php

class AccountController extends BaseController {
	private $model;
    
	public function __construct() {
		parent::__construct('Account');
        $this->model = new AccountModel();
	}
    
    public function register() {
        
        $this->renderView(__FUNCTION__);
    }
    
    public function login() {
        
        $this->renderView(__FUNCTION__);
    }
    
    public function logout() {
        
    }
    
    
}
