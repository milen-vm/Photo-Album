<?php

class Pagination {
    private $total_items;
    private $page_size;
    private $current_page;
    private $total_pages;
    
    public function __construct($total_items, $page_size) {
        $this->setTotalItems($total_items);
        $this->setPageSize($page_size);
        $this->setTotalPages();
        $this->setCurrentPage();
    }

    private function setTotalItems($total_items) {
        if (is_numeric($total_items)) {
            $total_items =  $total_items + 0;
            
            if ($total_items < 0) {
                die('Total items count cannot be negative');
            }
            
            if (!is_int($total_items)) {
                die('Toral items count must be an integer.');
            }
            
            $this->total_items = $total_items;
        } else {
             die('Total items count must be a number');
        }
    }
    
    private function setPageSize($page_size) {
        if (is_numeric($page_size)) {
            $page_size = $page_size + 0;
            
            if ($page_size < 1) {
                die('Page size cannot be zero or negative');
            }
            
            if (!is_int($page_size)) {
                die('Page size must be an integer.');
            }
            
             $this->page_size = $page_size;
        } else {
            die('Page size must be a number');
        }
    }
    
    private function setTotalPages() {
        if ($this->total_items === 0) {
            $this->total_pages = 1;
        } else {
            $this->total_pages = ceil($this->total_items / $this->page_size);
        }
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
        if ($this->current_page < 1) {
            return 0;
        }
        
        return ($this->current_page - 1) * $this->page_size;
    }
    
    public function includePangination() {
        $main_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->first_page_url = $main_url . '?page=1';
        $this->prev_page_url = $main_url . '?page=' . ($this->current_page - 1);
        $this->next_page_url = $main_url . '?page=' . ($this->current_page + 1);
        $this->last_page_url = $main_url . '?page=' . $this->total_pages;
        
        return include_once 'Pager.php';
    }
}