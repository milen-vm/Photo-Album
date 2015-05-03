<?php

class AccountController extends BaseController {
	private $model;
    
	public function __construct() {
		parent::__construct('account');
        $this->model = new AccountModel();
	}
    
    public function index() {
        $this->renderView();
    }
    
    public function register() {
        if ($this->is_post) {
            var_dump($_POST);
            $user_name = trim($_POST['user_name']);
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $birth_date = trim($_POST['birth_date']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            $registration_result = $this->model->register($user_name, $first_name,
                $last_name, $birth_date, $email, $password, $confirm_password);
        }
        
        $this->renderView(__FUNCTION__);
    }
    
    public function login() {
        
        $this->renderView(__FUNCTION__);
    }
    
    public function logout() {
        
    }
    
    
}
