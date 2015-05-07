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
        // TODO add validation for max page - get count for albums from db
        $this->page = $page;
        $this->page_size = $page_size;
        
        $start = $page * $page_size;
        $this->albums = $this->model->getAll($this->getUserId(), $start, $page_size);
        $this->renderView();
    }
    
    // public function browse($id) {
        // $this->authorize();
        // if (!isset($id)) {
            // $this->addErrorMessage('Album for browse is not selected.');
            // $this->redirect('album');
        // }
//         
        // $this->album_id = $id;
        // $_SESSION['album_id'] = $id;
        // $this->renderView(__FUNCTION__);
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
                $path = ALBUMS_PATH . DIRECTORY_SEPARATOR . $create_result;
                
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
}
