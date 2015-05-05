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
            $user_name = $_POST['user_name'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $birth_date = $_POST['birth_date'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = trim($_POST['confirm_password']);
            
            $register_result = $this->model->register($user_name, $first_name,
                $last_name, $birth_date, $email, $password, $confirm_password);
                
            if ($register_result === true) {
                $this->addInfoMessage('Successful registration.');
                $this->redirect('home');
            } else {
                $errors = $this->model->getErrors();
                foreach ($errors as $err) {
                    $this->addErrorMessage($err);
                }
            }
        }
        
        $this->renderView(__FUNCTION__);
    }
    
    public function login() {
        if($this->is_post) {
            $user_name = trim($_POST['user_name']);
            $password = trim($_POST['password']);
            $login_result = $this->model->login($user_name, $password);
            
            if ($login_result) {
                $_SESSION['user_name'] = $user_name;
                $_SESSION['full_name'] = $this->model->full_name;
                $this->addInfoMessage('Login successfuly.');
                $this->redirect('home');
            }
            else {
                var_dump($_POST);
                $this->addErrorMessage('Login error.');
                $this->redirect('account', 'login');
            }
        }
        
        $this->renderView(__FUNCTION__);
    }
    
    public function logout() {
        $this->authorize();

        if($this->is_post) {
            session_destroy();
            session_start();
            $this->addInfoMessage('You are logged out.');
            $this->redirect('home');
        }
    }
}
