<?php

class ImageModel extends BaseModel {
	private $errors = array();
    
	public function __construct() {
		parent::__construct(array('table' => 'images'));
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
                $image_id = $this->add($pairs);
                echo "image id $image_id";
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
    
    private function uploadImage($album_id) {
        $full_file_name = basename($_FILES['photo']['name']);
        $file_type = pathinfo($full_file_name, PATHINFO_EXTENSION);
        
        // $new_file_name = uniqid('img_');
        // $target_path = ALBUMS_PATH . DIRECTORY_SEPARATOR . $album_id .
            // DIRECTORY_SEPARATOR;
//             
        // while (file_exists($target_path . $new_file_name . '.' . $file_type)) {
            // $new_file_name = uniqid('img_');
        // }
        
        $target_file = $target_path . $new_file_name . '.' . $file_type;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // $this->addInfoMessage('The image '. basename( $_FILES["fileToUpload"]["name"]) . 
                // 'has been uploaded.');
            return true;
        } else {
            $this->addErrorMessage('There was an error uploading your image.');
            return false;
        }
    }
}
