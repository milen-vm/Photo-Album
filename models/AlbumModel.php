<?php

class AlbumModel extends BaseModel {
	
	public function __construct() {
		parent::__construct();
	}
    
    public function create($name, $description, $is_private) {
        $album = new Album($name, $description, $is_private);
        if ($album->isValid()) {
            
        } else {
            
        }
        
        var_dump($album);
    }
}
