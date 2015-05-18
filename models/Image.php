<?php

class Image {
    private $name;
    private $new_name;
    private $type;
    private $size;
    private $errors = array();

    public function __construct($album_id) {
        $this->name = basename($_FILES['photo']['name']);
        $this->type = strtolower(pathinfo(basename($_FILES['photo']['name']),
            PATHINFO_EXTENSION));
        $this->size = $_FILES['photo']['size'];
        $this->setNewName($album_id);
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getSize() {
        return $this->size;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getNewName() {
        return $this->new_name;
    }
    
    private function setNewName($album_id) {
        $new_file_name = uniqid(IMAGE_NAME_PREFIX);
        $target_path = ALBUMS_PATH . D_S . $album_id . D_S;
        // TODO Check is $target_path exist
        while (file_exists($target_path . $new_file_name . '.' . $this->getType())) {
            $new_file_name = uniqid(IMAGE_NAME_PREFIX);
        }
        
        $this->new_name = $new_file_name;
    }

    public function isValid() {
        $this->validateImage();

        return count($this->errors) === 0;
    }

    private function validateImage() {
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check === false) {
            $this->errors[] = 'File is not a image.';
            return;
        }

        if ($_FILES['photo']['size'] > MAX_IMAGE_FILE_SIZE) {
            $this->errors[] = 'Max image file size is ' . MAX_IMAGE_FILE_SIZE . ' b.';
            return;
        }

        $file_type = $this->type;
        if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg' && $file_type != 'gif') {
            $this->errors[] = 'Aloed image formats are only JPG, JPEG, PNG & GIF.';
        }
    }
}
