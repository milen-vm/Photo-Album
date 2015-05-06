<?php

abstract class BaseModel {
    protected $table;
    protected $db;
    
    public function __construct($args = array()) {
        $args = array_merge(array(
            'where' => '',
            'columns' => '*',
            'limit' => 0
        ), $args);
        
        if (!isset($args['table'])) {
            die('Table not defined. Please define a model table.');
        }
        
        $this->table = $args['table'];
        
        $db = Database::get_instance();
        $this->db = $db::get_db();
    }
    
    public function find($query_params = array(), $bind_params = array()) {
        $query_params = array_merge( array(
            'table' => $this->table,
            'where' => '',
            'columns' => '*',
            'limit' => 0
        ), $query_params );

        $query = 'SELECT ' . $query_params['columns'] . ' FROM '.
            $query_params['table'] ;
        
        if(!empty($query_params['where'])) {
            $query .= ' WHERE ' . $query_params['where'];
        }
        
        if(!empty($query_params['limit'])) {
            $query .= ' LIMIT ' . $query_params['where'];
        }
        
        $stmt = $this->db->prepare($query);
        
        if (count($bind_params) > 0) {
            call_user_func_array(array($stmt, 'bind_param'),
                $this->makeValuesReferenced($bind_params));
        }
        
        if ($stmt->execute()) {
            $meta = $stmt->result_metadata();
            // This is the tricky bit dynamically creating an array of variables to use
            // to bind the results
            while ($field = $meta->fetch_field()) { 
                $var = $field->name; 
                $$var = null; 
                $fields[$var] = &$$var;
            }
            
            call_user_func_array(array($stmt,'bind_result'), $fields);
            
            // Fetch Results
            $result = array();
            $i = 0;
            while ($stmt->fetch()) {
                $results[$i] = array();
                
                foreach($fields as $k => $v) {
                    $results[$i][$k] = $v;
                }
                    
                $i++;
            }

           return $results;
        } else {
            echo "stmt execut error:" . $stmt->error;
            die;
        }
    }

    private function makeValuesReferenced($arr) {
        if (strnatcmp(phpversion(),'5.3') >= 0) {       // Reference is required for PHP 5.3+
            $ref_arr = array();
        
            foreach($arr as $key => $value) {
                $ref_arr[$key] = &$arr[$key];
            }
            
            return $ref_arr;
        }
        
        return $arr;
    }


}
