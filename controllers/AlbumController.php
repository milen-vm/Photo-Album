<?php

class AlbumController extends BaseController {
	private $model;
    
	function __construct() {
		parent::__construct('album');
        $this->model = new AlbumModel();
	}
    
    // public function index() {
        // $this->renderView();
    // }
    
    public function create() {
        $this->authorize();
        if ($this->is_post) {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $is_private = isset($_POST['is_private']) ? $_POST['is_private'] : '';
            
            $create_result = $this->model->create($name, $description, $is_private,
                $this->getUserId());
            
            if ($create_result) {
                $this->addInfoMessage('Album successfully created.');
                $path = ALBUMS_PATH . $this->getUsername() . '/' . $create_result;
                
                if (!$this->makeDir($path)) {
                    $this->addErrorMessage('Error to create album directory.');
                }
                
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
    
    public function myalbums() {
        
        $this->renderView(__FUNCTION__);
    }
}
