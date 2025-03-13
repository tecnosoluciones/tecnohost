<?php

namespace NobunaPlugins\Model;

use Exception;

abstract class BaseModel {

    abstract protected static function GetFields();

    protected static function GetCreateStatement() {
        $charset_collate = static::get_wp_charset_collate();
        $table_name = static::TableName();
        $fields = static::GetFields();
        $indexes = isset($fields['indexes']) ? $fields['indexes'] : array();
        $index_sql = '';
        if(count($indexes) > 0) {
            $index_sql = ', ';
            $indexes_arr = array();
            foreach($indexes as $index) {
                $indexes_arr[] = $index->getCreateLine();
            }
            $index_sql .= implode(', ', $indexes_arr);
        }
        $fields_sql_array = array();
        reset($fields['fields']);
        foreach ($fields['fields'] as $dbField) {
            /* @var $dbField DBField */
            $fields_sql_array[] = $dbField->getCreateLine();
        }
        $fields_sql = implode(', ', $fields_sql_array);
        $keys_sql = implode('`, `', $fields['primary_key']);
        $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            $fields_sql,
            PRIMARY KEY (`$keys_sql`)
            $index_sql
            ) $charset_collate;";
        return $sql;
    }

    protected static function get_wp_charset_collate() {
        global $wpdb;
        $charset_collate = '';
        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        }
        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE $wpdb->collate";
        }

        return $charset_collate;
    }

    public static function FromArray($data, $res = NULL) {
        reset($data);
        $fields = static::GetFields();
        $alias = isset($fields['alias']) ? $fields['alias'] : array();
        $booleans = isset($fields['booleans']) ? $fields['booleans'] : array();
        $json_array = isset($fields['json_array']) ? $fields['json_array'] : array();
        if ($res === NULL) {
            $class = get_called_class();
            $res = new $class();
        }
        foreach ($data as $key => $value) {
            if(isset($alias[$key])) {
                $key = $alias[$key];
            }
            if(array_search($key, $json_array) !== FALSE && is_string($value)) {
                $value = json_decode($value);
            }
            if(array_search($key, $booleans) !== FALSE && ($value == 0 || $value == 1)) {
                $value = intval($value) <= 0 ? FALSE : TRUE;
            }
            if (property_exists($res, $key)) {
                $res->$key = $value;
            }
        }
        return $res;
    }

    public static function All() {
        global $wpdb;
        $table = static::TableName();
        $sql = "SELECT * FROM `$table`";
        $results = $wpdb->get_results($sql);
        $set_class = get_called_class() . 'Set';
        $result = new $set_class();
        foreach($results as $item) {
            $result->append(static::FromArray($item));
        }
        return $result;
    }
    
    protected static function GetById($id) {
        $row = static::GetArrayById($id);
        if($row !== NULL) {
            $res = static::FromArray((array) $row);
        }
        return $res;
    }

    protected static function GetArrayById($id) {
        global $wpdb;
        $fields = static::GetFields();
        if(count($fields['primary_key']) > 1) {
            throw new Exception('There is more than one primary key');
        }
        $field_name = $fields['primary_key'][0];
        $table = static::TableName();
        $sql = sprintf('SELECT * FROM `%s` WHERE `%s` = %d', $table, $field_name, $id);
        $row = $wpdb->get_row($sql);
        return $row;
    }

    protected static function SearchByFields($fields) {
        global $wpdb;
        $table = static::TableName();
        
        $whereArray = array();
        foreach($fields as $field_name => $field_value) {
            $whereArray[] = sprintf('`%s` = \'%s\'', $field_name, $field_value);
        }
        $where = implode(', ', $whereArray);
        
        $sql = "SELECT * FROM `$table` WHERE $where";
        $results = $wpdb->get_results($sql);
        $items = array();
        foreach($results as $result) {
            $items[] = static::FromArray($result);
        }
        return $items;
    }
    
    public function __construct($id = NULL) {
        if ($id !== NULL) {
            $res = static::GetArrayById($id);
            if($res !== NULL) {
                static::FromArray($res, $this);
            }
        }
    }

    public function differentTo(BaseModel $model) {
        $fields_array = static::GetFields();
        $fields = array_keys($fields_array['fields']);
        foreach($fields as $key) {
            if($this->$key != $model->$key) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    public function save($update_insert_id = FALSE) {
        $existing = NULL;
        if ($this->id !== NULL) {
            $existing = static::GetById($this->id);
        }
        if($existing !== NULL) {
            static::UpdateExisting($this);
        } else {
            static::InsertNew($this, $update_insert_id);
        }
    }

    protected static function InsertNew(BaseModel $obj, $update_insert_id = FALSE) {
        global $wpdb;
        $fields = static::GetFields();
        $table = static::TableName();
        $fieldsValues= static::GetFieldsAndValuesForDB($obj);
        $wpdb->insert($table, $fieldsValues);
        if($update_insert_id === TRUE) {
            $pk = $fields['primary_key'];
            if(count($pk) === 1) {
                $k = $pk[0];
                $obj->$k = $wpdb->insert_id;
            }
        }
    }

    protected static function UpdateExisting(BaseModel $obj) {
        global $wpdb;
        $table = static::TableName();
        $fieldsValues = static::GetFieldsAndValuesForDB($obj, TRUE);
        $fields_sql = implode(', ', $fieldsValues);
        $where_sql = static::GetPrimaryKeyWhere($obj);
        $sql_tpl = 'UPDATE `%s` SET %s WHERE %s';
        $sql = sprintf($sql_tpl, $table, $fields_sql, $where_sql);
        $wpdb->query($sql);
    }

    protected static function GetPrimaryKeyWhere(BaseModel $obj) {
        $fields = static::GetFields();
        $pks = $fields['primary_key'];
        $values_array = array();
        foreach($pks as $pk) {
            $values_array[] = sprintf('`%s` = \'%s\'', $pk, $obj->$pk);
        }
        $res = implode(' AND ', $values_array);
        return $res;
    }
    
    protected static function GetFieldsAndValuesForDB(BaseModel $obj, $for_update = FALSE) {
        $res = array();
        $fields = static::GetFields();
        $json_array = isset($fields['json_array']) ? $fields['json_array'] : array();
        $booleans = isset($fields['booleans']) ? $fields['booleans'] : array();
        $keys = array_keys($fields['fields']);
        reset($keys);
        foreach($keys as $key) {
            if(!property_exists($obj, $key)) {
                throw new Exception(sprintf('Invalid property: %s', $key));
            }
            $is_key = array_search($key, $fields['primary_key']) !== FALSE;
            $is_boolean = array_search($key, $booleans) !== FALSE;
            if($is_key && $for_update === TRUE) {
                continue;
            }
            if($is_key && $obj->$key === NULL) {
                continue;
            }
            $val = $obj->$key;
            if(array_search($key, $json_array) !== FALSE) {
                if(!is_array($val)) { $val = array(); }
                $val = json_encode($val);
            }
            if($is_boolean && is_bool($val)) {
                $val = $val ? 1 : 0;
            }
            $val_str = 'NULL';
            if($val !== NULL) { $val_str = "'$val'"; };
            if($for_update === TRUE) {
                $res[] = sprintf('`%s` = %s', $key, $val_str);
            } else {
                $res[$key] = $val;
            }
        }
        return $res;
    }
    
    public static function TableName() {
        global $wpdb;
        return $wpdb->prefix . static::TABLE_NAME;
    }

    public static function CharsetCollate() {
        global $wpdb;
        return $wpdb->get_charset_collate();
    }

}
