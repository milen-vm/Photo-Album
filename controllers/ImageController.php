<?php

class ImageController extends BaseController {
	private $model;
    
	public function __construct() {
		parent::__construct('image');
        $this->model = new ImageModel();
	}
    
    public function browse($album_id, $page = 0, $page_size = IMAGES_PAGE_SIZE) {
        $this->authorize();
        if (!isset($album_id)) {
            $this->addErrorMessage('Album for browse is not selected.');
            $this->redirect('album');
        }

        $is_user_owns_album = $this->model->isUserOwnsAlbum($album_id, $this->getUserId());
        if (!$is_user_owns_album) {
            $this->addErrorMessage('Invalid album selected.');
            $this->redirect('album');
        }
        
        if ($page < 0) {
            $page = 0;
        }
        // TODO add validation for max page - get count for albums from db
        $this->page = $page;
        $this->page_size = $page_size;
        $start = $page * $page_size;
        
        $images = $this->model->getImagesPaginated($album_id, $page, $page_size); // tuka sym
        $this->images_paths = array();
        foreach ($images as $image) {
            $path = '' . DX_ROOT_PATH . ALBUMS_PATH . '/' . $album_id .
                '/' . $image['name'] . '.' . $image['type'];
            $this->images_paths[] = $path;
        }
        
        $_SESSION['album_id'] = $album_id;
        $this->renderView(__FUNCTION__);
    }
    
    public function upload() {
        $this->authorize();
        if (!isset($_SESSION['album_id'])) {
            $this->addErrorMessage('Album for upload into, is not selected.');
            $this->redirect('album');
        }
        
        $album_id = $_SESSION['album_id'];
        unset($_SESSION['album_id']);

        $full_file_name = basename($_FILES['photo']['name']);             
        if (isset($_POST['submit']) && $full_file_name != '') {
           // TODO Move check for is user owns album here
            $image_id = $this->model->addImage($album_id, $this->getUserId());
            if (!$image_id) {
                $errors = $this->model->getErrors();
                foreach ($errors as $err) {
                    $this->addErrorMessage($err);
                }
                
                $this->redirect('image', 'browse', array($album_id));
            }
            
            $this->addInfoMessage('Successfuly uploaded image ' . $full_file_name . '.');
            $this->redirect('image', 'browse', array($album_id));
        }
        
        $this->addErrorMessage('No photo selected.');
        $this->redirect('image', 'browse', array($album_id));
    }
}
