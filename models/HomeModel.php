<?php

class HomeModel extends BaseModel {
	
    public function __construct() {
        parent::__construct(array('table' => 'images'));
    }
    
    public function getPublicImagesPaginated($page, $page_size) {
        $query_params = array(
            'columns' => 'images.album_id, images.name, images.type',
            'join' => 'albums ON albums.id = images.album_id',
            'where' => 'albums.is_private = 0',
            'orderby' => 'images.create_date DESC',
            'limit' => '?, ?'
        );
        $bind_params = array($page, $page_size);
        $images = $this->find($query_params, $bind_params);
        
        return $images;
    }
    
    public function getPublicImagesCount() {
        $query_params = array(
            'columns' => 'COUNT(images.id)',
            'join' => 'albums ON albums.id = images.album_id',
            'where' => 'albums.is_private = 0'
        );
        $result = $this->find($query_params);

        return $result[0]['COUNT(images.id)'];
    }
}