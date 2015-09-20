<?php

class Image {
    private $tmp_name;
    private $new_name;
    private $type;
    private $size;
    private $path;
    private $errors = array();

    public function __construct($image, $album_id) {
        $this->tmp_name = $image['tmp_name'];
        $this->type = strtolower(pathinfo(basename($image['name']),
            PATHINFO_EXTENSION));
        $this->size = $image['size'];
        $this->setPath($album_id);
        $this->setNewName($album_id);
    }

    public function getTmpName() {
        return $this->tmp_name;
    }
    
    public function getNewName() {
        return $this->new_name;
    }

    public function getType() {
        return $this->type;
    }

    public function getSize() {
        return $this->size;
    }
    
    public function getPath() {
        return $this->path;
    }
    
    private function setPath($album_id) {
        $this->path = ALBUMS_PATH . D_S . $album_id . D_S;
    }
    
    private function setNewName($album_id) {
        $new_file_name = uniqid(IMAGE_NAME_PREFIX);
        // TODO Check is $target_path exist
        while (file_exists($this->path . $new_file_name . '.' . $this->type)) {
            $new_file_name = uniqid(IMAGE_NAME_PREFIX);
        }
        
        $this->new_name = $new_file_name;
    }
    
    public function getFullPath() {
        $full_path = $this->path . $this->getFullFilename();
        return $full_path;
    }
    
    public function getFullFilename() {
        $full_filename = $this->new_name . '.' . $this->type;
        return $full_filename;
    }
    
    public function getErrors() {
        return $this->errors;
    } 

    public function isValid() {
        $this->validateImage();

        return count($this->errors) === 0;
    }

    private function validateImage() {
        $check = getimagesize($this->tmp_name);
        if ($check === false) {
            $this->errors[] = 'File is not a image.';
            return;
        }

        if ($this->size > MAX_IMAGE_FILE_SIZE) {
            $this->errors[] = 'Max image file size is ' . MAX_IMAGE_FILE_SIZE . ' b.';
            return;
        }

        $file_type = $this->type;
        if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg' && $file_type != 'gif') {
            $this->errors[] = 'Aloed image formats are only JPG, JPEG, PNG & GIF.';
        }
    }
}
