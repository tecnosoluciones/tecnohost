<?php

namespace NobunaPlugins\Model;

class DBIndex {
    
    const MODE_ASC = 'ASC';
    const MODE_DESC = 'DESC';
    
    const TYPE_INDEX = 'INDEX';
    const TYPE_UNIQUE = 'UNIQUE INDEX';
    
    public $type;
    public $name;
    public $fields;
    
    public function __construct($name, $type, $fields) {
        $this->name = $name;
        $this->type = $type;
        $this->fields = $fields;
    }
    
    public function getCreateLine() {
        $sql_tpl = '%s `%s` (%s)';
        $fields_arr = array();
        reset($this->fields);
        foreach($this->fields as $f => $m) {
            $fields_arr[] = sprintf('`%s` %s', $f, $m);
        }
        $fields_sql = implode(', ', $fields_arr);
        $res = sprintf($sql_tpl, $this->type, $this->name, $fields_sql);
        return $res;
    }
    
}
