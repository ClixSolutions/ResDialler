<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Check_duplicate_test extends Post
 {
 	 public function run()
 	 {
 	 	 // -- Check for duplicate in Debtsolv Database first
 	 	 // -------------------------------------------------
 	 	 $this->db->selectdb(DEBTSOLV_DB);
 	 	 
 	 	 $isDuplicate = $this->db->get_val("DECLARE @IDoutput int
			                                  EXEC dssp_CheckDuplicateClient_12_4 @ClientID = " . (int)$this->value['ClientID'] . ",
																				                                        @Surname = '" . $this->value['Surname'] . "',
																																						    @StreetAndNumber = '" . $this->value['StreetAndNumber'] . "',
																																						    @Postcode = '" . $this->value['PostCode'] . "',
																																						    @Telephone = '". $this->value['Telephone'] . "'
																																						    --@ID = @IDoutput OUTPUT
																																						
																				--SELECT IDoutput = @IDoutput
                                        --SELECT LastRowCount                           
			                                 ");
			                                 
     if($isDuplicate > 0)
     {
     	 $this->db->selectdb(LEADPOOL_DB);
     	
     	 // -- Duplicate found is Debtsolv Database
     	 // ---------------------------------------
     	 $result = array('result'   => 'duplicate',
				               'database' => 'debtsolv',
											 'ClientID' => $isDuplicate);
     }
     else
     {
  	   $this->db->selectdb(LEADPOOL_DB);
     	
       $isDuplicate = $this->db->get_val("--DECLARE @IDoutput int
			                                    EXEC dssp_CheckDuplicateClient_12_4 @ClientID = " . (int)$this->value['ClientID'] . ",
																				                                          @Surname = '" . $this->value['Surname'] . "',
																																						      @StreetAndNumber = '" . $this->value['StreetAndNumber'] . "',
																																						      @Postcode = '" . $this->value['PostCode'] . "',
																																						      @Telephone = '". $this->value['Telephone'] . "'
																																						      --@ID = @IDoutput OUTPUT
																																						
																				  --SELECT IDoutput = @IDoutput
					                               ");
																																																								  
		    if($isDuplicate > 0)
		    {
		    	$result = array('result'   => 'duplicate',
					                'database' => 'leadpool',
													'ClientID' => $isDuplicate);
		    }
     }
     
     if(!isset($result))
       $result = false;
     
 	 	 return $this->outputPost($result);
 	 }
 }

?>