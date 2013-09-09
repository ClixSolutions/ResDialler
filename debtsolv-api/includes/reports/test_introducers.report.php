<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
class Test_introducers extends Reports
{
  public function run()
  {
	  $results = array();
	
	  $sql = $this->db->query_read("SELECT
	                                  LI.ID,
	                                  LI.Name,
																	  SUM(
																	    CASE
																	      WHEN (CC.ContactResult > 0) THEN 1
																	      ELSE 0
																	    END) AS Called_Leads,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult <= 0) THEN 1
																	      ELSE 0
																	    END) AS No_Contact,
																	  SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 1500) THEN 1
																	      ELSE 0
																	    END) AS Completed_Lead,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 20) THEN 1
																	      ELSE 0
																	    END) AS No_Answer,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 500) THEN 1
																	      ELSE 0
																	    END) AS Left_Voice_Mail,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 700) THEN 1
																	      ELSE 0
																	    END) AS Call_Backs,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 2040) THEN 1
																	      ELSE 0
																	    END) AS No_Debt,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 2041) THEN 1
																	      ELSE 0
																	    END) AS Little_Debt,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 2042) THEN 1
																	      ELSE 0
																	    END) AS In_IVA,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 2043) THEN 1
																	      ELSE 0
																	    END) AS Bankrupt,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 2048) THEN 1
																	      ELSE 0
																	    END) AS DI_Higher_Then_Contractual,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 2020) THEN 1
																	      ELSE 0
																	    END) AS Not_Interested,
																	    SUM(
																	    CASE
																	      WHEN (CC.ContactResult = 2030) THEN 1
																	      ELSE 0
																	    END) AS Does_Not_Qualify
																	FROM
																	  " . LEADPOOL_DB . ".dbo.Campaign_Contacts AS CC
																	INNER JOIN
																	  " . LEADPOOL_DB . ".dbo.Client_LeadDetails AS CLD ON CC.ClientID = CLD.ClientID
																	INNER JOIN
																	  " . LEADPOOL_DB .".dbo.LeadBatch AS LB ON CLD.LeadBatchID = LB.ID
																	INNER JOIN
																	  " . LEADPOOL_DB . ".dbo.Type_Lead_Source AS TLS ON LB.LeadSourceID = TLS.ID
																	INNER JOIN
																	  " . DEBTSOLV_DB . ".dbo.Lead_Introducers AS LI ON TLS.IntroducerID = LI.ID
															    GROUP BY
															      LI.ID,
															      LI.Name                                  
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
  
  // -- Format Report for XML
  // ------------------------
  private function xml($results)
  {
  	$xml = array();
  	
  	$xml['xmlTitle'] = ucwords(str_replace("_", " ", $this->function));
  	$xml['xmlDescription'] = 'Introducers report showing call results.';
  	
  	foreach($results as $value)
  	{
  		$xml['element'][] = array('date' => date("r"),
			                          'title' => $value['Name'],
			                          'link' => HOST_ADDRESS . "/" . $this->key . "/full_introducer_report/" . $value['ID'] . "/html/",
				  		                  'description' => "<p>Called Leads: " . number_format($value['Called_Leads']) . "</p>" .
                                                 "<p>No Contact: " . number_format($value['No_Contact']) . "</p>" .
                                                 "<p>Completed Leads: " . number_format($value['Completed_Lead']) . "</p>" .
                                                 "<p>No Answer: " . number_format($value['No_Answer']) . "</p>" .
                                                 "<p>Not Interested: " . number_format($value['Not_Interested']) . "</p>"
							                 );
  	}
  	
  	return $xml;
  }
}
?>