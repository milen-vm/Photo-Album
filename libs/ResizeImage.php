<?php

class ResizeImage {
    private $ext;
    private $image;
    private $resize_image;
    private $orig_width;
    private $orig_heigth;
    private $resize_width;
    private $resize_heigth;
    
    public function __construct($filename) {
        if (file_exists($filename)) {
            $this->setImage($filename);
        } else {
            throw new Exception('Image ' . $filename . 'can not be found.');
        }
    }
    
    private function setImage($filename) {
        $size = getimagesize($filename);
        $this->ext = $size['mime'];
        
        switch ($this->ext) {
            case 'image/jpg':
            case 'image/jpeg':
                $this->image = imagecreatefromjpeg($filename);
                break;
            case 'image/gif':
                $this->image = imagecreatefromgif($filename);
                break;
            case 'image/phg':
                $this->image = imagecreatefrompng($filename);
                break;
            default:
                throw new Exception('File type' . $this->ext . 'is not supported.');
        }
        
        $this->orig_width = imagesx($this->image);
        $this->orig_heigth = imagesy($this->image);
    }
    
    public function saveImage($save_path, $image_quality = 100, $download = false) {
        switch ($this->ext) {
            case 'image/jpg':
            case 'image/jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->resize_image, $save_path, $image_quality);
                }
                break;
            case 'image/gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->resize_image, $save_path);
                }
                break;
            case 'image/png':
                $invert_scale_quality = 9 - round(($image_quality / 100) * 9);
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->resize_image, $save_path, $invert_scale_quality);
                }
                break;
        }
        
        if ($download) {
            header('Content-Description: File Transfer');
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename= " . $save_path);
            readfile($save_path);
        }
        
        imagedestroy($this->resize_image);
    }
    
    public function resizeTo($width, $height, $resize_option = 'default') {
        switch (strtolower($resize_option)) {
            case 'exact':
                $this->resize_width = $width;
                $this->resize_heigth = $height; 
                break;
            case 'maxwidth':
                $this->resize_width = $width;
                $this->resize_heigth = $this->resizeHeightByWidth($width);
                break;
            case 'maxheight':
                $this->resize_width = $this->resizeWidthByHeight($height);
                $this->resize_heigth = $height;
                break;
            default:
                $this->resizeDefault($width, $height);
                break;
        }
        
        $this->resize_image = imagecreatetruecolor($this->resize_width, $this->resize_heigth);
        imagecopyresampled($this->resize_image, $this->image, 0, 0, 0, 0,
            $this->resize_width, $this->resize_heigth, $this->orig_width, $this->orig_heigth);
    }
    
    private function resizeHeightByWidth($width) {
        $height = floor(($this->orig_heigth / $this->orig_width) * $width);
        return $height;
    }
    
    private function resizeWidthByHeight($height) {
        $width = floor(($this->orig_width / $this->orig_heigth) * $height);
        return $width;
    }
    
    private function resizeDefault($width, $height) {
        if ($this->orig_width > $width || $this->orig_heigth > $height) {
            
            if ($this->orig_width > $this->orig_heigth) {
                
                $this->resize_width = $width;
                $this->resize_heigth = $this->resizeHeightByWidth($width);
                
            } elseif ($this->orig_width < $this->orig_heigth) {
                
                $this->resize_width = $this->resizeWidthByHeight($height);
                $this->resize_heigth = $height;
                
            } else {
                $this->resize_width = $width;
                $this->resize_heigth = $height; 
            }
        } else {
            $this->resize_width = $width;
            $this->resize_heigth = $height; 
        }
    }
}
