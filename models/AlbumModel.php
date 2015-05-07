<?php

class AlbumModel extends BaseModel {
	private $errors = array();
    
	public function __construct() {
		parent::__construct(array('table' => 'albums'));
	}
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getAll($user_id, $page, $page_size) {
        $query_params = array(
            'columns' => 'id, name',
            'where' => 'user_id = ?',
            'limit' => '?, ?'
        );
        $bind_params = array( 'iii', $user_id, $page, $page_size);
        
        $albums = $this->find($query_params, $bind_params);
        
        return $albums;
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
        $query = 'INSERT INTO albums (name, description, is_private, user_id)' . //  
            ' VALUES (?, ?, ?, ?)';
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param('ssii', $album->getName(), $album->getDescription(),
                $album->getIsPrivate(), $user_id);
                
            if ($stmt->execute()) {
                return $stmt->insert_id;
            }
            
            // printf("Error message: %s\n", $stmt->error);
            $this->errors[] = 'Data base error.';
            return false;
        }
        
        // printf("Error message: %s\n", $this->db->error);
        $this->errors[] = 'Data base error.';
        return false;
    }
}
