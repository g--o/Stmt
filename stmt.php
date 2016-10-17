<?php
// This class is wrapping prepared statments so you can use
// them like any normal query.
// Made by g_o

class Stmt // Wrapping class for mysqli_stmt
{
	var $stmt = null; // Stmt holder
	
	function __construct($con, $statement, $params, $types=null) // Make a prepared statement using connection to DB, statement and params
	{			
		if($types==null) { // If types aren't specified, specify it automatically!
			$types=$this->getTypes($params);
		}
		
		if($this->stmt = $con->prepare($statement)) { // Prepare the statement
			call_user_func_array(array($this->stmt, "bind_param"), array_merge(array($types), $params)); // Bind the parameter
			$this->stmt->execute(); // Execute the prepared statement
			$this->stmt->store_result();
		}
		else
			die("Error trying to prepare statement: ".$con->error);
	}
	
	function __destruct() // Close the stmt
	{
		$this->close();
	}
	
	function getTypes($params) // Get param types for prepared statements
	{
		$table = array("string"=>"s", "integer"=>"i", "double"=>"d", "object"=>"b");
		$res = array();
		$count = 0;
		
		foreach($params as &$var) { 
			$res[$count] = $table[gettype($var)];
			$count++;
		}
		
		return implode("",$res);
	}
	
	function getNumRows() // Get statment num_rows variable
	{
		return $this->stmt->num_rows;
	}
	
	function fetchRow() // Thanks to Masterkitano from php.net!
    {
        if($this->stmt->num_rows>0)
        {
            $result = array();
            $md = $this->stmt->result_metadata();
            $params = array();
            while($field = $md->fetch_field()) {
                $params[] = &$result[$field->name];
            }
            call_user_func_array(array($this->stmt, 'bind_result'), $params);
            $this->stmt->fetch();
			
            return $result;
        }

        return null;
    } 
	
	function close() // Close the prepared statement variable (mysqli_stmt_close())
	{
		if(isset($this->stmt)) {
			$this->stmt->close();
			$this->stmt = null;
		}
	}
	
}
?>
