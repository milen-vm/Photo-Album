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

    public function add($pairs) {
        $columns = array_keys($pairs);
        $bind_params = array_values($pairs);
        $params_count = count($bind_params);

        $types = $this->generateTypesString($bind_params);
        array_unshift($bind_params, $types);

        $placeholders = str_repeat('?,', $params_count);
        $placeholders = rtrim($placeholders, ',');
       
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $columns) . ') ' .
            'VALUES (' . $placeholders . ')';

        if ($stmt = $this->db->prepare($query)) {
            call_user_func_array(array($stmt, 'bind_param'),
                $this->makeValuesReferenced($bind_params));
            if ($stmt->execute()) {
                return $stmt->insert_id;
            }
            // printf("Error message: %s\n", $stmt->error);
            return false;
        }
        // printf("Error message: %s\n", $this->db->error);
        return false;
    }
    
    // TODO Add using function generateTypesString and extract build query function
    public function find($query_params = array(), $bind_params = array()) {
        $query_params = array_merge( array(
            'table' => $this->table,
            'columns' => '*',
            'where' => '',
            'limit' => 0
        ), $query_params );

        $query = 'SELECT ' . $query_params['columns'] . ' FROM '.
            $query_params['table'] ;
        
        if(!empty($query_params['where'])) {
            $query .= ' WHERE ' . $query_params['where'];
        }
        // TODO Add order by option in query
        if(!empty($query_params['limit'])) {
            $query .= ' LIMIT ' . $query_params['limit'];
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
                $result[$i] = array();
                
                foreach($fields as $k => $v) {
                    $result[$i][$k] = $v;
                }
                    
                $i++;
            }

           return $result;
        } else {
            echo "stmt execut error:" . $stmt->error;
            die;
        }
    }

    private function generateTypesString($params) {
        $types = '';                        
        foreach($params as $param) {        
            if(is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
        }
    
        return $types;
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
