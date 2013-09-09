<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
class Get_new_leads extends Reports
{
  public function run()
  {
	  $results = array();
	
	  $sql = $this->db->query_read("SELECT
					                       ");
	                      
    while($resultRow = $this->db->fetch_row($sql))
      $results[] = $resultRow;
    
    $outputMethod = $this->output;
    return $this->outputReport($this->$outputMethod($results));
  }
  
  private function json($results)
  {
  	return $results;
  }
}
?>