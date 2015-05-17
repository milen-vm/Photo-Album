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
            $read_errors = 0;
            foreach ($images as $image) {
                $path = ALBUMS_PATH . '/' . $album_id .
                    '/' . $image['name'] . '.' . $image['type'];
                if (is_readable($path)) {
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $image['type'] . ';base64,' . base64_encode($data);
                    $this->images_data[] = $base64;
                } else {
                    ++$read_errors;
                }
            }
            
            if ($read_errors > 0) {
                $this->addErrorMessage("Something goes wrong. There is $read_errors broken images.");
            }
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
