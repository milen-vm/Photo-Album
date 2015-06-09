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
        if ($this->isLoggedIn()) {
            $this->redirect('home');
        }
        
        if (!$this->is_post) {
            $_SESSION['form_token'] = hash('sha256', uniqid());
        }
        
        if ($this->is_post) {
            if (!isset($_POST['form_token']) || $_POST['form_token'] != $_SESSION['form_token']) {
                die('Aplication error.');
            }
            $user_data = array();
            $user_data['email'] = trim($_POST['email']);
            $user_data['full_name'] = trim($_POST['full_name']);
            $user_data['birth_date'] = trim($_POST['birth_date']);
            $user_data['password'] = trim($_POST['password']);
            $user_data['confirm_password'] = trim($_POST['confirm_password']);
            // TODO Save field values on wrong input after submit - get account from model to view
            $id = $this->model->register($user_data);
                
            if ($id != null) {
                $this->addInfoMessage('Successful registration.');
                $this->createUserSession(array(
                    'di' => $id,
                    'full_name' => $user_data['full_name'],
                    'email' => $user_data['email']
                ));
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
        if ($this->isLoggedIn()) {
            $this->redirect('home');
        }
        
        if (!$this->is_post) {
            $_SESSION['form_token'] = hash('sha256', uniqid());
        }
        
        if($this->is_post) {
            if (!isset($_POST['form_token']) || $_POST['form_token'] != $_SESSION['form_token']) {
                die('Aplication error.');
            }
            
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $user_data = $this->model->login($email, $password);
            
            if ($user_data != null) {
                $this->createUserSession($user_data);
                
                $this->addInfoMessage('Login successfuly.');
                $this->redirect('home');
            }
            else {
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
            $this->redirect(DEFAULT_CONTROLLER);
        }
    }
    
    private function createUserSession($user_data) {
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['full_name'] = $user_data['full_name'];
        $_SESSION['user_id'] = $user_data['id'];
    }
}
