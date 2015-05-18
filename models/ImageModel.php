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
                
                $upload_result = $this->uploadImage($album_id, $image->getNewName(),
                    $image->getType());     // save image to hard drive
                $this->createThumbnail($album_id, $image->getNewName(),
                    $image->getType(), THUMBNAIL_WITH_SIZE);
                if ($upload_result) {
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
    
    private function uploadImage($album_id, $file_name, $file_type) {        
        $target_path = ALBUMS_PATH . D_S . $album_id .
             D_S . $file_name . '.' . $file_type;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
            
            return true;
        }

        return false;
    }
    
    private function createThumbnail($album_id, $file_name, $file_type, $new_width) {
        $target_path = ALBUMS_PATH . D_S . $album_id .
            D_S . $file_name . '.' . $file_type;
        if (!file_exists($target_path)) {
            die('Cannot create thumbnail. Source image do not exist.');
        }

        list($width, $height) = getimagesize($target_path);
        $mime_type = end(getimagesize($target_path));

        $ratio = $width / $height;
        $new_heigth = (int)round($new_width / $ratio);
        
        switch (strtolower($mime_type)) {
            case 'image/png':
                $image = imagecreatefrompng($target_path);
                break;
            case 'image/jpeg':
                $image = imagecreatefromjpeg($target_path);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($target_path);
                break;
            default:
                die('Invalid image file type.');
                break;
        }
        
        $thumbnail = imagecreatetruecolor($new_width, $new_heigth);
        imagecopyresized($thumbnail, $image, 0, 0, 0, 0,
            $new_width, $new_heigth, $width, $height);
        
        $save_path = ALBUMS_PATH . D_S . $album_id . D_S .
            THUMBS_DIR_NAME . D_S . $file_name . '.' . $file_type;
            
        imagejpeg($thumbnail, $save_path);
        imagedestroy($thumbnail);
    }

    // private function createThumbnail($album_id, $file_name, $file_type) {
        // $target_path = ALBUMS_PATH . D_S . $album_id . D_S .
            // 'thumbs' . D_S . $file_name . '.' . $file_type;
//             
        // $thumb = new Imagick();
        // $thumb->readImage($_FILES['photo']['tmp_name']);
        // $thumb->resizeImage(THUMBNAIL_WITH_SIZE, 0, Imagick::FILTER_LANCZOS, 1);
        // $thumb->writeImage($target_path);
        // $thumb->clear();
        // $thumb->destroy(); 
    // }
}
