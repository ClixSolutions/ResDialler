<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
class packs_out_in extends Reports
{
  public function run()
  {
	  $results = array();
	
	  $sql = $this->db->query_read("SELECT Distinct 
																		cc.ClientID AS \"Leadpool_ID\",
																		CL.Client_ID,
																		convert(varchar, CC.LastContactAttempt, 103) AS \"DD/MM/YY\",
																		FLOOR(CAST(CC.LastContactAttempt AS FLOAT )) AS pussy,
																		cd.Title,
																		cd.Forename,
																		cd.Surname, 
																		cd.StreetAndNumber,
																		cd.Area, 
																		cd.Town,
																		cd.County,
																		cd.Postcode,
																		LI.Name AS Introducer,
																		TLS.Description,
																		CASE
																		  WHEN   
																		  (
																		    SELECT Top (1)
																			    Title
																				FROM
																				  " . DEBTSOLV_DB . ".dbo.Client_Correspondence
																				WHERE
																				  ClientID = CL.Client_ID
																				AND
																				  Title = 'INFO PACK RECEIVED'
																	     ) Is Not null THEN 'Yes' 
																		   ELSE 'No'
																		   END AS 'Pack In',
															      (
																	    SELECT Top (1)
																			  " . DEBTSOLV_DB . ".dbo.Type_Payment_Status.Description 
																	    FROM
																			  " . DEBTSOLV_DB . ".dbo.Payment_Receipt 
																      LEFT JOIN
																		    " . DEBTSOLV_DB . ".dbo.Type_Payment_Status ON Debtsolv.dbo.Payment_Receipt.Status = Debtsolv.dbo.Type_Payment_Status.ID
																	    WHERE
																			  ClientID = CL.Client_ID 
																	    ORDER BY
																			  [DATE] desc
																		 ) AS Payment,
																		    CP.FeePercentage,
																		    CS.TriggerAmount/100 AS DI													        
																		FROM
																		  " . LEADPOOL_DB . ".dbo.Campaign_Contacts AS CC
																		INNER JOIN
																		  " . LEADPOOL_DB . ".dbo.Client_LeadDetails AS CLD ON CC.ClientID = CLD.ClientID
																		INNER JOIN
																		  " . LEADPOOL_DB . ".dbo.LeadBatch AS LB ON CLD.LeadBatchID = LB.ID
																		INNER JOIN
																		  " . DEBTSOLV_DB . ".dbo.Type_Lead_Source AS TLS ON LB.LeadSourceID = TLS.ID
																		INNER JOIN
																		  " . DEBTSOLV_DB . ".dbo.Lead_Introducers AS LI ON TLS.IntroducerID = LI.ID
																		INNER JOIN 
																		  " . LEADPOOL_DB . ".dbo.Client_Details AS CD ON CC.ClientID = CD.ClientID
																		INNER JOIN 
																		  " . DEBTSOLV_DB . ".dbo.Client_LeadData AS CL ON CC.ClientID = CL.LeadPoolReference
																		INNER JOIN
																		  " . DEBTSOLV_DB . ".dbo.Client_Correspondence AS DCC ON CL.Client_ID = DCC.ClientID
																		INNER JOIN 
																		  " . DEBTSOLV_DB . ".dbo.Client_PaymentData AS CP ON DCC.ClientID = CP.ClientID
																		INNER JOIN
																		 " . DEBTSOLV_DB . ".dbo.Client_Status AS CS ON CL.Client_ID = CS.ClientID																		 
																		WHERE
																		  CC.ContactResult = 1500
																		AND
																		  cd.Surname NOT IN ('Test', 'Tester')
																		GROUP BY 
																		  cc.ClientID,
																			CL.Client_ID,
																			CC.LastContactAttempt,
																			cd.Title,
																			cd.Forename,
																			cd.Surname, 
																			cd.StreetAndNumber,
																			cd.Area, 
																			cd.Town,
																			cd.County,
																			cd.Postcode,
																			LI.Name,
																			TLS.Description,
																			DCC.Title,
																			DCC.ClientID,
																			CP.FeePercentage,
																			CS.TriggerAmount                               
					                       ");
	                      
    while($resultRow = $this->db->fetch_row($sql))
      $results[] = $resultRow;
      
    $outputMethod = $this->output;
    return $this->outputReport($this->$outputMethod($results));
  }
  
  // -- Format Report for json
  private function json($result)
  {
  	return $result;
  }
  
  // -- Format Report for XML
  // ------------------------
  private function xml($results)
  {
  	$xml = array();
  	
  	$xml['xmlTitle'] = ucwords(str_replace("_", " ", $this->function));
  	$xml['xmlDescription'] = 'Total Packs Out and Packs In';
  	
  	return $xml;
  }
}
?>