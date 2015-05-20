<?php

class HomeController extends BaseController {
    private $model;
    
    public function __construct() {
        parent::__construct('home');
        $this->model = new HomeModel();
    }
    
    public function index() {
        
        $public_images_count = $this->model->getPublicImagesCount();
        $this->pagination = new Pagination($public_images_count, IMAGES_PAGE_SIZE);
        
        $start = $this->pagination->getOffset();
        $images = $this->model->getPublicImagesPaginated($start, IMAGES_PAGE_SIZE);
        $this->images_data = array();
        
        if (count($images) === 0) {
            $this->addInfoMessage('No public images. Login to create your on albums.');
        } else {
            $main_path = ALBUMS_PATH . D_S;
            $this->images_data = $this->makeBase64($images, $main_path);
            
            if (count($this->images_data) === 0) {
                $this->addErrorMessage('Public images are broken.');
            }
        }

        $this->renderView();
    }
}
