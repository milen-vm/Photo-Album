<?php

class Pagination {
    private $total_items;
    private $current_page;
    private $total_pages;
    
    public function __construct($total_items) {
        $this->setTotalItems($total_items);
        $this->setTotalPages();
        $this->setCurrentPage();
    }

    private function setTotalItems($total_items) {
        if (is_numeric($total_items)) {
            $total_items =  $total_items + 0;
            
            if ($total_items < 1) {
                die('Total items count cannot be zero or negative');
            }
            
            if (!is_int($total_items)) {
                die('Toral items count must be an integer.');
            }
            
            $this->total_items = $total_items;
        } else {
             die('Total items count must be a number');
        }
    }
    
    // public function getTotalPages() {
        // return 
    // }
    
    private function setTotalPages() {
        $this->total_pages = ceil($this->total_items / ALBUMS_PAGE_SIZE);
    }
    
    public function getCurrentPage() {
        return $this->current_page;
    }
    
    private function setCurrentPage() {
        if (isset($_GET['page'])) {
           $this->current_page = intval($_GET['page']);
        } else {
           $this->current_page = 1;
        }
        
        if ($this->current_page < 1) {
            $this->current_page = 1;
        }
        
        if ($this->current_page > $this->total_pages) {
            $this->current_page = $this->total_pages;
        }
    }
    
    public function getOffset() {
        if ($this->current_page == 0) {
            return 0;
        }
        
        return ($this->current_page - 1) * ALBUMS_PAGE_SIZE;
    }
    
    public function includePangination() {
        $main_url = reset(explode('?', $_SERVER['REQUEST_URI']));
        $this->first_page_url = $main_url . '?page=1';
        $this->prev_page_url = $main_url . '?page=' . ($this->current_page - 1);
        $this->next_page_url = $main_url . '?page=' . ($this->current_page + 1);
        $this->last_page_url = $main_url . '?page=' . $this->total_pages;
        $this->last_page = $this->total_pages;
        $this->curr_page = $this->current_page;
        
        return include_once 'Pager.php';
    }
}