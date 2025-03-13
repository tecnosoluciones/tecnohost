<?php

namespace NobunaPlugins\Model;

use ArrayObject;

class NobunaProductSet extends ArrayObject {
    
    public static function SetFromAPIRequest($products) {
        $result = new NobunaProductSet;
        reset($products);
        foreach($products as $product) {
            $result->append(NobunaProduct::FromArray($product));
        }
        return $result;
    }
    
    public function getRequiredUpdates() {
        $ids = $this->getIds();
        $products = NobunaProduct::ProductsByIds($ids);
        $updates = new NobunaProductSet();
        reset($this);
        foreach($this as $product) {
            $existing = $products->productById($product->id);
            if($existing === NULL || $existing->differentTo($product)) {
                $updates->append($product);
            }
        }
        return($updates);
    }
    
    public function update() {
        reset($this);
        foreach($this as $product) {
            $product->save();
        }
    }
    
    public function getIds() {
        $ids = array();
        reset($this);
        foreach($this as $product) {
            $ids[] = $product->id;
        }
        return $ids;
    }
    
    public function productById($id) {
        reset($this);
        foreach($this as $product) {
            if($product->id == $id) {
                return $product;
            }
        }
        return NULL;
    }
    
}