<?php

class HomeController extends BaseController {
    private $model;
    
    public function __construct() {
        parent::__construct('home');
        $this->model = new HomeModel();
    }
    
    public function index($page = 0, $page_size = IMAGES_PAGE_SIZE) {
        
        $public_images_count = $this->model->getPublicImagesCount();

        $total_pages = ($public_images_count + $page_size - 1) / $page_size;
        if (($page + 1) > $total_pages) {
            $page -= 1;
        }
        
        if ($page < 0) {
            $page = 0;
        }

        $this->page = $page;
        $this->page_size = $page_size;
        $start = $page * $page_size;

        $images = $this->model->getPublicImagesPaginated($start, $page_size);
        
        $this->images_paths = array();
        foreach ($images as $image) {
            $path = '/' . DX_ROOT_PATH . ALBUMS_PATH . '/' . $image['album_id'] .
                '/' . $image['name'] . '.' . $image['type'];
            $this->images_paths[] = $path;
        }
        
        $this->renderView();
    }
}
