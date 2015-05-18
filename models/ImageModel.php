<?php

class ImageModel extends BaseModel {
	private $errors = array();
    
	public function __construct() {
		parent::__construct(array('table' => 'images'));
	}
    
    public function getErrors() {
        return $this->errors;
    }
    public function getImagesPaginated($album_id, $page, $page_size) {
        $query_params = array(
            'columns' => 'name, type',
            'where' => 'album_id = ?',
            'orderby' => 'create_date DESC',
            'limit' => '?, ?'
        );
        $bind_params = array($album_id, $page, $page_size);
        $images = $this->find($query_params, $bind_params);

        return $images;
    }
    
    public function getCount($album_id) {
        $query_params = array(
            'columns' => 'COUNT(id)',
            'where' => 'album_id = ?'
        );
        $bind_params = array($album_id);
        $result = $this->find($query_params, $bind_params);

        return $result[0]['COUNT(id)'];
    }
    
    public function getAlbumData($album_id) {
        $query_params = array(
            'table' => 'albums',
            'columns' => 'name, description',
            'where' => 'id = ?'
        );
        $bind_params = array($album_id);
        $result = $this->find($query_params, $bind_params);
        
        return $result[0];
    }
    
    public function addImage($album_id, $user_id) {
        $image = new Image($album_id);
        if ($image->isValid()) {

            if ($this->isUserOwnsAlbum($album_id, $user_id)) {
                
                $pairs = array(
                    'name' => $image->getNewName(),
                    'type' => $image->getType(),
                    'size' => $image->getSize(),
                    'album_id' => $album_id
                );
                
                $image_id = $this->add($pairs);     // add image meta data to database
                if (!$image_id) {
                    $this->errors[] = 'Database error. Can not upload image.';
                    return false;
                }
                
                $upload_result = $this->uploadImage($image->getFullPath());     // save image to hard drive
                
                
                if ($upload_result) {
                    // TODO Add try catch block to createThumbnail
                    $save_path = $image->getPath() . THUMBS_DIR_NAME . D_S .
                        $image->getFullFilename();
                    $this->createThumbnail($image->getFullPath(), $save_path,
                        THUMBNAIL_WITH_SIZE, 0);
                        
                    return $image_id;
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

    public function isUserOwnsAlbum($album_id, $user_id) {
        $query_params = array(
            'table' => 'albums',
            'columns' => 'user_id', 
            'where' => 'id = ?');
        $bind_params = array($album_id);
        
        $result = $this->find($query_params, $bind_params);
        
        if (isset($result[0]['user_id'])) {
            return $result[0]['user_id'] == $user_id;
        }
        
        return false;
    }
    
    private function uploadImage($path) {
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $path)) {
            
            return true;
        }

        return false;
    }
    
    private function createThumbnail($source_path, $save_path, $width, $heigth, $resize_opt = 'default') {
        $resize = new ResizeImage($source_path);
        $resize->resizeTo($width, $heigth, $resize_opt);
        $resize->saveImage($save_path, 75);
    }
}
