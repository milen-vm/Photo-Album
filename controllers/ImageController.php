<?php

class ImageController extends BaseController {
	private $model;
    
	public function __construct() {
		parent::__construct('image');
        $this->model = new ImageModel();
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
           
            $add_result = $this->model->addImage($album_id, $this->getUserId());
            if ($add_result) {
                $this->addInfoMessage('Successfuly uploaded image ' . $full_file_name . '.');
                $this->redirect('album', 'browse', array($album_id));
            } else {
                $errors = $this->model->getErrors();
                foreach ($errors as $err) {
                    $this->addErrorMessage($err);
                }
                
                $this->redirect('album', 'browse', array($album_id));
            }
        }
        
        $this->addErrorMessage('No photo selected.');
        $this->redirect('album', 'browse', array($album_id));
    }
}
