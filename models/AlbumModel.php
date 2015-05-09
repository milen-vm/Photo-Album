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
            return $this->createAlbum($album, $user_id);
        }
        
        $this->errors = $album->getErrors();
        return false;
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
            return false;
        }

        return $album_id;
    }       
}
