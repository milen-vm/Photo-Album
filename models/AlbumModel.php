<?php

class AlbumModel extends BaseModel {
	private $errors = array();
    
	public function __construct() {
		parent::__construct(array('table' => 'albums'));
	}
    
    public function getErrors() {
        return $this->errors;
    }
    // TODO Rename to get whit pagination and create function getAll
    public function getAll($user_id, $start, $page_size) {
        $query_params = array(
            'columns' => 'id, name, description',
            'where' => 'user_id = ?',
            'orderby' => 'create_date DESC',
            'limit' => '?, ?'
        );
        $bind_params = array($user_id, $start, $page_size);
        $albums = $this->find($query_params, $bind_params);
        
        return $albums;
    }
    
    public function getCount($user_id) {
        $query_params = array(
            'columns' => 'COUNT(id)',
            'where' => 'user_id = ?'
        );
        $bind_params = array($user_id);
        $result = $this->find($query_params, $bind_params);

        return $result[0]['COUNT(id)'];
    }
    
    public function create($name, $description, $is_private, $user_id) {
        $album = new Album($name, $description, $is_private);
        if ($album->isValid()) {
            $album_id = $this->createAlbum($album, $user_id);
            if ($album_id != null) {
                if ($this->createAlbumDirectories($album_id)) {
                    return $album_id;
                }
                
                return null;
            }
            
            return null;
        }
        
        $this->errors = $album->getErrors();
        return null;
    }
    
    private function createAlbum($album, $user_id) {
        $pairs = array(
                    'name' => $album->getName(),
                    'description' => $album->getDescription(),
                    'is_private' => $album->getIsPrivate(),
                    'user_id' => $user_id
                );
        $album_id = $this->add($pairs);
        if (!$album_id) {
            $this->errors[] = 'Database error. Can not upload image.';
            return null;
        }

        return $album_id;
    }
    
    private function createAlbumDirectories($album_id) {
        $path = ALBUMS_PATH . D_S . $album_id;
                
        if (!$this->makeDir($path)) {
            $this->errors[] = 'Error to create album directory.';
            // TODO Delete album from database if directory creation fails
            return false;
        }
        
        if (!$this->makeDir($path . D_S . THUMBS_DIR_NAME)) {
            $this->errors[] = 'Error to create thumbnail directory.';
            return false;
        }
        
        return true;
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
}
