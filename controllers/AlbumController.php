<?php

class AlbumController extends BaseController {
	private $model;
    
	public function __construct() {
		parent::__construct('album');
        $this->model = new AlbumModel();
	}
    
    public function index() {
        $this->authorize();
        if (!$this->is_post) {
            $_SESSION['form_token'] = hash('sha256', uniqid());
        }

        $albums_count = $this->model->getCount($this->getUserId());
        $this->pagination = new Pagination($albums_count, ALBUMS_PAGE_SIZE);
        
        $start = $this->pagination->getOffset();
        $this->albums = $this->model->getAll($this->getUserId(), $start, ALBUMS_PAGE_SIZE);
        $this->renderView();
    }
    
    public function publicalbums() {
        
    }      
    
    public function create() {
        $this->authorize();
        if (!$this->is_post) {
            $_SESSION['form_token'] = hash('sha256', uniqid());
        }
        
        if ($this->is_post) {
            if (!isset($_POST['form_token']) || $_POST['form_token'] != $_SESSION['form_token']) {
                die('Aplication error.');
            }
            
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $is_private = isset($_POST['is_private']) ? $_POST['is_private'] : '';
            
            $create_result = $this->model->create($name, $description, $is_private,
                $this->getUserId());
            
            if ($create_result) {
                $this->addInfoMessage('Album successfully created.');
                $path = ALBUMS_PATH . D_S . $create_result;
                
                if (!$this->makeDir($path)) {
                    $this->addErrorMessage('Error to create album directory.');
                    // TODO Delete album from database if directory creation fails
                }
                
                if (!$this->makeDir($path . D_S . THUMBS_DIR_NAME)) {
                    $this->addErrorMessage('Error to create thumbnail directory.');
                }
                
                $this->redirect('album');
            } else {
                $errors = $this->model->getErrors();
                foreach ($errors as $err) {
                    $this->addErrorMessage($err);
                }
            }
        }
        
        $this->renderView(__FUNCTION__);
    }

    public function delete($album_id) {
        $this->authorize();
        if (!isset($_POST['form_token']) || $_POST['form_token'] != $_SESSION['form_token']) {
            die('Aplication error.');
        }
        
        $is_user_owns_album = $this->model->isUserOwnsAlbum($album_id, $this->getUserId());
        if (!$is_user_owns_album) {
            $this->addErrorMessage('Invalid album selected.');
            $this->redirect('album');
        } 
        
        $result = $this->model->delete($album_id);
        if ($result === 0) {
            $this->addErrorMessage('Database error. Album is not deleted.');
            $this->redirect('album');
        }
        
        $dir = ALBUMS_PATH . '/' . $album_id;
        $files = glob($dir . '/*');
        foreach($files as $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
        
        if (!rmdir($dir)) {
            $this->addErrorMessage('The album does not completely deleted.');
        }
        
        $this->addInfoMessage('The album is successfuly deleted.');
        // TODO Set current page number
        $this->redirect('album', 'index');
    }
}
