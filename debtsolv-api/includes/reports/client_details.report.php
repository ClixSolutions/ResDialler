<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
class Client_details extends Reports
{
  public function run()
  {
	  $results = array();
	
	  $results = $this->db->query_first("SELECT Top (1)
	                                       CD.ClientID, Title, Forename, Surname, DateOfBirth, Email, StreetAndNumber, Area, Town, County, County
	                                       PostCode, CC.ContactResult
                                       FROM
                                         dbo.Client_Details AS CD
                                       LEFT JOIN
                                         dbo.Campaign_Contacts As CC ON CD.ClientID = CC.ClientID
                                       WHERE
                                         CD.ClientID = " . (int)$this->value[0] . "
					                            ");
    
    $outputMethod = $this->output;
    return $this->outputReport($this->$outputMethod($results));
  }
  
  private function json($results)
  {
  	return $results;
  }
}
?>