<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Add_new_lead extends Post
 {
 	 public function run()
 	 {


 	 	 // -- 1) Check for duplicates
 	 	 // --------------------------
 	 	 #$clientExits = $this->isDuplicate($this->value['ClientID'], $this->value['last_name'], $this->value['address1'], $this->value['postal_code'], $this->value['phone_number']);
	   
 	 	 #if(is_array($clientExits))
 	 	 #{
 	 	 	 // -- Duplicate found
 	 	 	 // ------------------
 	 	 #	 return $this->outputPost($clientExits);
 	 	 #}
 	 	 #else
		 #{
 	 	 	 // -- No Duplicate found, so carry on with adding the new user
 	 	 	 // -----------------------------------------------------------
 	 	 	 
 	 	 	 // -- 2) Stored Procedure Input - Create New Client
 	 	 	 // ------------------------------------------------
 	 	 	 // -- @Title
			 // -- @Initials
			 // -- @Forename
			 // -- @MiddleNames
			 // -- @Surname
			 // -- @DateOfBirth
			 // -- @Email
			 // -- @MaritalStatus
			 // -- @Gender
			 // -- @StreetAndNumber
			 // -- @Area
			 // -- @District
			 // -- @Town
			 // -- @County
			 // -- @Country
			 // -- @Postcode
			 
			 $leadID = $this->db->get_val("DECLARE @NewLeadID int
			                               EXEC isp_CreateNewClient_103 @Title = '" . $this->value['title'] . "',
			                                                            @Initials = '',
			                                                            @Forename = '" . $this->value['first_name'] . "',
			                                                            @MiddleNames = '',
			                                                            @Surname = '" . $this->value['last_name'] . "',
			                                                            @DateOfBirth = NULL,
			                                                            @Email = '" . $this->value['email'] . "',
			                                                            @MaritalStatus = '',
			                                                            @Gender = '',
			                                                            @StreetAndNumber = '" . $this->value['address1'] . "',
			                                                            @Area = '" . $this->value['address2'] . "',
			                                                            @District = '',
			                                                            @Town = '" . $this->value['city'] . "',
			                                                            @County = '',
			                                                            @Country = '',
			                                                            @Postcode = '" . $this->value['postal_code'] . "',
			                                                            @ID = @NewLeadID OUTPUT
			                                                              
                                     SELECT NewLeadID = @NewLeadID");
		                                  
       if($leadID <= 0)
       {
       	 // -- Error creating new lead
       	 // --------------------------
       	 return $this->outputPost('Failed to Create Lead');
       }
       else
       {
       	  // -- 3) Stored Procedure Input - Create New Campaign Contact GAB
        	// --------------------------------------------------------------
        	// -- @ClientID
					// -- @CampaignID
					// -- @Appointment
					// -- @LastContactAttempt
					// -- @ContactResult
					// -- @Note
					
					$campaignContactID = $this->db->get_val("DECLARE @NewCampaignContactID int
					                                         DECLARE @currDate datetime = GETDATE()
                                                   EXEC isp_CreateNewCampaignContact_GAB @ClientID = " . (int)$leadID . ",
							                                                                           @CampaignID = 0,
																																												 @Appointment = NULL,
																																												 @LastContactAttempt = @currDate,
																																												 @ContactResult = 0,
																																												 @Note = '" . $this->value['comments'] . "',
																												 																 @ID = @NewCampaignContactID OUTPUT
																												 																	 
                                                   SELECT NewCampaignContactID = @NewCampaignContactID");
								                                    
         if($campaignContactID <= 0)
         {
         	 // -- Error creating CampaignID
         	 // ----------------------------
         	 return $this->outputPost('Failed to Create Campaign ID for Lead ID: ' . (int)$leadID);
         }
         else
         {
  	       // -- 4) Stored Procedure Input - Insert Client Lead Details
           // ---------------------------------------------------------
         	 // -- @ClientID
					 // -- @LeadBatchID
					 // -- @LeadRef
					 // -- @LeadRef2
					 // -- @CaseNotes
					 // -- @$LeadNotes
					 
					 $leadBatchID = $this->getLeadBatchID();
     	     /*
         	 $this->db->query_write("EXEC isp_InsertClient_LeadDetails @ClientID = " . (int)$leadID . ",
						                                                         @LeadBatchID = " . (int)$leadBatchID . ",
										                                                 @LeadRef = '" . (int)$this->value['LeadID'] . "',
																																		 @LeadRef2 = '" . $this->value['LeadRef2'] . "',
																																		 @CaseNotes = '',
																																		 @LeadNotes = ''");
           */
                                                                     
           $this->db->query_write("INSERT INTO Client_LeadDetails
                                   (ClientID,
                                	  LeadBatchID,
                               	    LeadRef,
                                	  LeadRef2,
                                	  CaseNotes,
                                	  LeadNotes)
          	                       VALUES
         		                       (" . (int)$leadID . ",
                                	  " . (int)$leadBatchID . ",
                                	 '" . (int)$this->value['LeadID'] . "',
                                	 '" . $this->value['LeadRef2'] . "',
                                	 '',
                                	 '')");
																																			 
					 // -- 5) Stored Procedure Input - Insert Campaign Contact Access
     	     // -------------------------------------------------------------
     	     // -- @CampaignContactID
					 // -- @UserID
					 // -- @AccessDate
     	     
           $this->db->query_write("DECLARE @currDate datetime = GETDATE()
					                         EXEC isp_InsertCampaignContactAccess @CampaignContactID = " . $campaignContactID . ",
						                                                            @UserID = 0,
																																				@AccessDate = @currDate,
																																				@ID = 0");
																																					
				   // -- 6) Stored Procedure Input - Insert Telephone Number
           // ------------------------------------------------------
					 // -- @ClientID
					 // -- @TelephoneNumber
					 // -- @TypeID
					 
           $this->db->query_write("EXEC isp_InsertTelephoneNumber @ClientID = " . (int)$leadID . ",
							                                                    @TelephoneNumber = " . $this->value['phone_number'] . ",
																																	@TypeID = 1");
                                                                  
           if($this->value['alt_phone'] != '')
           {
             $this->db->query_write("EXEC isp_InsertTelephoneNumber @ClientID = " . (int)$leadID . ",
							                                                      @TelephoneNumber = " . $this->value['alt_phone'] . ",
																																	  @TypeID = 16");
           }
         }
       }
 	 	 #}
     
     // -- Add Referral Details to the Dialler Database
     // -----------------------------------------------
     $this->db->query_write("INSERT INTO Dialler.dbo.referrals
                             ( list_id
                              ,lead_id
                              ,leadpool_id
                              ,list_name
                              ,short_code
                              ,user_login
                              ,full_name
                              ,referral_date
                              ,product)
                             VALUES
                              ( '" . $this->value['ListID'] . "'
                               ," . (int)$this->value['LeadID'] . "
                               ," . (int)$leadID . "
                               ,'" . $this->value['ListName'] . "'
                               ,'" . $this->value['LeadRef2'] . "'
                               ,'" . $this->value['AgentID'] . "'
                               ,'" . $this->value['AgentFullName'] . "'
                               ,GETDATE()
                               ,'" . ($this->value['Product'] ? $this->value['Product'] : 'DR') . "')
                            ");
	   
 	 	 return $this->outputPost((int)$leadID);
 	 }
 	 
 	 /*
 	 private function isDuplicate($ClientID = 0, $Surname, $StreetAndNumber = false, $PostCode = false, $Telephone = false)
 	 {
 	 	 // -- Check for duplicate in Debtsolv Database first
 	 	 // -------------------------------------------------
 	 	 $this->db->selectdb(DEBTSOLV_DB);
 	 	 
 	 	 $isDuplicate = $this->db->get_val("DECLARE @IDoutput int
			                                  EXEC dssp_CheckDuplicateClient_12_4_GAB @ClientID = " . (int)$ClientID . ",
																				                                        @Surname = '" . $Surname . "',
																																						    @StreetAndNumber = '" . $StreetAndNumber . "',
																																						    @Postcode = '" . $PostCode . "',
																																						    @Telephone = '". $Telephone . "',
																																						    @ID = @IDoutput OUTPUT
																																						
																				SELECT IDoutput = @IDoutput                             
			                                 ");
			                                 
     if($isDuplicate > 0)
     {
     	 $this->db->selectdb(LEADPOOL_DB);
     	
     	 // -- Duplicate found is Debtsolv Database
     	 // ---------------------------------------
     	 $result = array('duplicate', 'debtsolv', $isDuplicate); 
     	 return $result;
     }
     else
     {
  	   $this->db->selectdb(LEADPOOL_DB);
     	
       $isDuplicate = $this->db->get_val("DECLARE @IDoutput int
			                                    EXEC dssp_CheckDuplicateClient_12_4_GAB @ClientID = " . (int)$ClientID . ",
																				                                          @Surname = '" . $Surname . "',
																																						      @StreetAndNumber = '" . $StreetAndNumber . "',
																																						      @Postcode = '" . $PostCode . "',
																																						      @Telephone = '". $Telephone . "',
																																						      @ID = @IDoutput OUTPUT
																																						
																				  SELECT IDoutput = @IDoutput
					                               ");
																																																								  
		    if($isDuplicate > 0)
		    {
		    	$result = array('duplicate', 'leadpool', $isDuplicate);
	        return $result;
        }
	      else
	        return false;
     }
 	 }
 	 */
 	 
 	 private function getLeadSourceID()
 	 {
 	 	 // -- Get the lead source for the new lead
 	 	 // ---------------------------------------
 	 	 if(!$this->value['ListID'])
 	 	   die('No Dialler List ID:' . $this->value['ListID']);
 	 	   
 	   $leadSourceID = $this->db->get_val("SELECT Top (1)
 	                                         ID
                                         FROM
                                           " . DEBTSOLV_DB . ".dbo.Type_Lead_Source
                                         WHERE
                                           Reference = '" . $this->value['ListID'] . "'
			                                  ");
			                                  
     if($leadSourceID > 0)
       return $leadSourceID;
     else
       die('Lead Source ID not found. Dialler List ID: ' . $this->value['ListID']);
 	 }
 	 
 	 private function getLeadBatchID()
 	 {
 	 	  $leadSourceID = (int)$this->getLeadSourceID();
 	 	  
	 	  // -- Check for an existing Batch ID
			// ---------------------------------
			$leadBatchID = $this->db->get_val("SELECT Top (1)
			                                     ID
                                         FROM
			                                     dbo.LeadBatch
                                         WHERE
			                                     LeadSourceID = " . (int)$leadSourceID . "
			                                    ");
			                                    
			if($leadBatchID <= 0)
			{
				// -- No Batch ID found, so create a new Lead Batch
				// ------------------------------------------------
				
				// -- Get the Lead Source Description
				// ----------------------------------
				$discription = $this->db->get_val("SELECT Top (1)
				                                     Description
		                                       FROM
			                                       " . DEBTSOLV_DB . ".dbo.Type_Lead_Source
		                                       WHERE
			                                       ID = " . (int)$leadSourceID . "
				                                    ");
				
				// -- Stored Procedure Input - Create New Lead Batch
				// -------------------------------------------------
				// -- @Description
				// -- @Filename
				// -- @ImportDate
				// -- @LeadSourceID
				// -- @ImportMethodID
				// -- @ImportNotes
				// -- @ActionTakenID
				
				$this->db->query_write("DECLARE @currDate datetime = GETDATE()
				                        EXEC isp_CreateNewLeadBatch @Description = '" . $discription . "',
				                                                    @Filename = '',
																														@ImportDate = @currDate,
																														@LeadSourceID = " . (int)$leadSourceID . ",
																														@ImportMethodID = '',
																														@ImportNotes = '',
																														@ActionTakenID = 0
				                         ");
				                         
			  $leadBatchID = $this->db->get_val("SELECT Top (1)
				                                     ID
                                           FROM
				                                     dbo.LeadBatch
                                           WHERE
				                                     LeadSourceID = " . (int)$leadSourceID . "
				                                    ");
			                                    
			  if($leadBatchID <= 0)
			    die('Can not create Lead Batch ID with Lead Source ID: ' . (int)$leadSourceID);
			  else
			    return (int)$leadBatchID;
			}
			else
			{
				return (int)$leadBatchID;
			}
 	 }
 }

?>