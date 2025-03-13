<?php

namespace NBHelpers;

class PaginationSet {
    
    public $itemsPerPage;
    public $itemsCount;
    public $currentPage;
    public $href;
    
    public $firstPageStr = '&lt;&lt;';
    public $prevPageStr = '&lt;';
    public $lastPageStr = '&gt;&gt;';
    public $nextPageStr = '&gt;';
    
    public function __construct($items_per_page, $objects_count, $current_page, $href) {
        $this->itemsPerPage = $items_per_page;
        $this->itemsCount = $objects_count;
        $this->currentPage = $current_page;
        $this->href = $href;
        if($this->currentPage > $this->pagesCount()) {
            $this->currentPage = $this->pagesCount() > 0 ? $this->pagesCount() : 1;
        }
    }
    
    public function offsetIndex() {
        return (($this->currentPage - 1) * $this->itemsPerPage) - 1;
    }
    
    public function lastItemIndex() {
        return ($this->currentPage * $this->itemsPerPage) - 1;
    }
    
    public function pagesCount() {
        static $c = NULL;
        if($c === NULL) {
            $c = ceil($this->itemsCount / $this->itemsPerPage);
        }
        return $c;
    }
    
    public function getListHTML($class = 'pagination', $class_selected = 'selected') {
        if($this->pagesCount() <= 1) {
            return '';
        }
        $pagesCount = $this->pagesCount();
        $out = '';
        $out .= '<ul class="' . $class . '">' . PHP_EOL;
        $out .= $this->getFirstPageLi();
        $out .= $this->getPrevPageLi();
        for($i = 1; $i <= $pagesCount; $i++) {
            $selected = sprintf(' class="%s%s" ', 
                    $i==$this->currentPage ? $class_selected : '',
                    $i==1 ? ' first' : ($i==$pagesCount ? ' last' : ''));
            $out .= '<li '.$selected.'>'.$this->link($i).'</li>' . PHP_EOL;
        }
        $out .= $this->getNextPageLi();
        $out .= $this->getLastPageLi();
        $out .= '</ul>' . PHP_EOL;
        return $out;
    }
    
    protected function getFirstPageLi() {
        if($this->currentPage == 1) {
            return '';
        }
        if($this->pagesCount() == 1) {
            return '';
        }
        $res = sprintf('<li class="firstpage">%s</li>' . PHP_EOL, $this->link(1, $this->firstPageStr));
        return $res;
    }
    
    protected function getLastPageLi() {
        if($this->currentPage >= $this->pagesCount()) {
            return '';
        }
        if($this->pagesCount() == 1) {
            return '';
        }
        $res = sprintf('<li class="lastpage">%s</li>' . PHP_EOL, $this->link($this->pagesCount(), $this->lastPageStr));
        return $res;
    }
    
    protected function getNextPageLi() {
        if($this->currentPage >= $this->pagesCount()) {
            return '';
        }
        if($this->pagesCount() == 1) {
            return '';
        }
        $res = sprintf('<li class="nextpage">%s</li>' . PHP_EOL, $this->link($this->currentPage + 1, $this->nextPageStr));
        return $res;
    }
    
    protected function getPrevPageLi() {
        if($this->currentPage == 1) {
            return '';
        }
        if($this->pagesCount() == 1) {
            return '';
        }
        $res = sprintf('<li class="prevpage">%s</li>' . PHP_EOL, $this->link($this->currentPage - 1, $this->prevPageStr));
        return $res;
    }
    
    protected function link($i, $str = NULL) {
        $str = $str === NULL ? $i : $str;
        $res = $i==$this->currentPage ? $str : sprintf('<a href="%s">%s</a>', $this->href($i), $str);
        return $res;
    }
    
    protected function href($i) {
        return str_replace('{page}', $i, $this->href);
    }
}
