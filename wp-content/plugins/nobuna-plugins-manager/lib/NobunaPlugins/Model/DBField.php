<?php

namespace NobunaPlugins\Model;

class DBField {
    public $name;
    public $type;
    public $nullable = FALSE;
    public $default = NULL;
    public $autoincrement = FALSE;
    
    public function __construct($name, $type, $nullable = FALSE, $default = NULL, $auto_increment = FALSE) {
        $this->name = $name;
        $this->type = $type;
        $this->nullable = $nullable;
        $this->default = $default;
        $this->autoincrement = $auto_increment;
    }
    
    public function getCreateLine() {
        $tpl = '`%s` %s %s%s%s';
        $default = ($this->default !== NULL) ? sprintf(' DEFAULT \'%s\'', $this->default) : '';
        $autoincrement = ($this->autoincrement === TRUE) ? ' AUTO_INCREMENT' : '';
        $nullable = $this->nullable === TRUE ? 'NULL' : 'NOT NULL';
        $res = sprintf($tpl, $this->name, $this->type, $nullable, $default, $autoincrement);
        return $res;
    }
    
}
