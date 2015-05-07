<?php

class AlbumController extends BaseController {
	private $model;
    
	function __construct() {
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
    
    public function browse($id) {
        $this->authorize();
        if (!isset($id)) {
            $this->addErrorMessage('Album for browse is not selected.');
            $this->redirect('album');
        }
        
        $this->album_id = $id;
        $_SESSION['album_id'] = $id;
        $this->renderView(__FUNCTION__);
        var_dump($this->album_id);
    }
    
    public function upload() {
        $this->authorize();
        if (!isset($_SESSION['album_id'])) {
            $this->addErrorMessage('Album for upload into, is not selected.');
            $this->redirect('album');
        }
        
        $album_id = $_SESSION['album_id'];
        unset($_SESSION['album_id']);
        
        $full_file_name = basename($_FILES['photo']['name']);      // full file name               
        if (isset($_POST['submit']) && $full_file_name != '') {
           
            if ($this->isValidImage()) {
                $this->uploadImage($album_id);
                $this->redirect('album', 'browse', array($album_id));
            }
            
            $this->redirect('album', 'browse', array($album_id));
        }
        
        $this->addErrorMessage('No photo selected.');
        $this->redirect('album', 'browse', array($album_id));
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
    
    private function isValidImage() {
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if($check === false) {
            $this->addErrorMessage('File is not a image.');
            return false;
        }
        
        if ($_FILES['photo']['size'] > MAX_IMAGE_FILE_SIZE) {
            $this->addErrorMessage('Max image file size is ' .
                MAX_IMAGE_FILE_SIZE . ' b.');
            return false;
        }
        
        $full_file_name = basename($_FILES['photo']['name']);
        $file_type = pathinfo($full_file_name, PATHINFO_EXTENSION);
        if($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg'
                && $file_type != 'gif' ) {
            $this->addErrorMessage('Aloed image formats are only JPG, JPEG, PNG & GIF.');
            return false;
        }
                
        return true;
    }
    
    private function uploadImage($album_id) {
        $full_file_name = basename($_FILES['photo']['name']);
        $file_type = pathinfo($full_file_name, PATHINFO_EXTENSION);
        
        $new_file_name = uniqid('img_');
        $target_path = ALBUMS_PATH . DIRECTORY_SEPARATOR . $album_id .
            DIRECTORY_SEPARATOR;
            
        while (file_exists($target_path . $new_file_name . '.' . $file_type)) {
            $new_file_name = uniqid('img_');
        }
        
        $target_file = $target_path . $new_file_name . '.' . $file_type;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $this->addInfoMessage('The image '. basename( $_FILES["fileToUpload"]["name"]) . 
                'has been uploaded.');
        } else {
            $this->addErrorMessage('There was an error uploading your image.');
        }
    }
        
}
