<?php

class AlbumController extends BaseController {
	private $model;
    
	public function __construct() {
		parent::__construct('album');
        $this->model = new AlbumModel();
	}
    
    public function index($page = 0, $page_size = ALBUMS_PAGE_SIZE) {
        $this->authorize();
        if ($page < 0) {
            $page = 0;
        }
        
        $albums_count = $this->model->getCount($this->getUserId());
        $total_pages = ($albums_count + $page_size - 1) / $page_size;
        if (($page + 1) > $total_pages) {
            $page -= 1;
        }
        
        if ($page < 0) {
            $page = 0;
        }

        $this->page = $page;
        $this->page_size = $page_size;
        $start = $page * $page_size;
        
        $this->albums = $this->model->getAll($this->getUserId(), $start, $page_size);
        var_dump($this->albums);
        $this->renderView();
    }
    
    public function get($album_id) {
        $this->authorize();
    }      
    
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
                $path = ALBUMS_PATH . DIRECTORY_SEPARATOR . $create_result;
                
                if (!$this->makeDir($path)) {
                    $this->addErrorMessage('Error to create album directory.');
                    // TODO Delete album from database if directory creation fails
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
        $is_user_owns_album = $this->model->isUserOwnsAlbum($album_id, $this->getUserId());
        if (!$is_user_owns_album) {
            $this->addErrorMessage('Invalid album selected.');
            $this->redirect('album');
        } 
        
        $result = $this->model->delete($album_id);
        if ($result === 0) {
            $this->addErrorMessage('Database error. Album os not deleted.');
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
        $this->redirect('album');
    }
}
