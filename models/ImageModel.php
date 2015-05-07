<?php

class ImageModel extends BaseModel {
	
	public function __construct() {
		parent::__construct(array('table' => 'images'));
	}
    
    public function addImage($album_id, $user_id) {
        $image = new Image();
        if ($image->isValid()) {
            
        }

        $this->errors = $image->getErrors();
        return false;
    }
    
    private function isUserOwnsAlbum($album_id, $user_id) {
        $this->find(array(
            ));
    }
}
