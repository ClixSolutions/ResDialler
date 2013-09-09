<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Get_client_debt extends Post
 {
 	 public function run()
 	 { 	 	 
 	 	 // -- Get the client's Debt History
     // --------------------------------
     $client = $this->db->query_first("SELECT Top (1)
                                          ID
                                         ,Title
                                         ,Forename
                                         ,Surname
                                         ,StreetAndNumber
                                         ,Town
                                         ,County
                                         ,Postcode
                                         ,Tel_Home
                                         DateOfBirth
                                       FROM
                                         " . DEBTSOLV_DB . ".dbo.Client_Contact
                                       WHERE
                                         ID = " . $this->value['ClientID'] . "
                                      ");
	   
 	 	 return $this->outputPost(true);
 	 }
 }

?>