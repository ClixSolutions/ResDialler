<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Update_client_details extends Post
 {
 	 public function run()
 	 {
 	   #$body = "Client ID: " . $this->value['ClientID'] . "| Lead ID: " . $this->value['LeadID'] . " | LeadRef2: " . $this->value['LeadRef2'];
 	   #@mail("d.stansfield@gregsonandbrooke.co.uk", "Debtsolv Leadpool Duplicate", $body, "From: Dialler <dialler@gregsonandbrooke.co.uk>\nContent-Type: text/html; charset=iso-8859-1");
     
 	   // -- Update Client Lead Details
     // -----------------------------
     $this->db->query_write("UPDATE Top (1)
                               dbo.Client_LeadDetails
                             SET
                               LeadRef = '" . $this->value['LeadID'] . "',
                               LeadRef2 = '" . $this->value['LeadRef2'] . "',
                               DateCreated = GetDate()
                             WHERE
                               ClientID = " . (int)$this->value['ClientID'] . "
                            ");
                            
 	   // -- Update Client Lead Details
     // -----------------------------
 	 	 $result = $this->db->query_write("UPDATE Top (1)
                                         dbo.Client_Details
  			                               SET
  			                                 Title = '" . $this->value['title'] . "',
  			                                 Forename = '" . $this->value['first_name'] . "',
  			                                 Surname = '" . $this->value['last_name'] . "',
  			                                 StreetAndNumber = '" . $this->value['address1'] . "',
  			                                 Area = '" . $this->value['address2'] . "',
  			                                 Town = '" . $this->value['city'] . "',
  			                                 Postcode = '" . $this->value['Postcode'] . "'
  			                               WHERE
  			                                 ClientID = " . (int)$this->value['ClientID'] . "
                                       ");                          
								                      
 	 	 return $this->outputPost(true);
 	 }
 }

?>