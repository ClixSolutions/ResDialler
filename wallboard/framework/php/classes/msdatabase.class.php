<?php

  class msdatabase {
    
    private static $instance;
    private static $connection;
    
    public function prepared_statement($query, $casts, $preparedParams )
    {
      $results = False;
      
      if ($results == False)
      {
        $statement = self::$connection->prepare( $query );
        call_user_func_array('mysqli_stmt_bind_param', array_merge(array($statement, $casts), self::refValues($preparedParams)));
      
        $statement->execute();
      }
      return self;
    }
    
    public function prepared_query( $cacheName, $query, $casts = False, $preparedParams = False )
    {
      $results = False;
      
      if ($results == False)
      {
        $statement = self::$connection->prepare( $query );
        if ($casts != False)
          call_user_func_array('mysqli_stmt_bind_param', array_merge(array($statement, $casts), self::refValues($preparedParams)));
      
        $statement->execute();
      
        $meta = $statement->result_metadata();

        while ($field = $meta->fetch_field()) 
        { 
          $params[] = &$row[$field->name]; 
        }
      
        call_user_func_array(array($statement, 'bind_result'), $params);

        while ($statement->fetch())
        { 
          $c = array();
          foreach($row as $key => $val) 
          { 
              $c[$key] = $val; 
          } 
          $results[] = $c; 
        }
      
        $statement->close();
      }
      
      return $results;
      
    }
    
    
    public function getById( $cacheName, $id, $table, $fields="*", $idField="id" )
    {
      
      $results = self::prepared_query( $cacheName, "SELECT ".self::constructFields($fields)." FROM ".$table." WHERE ".$idField." = ?;",
                                       "i", array($id) );
      
      return $results;
      
    }
    
    
    /*************************************
    ** Private Functions
    */
    
    private function constructFields( $fields )
    {
      $i = 0;
      $len = count($fields);
      $fieldString = "";
      if ($len > 1) {
        foreach ($fields AS $field) {
          $fieldString .= $field;
          if ($i != $len - 1) {
            $fieldString .= ", ";
          }
          $i++;
        }
      } else {
        $fieldString = $fields;
      }
      return $fieldString;
    }
    
    
    // Fixer for php 5.3+
    public function refValues($arr)
    { 
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+ 
        { 
            $refs = array(); 
            foreach($arr as $key => $value) 
                $refs[$key] = &$arr[$key]; 
             return $refs; 
         } 
         return $arr; 
    }
    
    
    /*************************************
    ** Constructors and Destructors
    */
    
        
    public static function connect($config)
    {
      if (!self::$instance)
      {
        self::$instance = new self($config);
      }
      return self::$instance;
    }
    
    private function __construct($config)
    {
      // Connect to the server
      self::$connection = mssql_connect( $config['host'], $config['user'], $config['pass'] );
      if(mysqli_connect_errno())
      {
        echo "Couldn't connect to the MS database (No surprise there!)";
        exit();
      } else {
      	mssql_select_db($config['database'], self::$connection);
      }
      
    }
    
  }




?>

