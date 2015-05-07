<?php
 
 class Image {
     private $name;
     private $type;
     private $size;
     private $errors = array();
     
     public function __construct() {
         $full_file_name = basename($_FILES['photo']['name']);
         $this->name = basename($_FILES['photo']['name']);
         $this->type = pathinfo($full_file_name, PATHINFO_EXTENSION);
         $this->size = $_FILES['photo']['size'];
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
     
     public function getNewName($album_id) {
         $new_file_name = uniqid('img_');
         $target_path = ALBUMS_PATH . DIRECTORY_SEPARATOR . $album_id .
             DIRECTORY_SEPARATOR;
            
         while (file_exists($target_path . $new_file_name . '.' . $this->getType())) {
             $new_file_name = uniqid('img_');
         }
         
         return $new_file_name;
     }
     
     public function isValid() {
        $this->validateImage();
        
        return count($this->errors) === 0;
    }
     
    private function validateImage() {
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if($check === false) {
            $this->errors[] = 'File is not a image.';
            return;
        }

        if ($_FILES['photo']['size'] > MAX_IMAGE_FILE_SIZE) {
            $this->errors[] = 'Max image file size is ' .
                MAX_IMAGE_FILE_SIZE . ' b.';
            return;
        }
        
        $full_file_name = basename($_FILES['photo']['name']);
        $file_type = pathinfo($full_file_name, PATHINFO_EXTENSION);
        if($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg'
                && $file_type != 'gif' ) {
            $this->errors[] = 'Aloed image formats are only JPG, JPEG, PNG & GIF.';
        }
    }
 }
 
