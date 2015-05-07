<?php

class ImageModel extends BaseModel {
	private $errors = array();
    
	public function __construct() {
		parent::__construct(array('table' => 'images'));
	}
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function addImage($album_id, $user_id) {
        $image = new Image();
        if ($image->isValid()) {

            if ($this->isUserOwnsAlbum($album_id, $user_id)) {
                $new_name = $image->getNewName($album_id);
                
                $pairs = array(
                    'name' => $new_name,
                    'type' => $image->getType(),
                    'size' => $image->getSize(),
                    'album_id' => $album_id
                );
                
                $image_id = $this->add($pairs);     // add image meta data to database
                if (!$image_id) {
                    $this->errors[] = 'Database error. Can not upload image.';
                    return false;
                }
                
                $upload_result = $this->uploadImage($album_id, $new_name, $image->getType());     // save image to hard drive
                if ($upload_result) {
                    return true;
                }
                // TODO Add function to delete image from database if upload fails
                $this->errors[] = 'There was an error uploading your image.';
                return false;
            }
            
            $this->errors[] = 'You do not have permission to upload in this album.';
            return false;
        }

        $this->errors = $image->getErrors();
        return false;
    }

    private function isUserOwnsAlbum($album_id, $user_id) {
        $query_params = array(
            'table' => 'albums',
            'columns' => 'id',
            'where' => 'user_id = ?');
        $bind_params = array('i', $user_id);
        
        $result = $this->find($query_params, $bind_params);
        
        if (isset($result[0]['id'])) {
            return true;
        }
        
        return false;
    }
    
    private function uploadImage($album_id, $file_name, $file_type) {        
        $target_path = ALBUMS_PATH . DIRECTORY_SEPARATOR . $album_id .
             DIRECTORY_SEPARATOR . $file_name . '.' . $file_type;
             
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
            
            return true;
        }

        return false;
    }
}
