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

        $albums_count = $this->model->getUserAlbumsCount($this->getUserId());
        $this->pagination = new Pagination($albums_count, ALBUMS_PAGE_SIZE);
        
        $start = $this->pagination->getOffset();
        $this->albums = $this->model->getUserAlbums($this->getUserId(), $start, ALBUMS_PAGE_SIZE);
        if (empty($this->albums)) {
            $this->addInfoMessage('You do not have any albums.');
        }
        
        $this->renderView();
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
            $album_id = $this->model->create($name, $description, $is_private,
                $this->getUserId());
            
            if ($album_id != null) {
                $this->addInfoMessage('Album successfully created.');
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

    public function edit($album_id) {
        $this->authorize();
        if (!$this->is_post) {
            $_SESSION['form_token'] = hash('sha256', uniqid());
        }

        $album = $this->model->getAlbum($album_id, $this->getUserId());
        if (isset($album[0])) {
            $this->album = $album[0];
        } else {
            $this->addErrorMessage('Invalid album selected.');
            $this->redirect('album');
        }

        $this->renderView(__FUNCTION__);
    }

    public function update($album_id) {
        if ($this->is_post) {
            if (!isset($_POST['form_token']) || $_POST['form_token'] != $_SESSION['form_token']) {
                die('Aplication error.');
            }
            
            $name = $_POST['name'];
            $description = $_POST['description'];
            $is_private = isset($_POST['is_private']) ? $_POST['is_private'] : '';
            
            $result = $this->model->updateAlbum($album_id, $name, $description,
                $is_private, $this->getUserId());

            if ($result != null) {
                $this->addInfoMessage('Album successfully edited.');    // TODO Back to current page number
                $this->redirect('album');
            } else {
                $errors = $this->model->getErrors();
                foreach ($errors as $err) {
                    $this->addErrorMessage($err);
                }
                
                $this->redirect('album', 'edit', array($album_id));
            }
        }
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
        
        $directory = ALBUMS_PATH . D_S . $album_id;
        if ($this->removeDirectory($directory)) {
            $this->addInfoMessage('The album is successfuly deleted.');
        } else {
            $this->addErrorMessage('The album does not completely deleted.');
        }
        // TODO Set current page number
        $this->redirect('album', 'index');
    }
    
    private function removeDirectory($directory) {
        $items = glob($directory . '/*');
        foreach($items as $item)
        {
            if(is_dir($item)) { 
                $this->removeDirectory($item);
            } else {
                unlink($item);
            }
        }

        return rmdir($directory);
    }
}
