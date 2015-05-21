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
            'columns' => 'id, name, type',
            'where' => 'album_id = ?',
            'orderby' => 'create_date DESC',
            'limit' => '?, ?'
        );
        $bind_params = array($album_id, $page, $page_size);
        $images = $this->find($query_params, $bind_params);

        return $images;
    }
    
    public function getImage($image_id, $user_id = null) {
         $query_params = array(
            'columns' => 'images.name, images.type, images.album_id',
            'join' => 'albums ON albums.id = images.album_id'
         );
         if ($user_id === null) {
             $query_params['where'] = 'images.id = ? AND albums.is_private = 0';
             $bind_params = array($image_id);
         } else {
             $query_params['where'] = 'images.id = ? AND (albums.is_private = 0' .
                 ' OR albums.user_id = ?)';
             $bind_params = array($image_id, $user_id);
         }
         
         $image = $this->find($query_params, $bind_params);
         return $image[0];
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
    
    public function addImage($image, $album_id, $user_id) {
        $img = new Image($image, $album_id);
        if ($img->isValid()) {

            if ($this->isUserOwnsAlbum($album_id, $user_id)) {
                
                $pairs = array(
                    'name' => $img->getNewName(),
                    'type' => $img->getType(),
                    'size' => $img->getSize(),
                    'album_id' => $album_id
                );
                
                $image_id = $this->add($pairs);     // add image meta data to database
                if (!$image_id) {
                    $this->errors[] = 'Database error. Can not upload image.';
                    return false;
                }

                if (move_uploaded_file($img->getTmpName(), $img->getFullPath())) {
                    // TODO Add try catch block to createThumbnail
                    $save_path = $img->getPath() . THUMBS_DIR_NAME . D_S .
                        $img->getFullFilename();
                    $this->createThumbnail($img->getFullPath(), $save_path,
                        THUMBNAIL_WITH, THUMBNAIL_HEIGTH, THUMBS_QUALITY_RATE);
                        
                    return $image_id;
                }
                // TODO Add function to delete image from database if upload fails
                $this->errors[] = 'There was an error uploading your image.';
                return false;
            }
            
            $this->errors[] = 'You do not have permission to upload in this album.';
            return false;
        }

        $this->errors = $img->getErrors();
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
    
    private function createThumbnail($source_path, $save_path, $width, $heigth,
            $quality, $resize_opt = 'default') {
        $resize = new ResizeImage($source_path);
        $resize->resizeTo($width, $heigth, $resize_opt);
        $resize->saveImage($save_path, $quality);
    }
}
