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
        
        $this->album_id = $album_id;
        $is_user_owns_album = $this->model->isUserOwnsAlbum($album_id, $this->getUserId());
        if (!$is_user_owns_album) {
            $this->addErrorMessage('Invalid album selected.');
            $this->redirect('album');
        }
        
        $album_images_count = $this->model->getCount($album_id);
        $this->pagination = new Pagination($album_images_count, IMAGES_PAGE_SIZE);
        
        $this->album_data = $this->model->getAlbumData($album_id);
        $start = $this->pagination->getOffset();
        $images = $this->model->getImagesPaginated($album_id, $start, IMAGES_PAGE_SIZE);
        $this->images_data = array();
        
        if (count($images) === 0) {
            $this->addInfoMessage('This album is empty.');
        } else {
            $main_path = ALBUMS_PATH . D_S . $album_id . D_S . THUMBS_DIR_NAME . D_S;
            $this->images_data = $this->makeBase64($images, $main_path);
        }
        
        $_SESSION['album_id'] = $album_id;
        $this->renderView(__FUNCTION__);
    }

    public function view($image_id) {
        var_dump($image_id);
    }
    
    public function upload() {
        $this->authorize();
        if (!isset($_SESSION['album_id'])) {
            $this->addErrorMessage('Album for upload into, is not selected.');
            $this->redirect('album');
        }
        
        $album_id = $_SESSION['album_id'];
        unset($_SESSION['album_id']);

        $image = $_FILES['photo'];
        if (isset($_POST['submit']) && $image['name'] != '') {
           // TODO Move check for is user owns album here
            $image_id = $this->model->addImage($image, $album_id, $this->getUserId());
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
