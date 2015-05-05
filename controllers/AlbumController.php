<?php

class AlbumController extends BaseController {
	private $model;
    
	function __construct() {
		parent::__construct('album');
        $this->model = new AlbumModel();
	}
    
    // public function index() {
        // $this->renderView();
    // }
    
    public function create() {
        $this->authorize();
        if ($this->is_post) {
            var_dump($_POST);
            $name = $_POST['name'];
            $description = $_POST['description'];
            $is_private = isset($_POST['is_private']) ? $_POST['is_private'] : '';
            
            $create_result = $this->model->create($name, $description, $is_private);
        }
        $this->renderView(__FUNCTION__);
    }
}
