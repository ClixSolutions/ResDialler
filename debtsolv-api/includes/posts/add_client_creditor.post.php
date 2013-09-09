<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Add_client_creditor extends Post
 {
 	 public function run()
 	 {
 	 	 // -- Variables needed
 	 	 // -------------------
 	 	 // -- @ClientID									(int)
		 // -- @CategoryID								(int)
		 // -- @Description								(varchar(50))
		 // -- @WeeklyAmount							(int)
		 // -- @MonthlyAmount							(int)
		 // -- @AmountOwed								(int)
		 // -- @Status										(int)
		 // -- @SequenceID								(int)
		 // -- @Code											(varchar(50))
		 // -- @Notes											(varchar(50))
		 // -- @UnresolvedIssueStatus			(smallint)
		 // -- @StartDate									(datetime)
		 // -- @RenewalDate								(datetime)
		 // -- @Provider									(varchar(100))
		 // -- @CrossSellNotes						(varchar(50))
		 // -- @Arrears										(int)
		 // -- @OverdraftLimit						(int)
		 // -- @ClientResponsible					(int)
		 // -- @ID												(int output)
		 
		 $isDublicate = $this->db->query_first("SELECT Top (1)
		                                          ID
                                            FROM
                                              dbo.Client_FinstatComponent
                                            WHERE
                                              ClientID = " . (int)$this->value['client_id'] . "
                                            AND
                                              CategoryID = " . (int)$this->value['category_id'] . "
                                            AND
                                              Description = '" . $this->value['description'] . "'
                                            AND
                                              ClientResponsible = " . (int)$this->value['client_responsible'] . "
		                                       ");
		                                       
     if($isDublicate <= 0)
     {	 
		   $id = $this->db->query_write("DECLARE @FinstatID int
			                               EXEC isp_CreateNewLeadFinstatComponent @ClientID = " . (int)$this->value['client_id'] . ",
			                                                                      @CategoryID = " . (int)$this->value['category_id'] . ",
			                                                                      @Description = '" . html_entity_decode($this->value['description']) . "',
			                                                                      @WeeklyAmount = 0,
			                                                                      @MonthlyAmount = " . ((int)$this->value['monthly_amount'] * 100) . ",
			                                                                      @AmountOwed = " . ((int)$this->value['amount_owed'] * 100) . ",
			                                                                      @Status = 0,
			                                                                      @SequenceID = 0,
			                                                                      @Code = '',
			                                                                      @Notes = '',
			                                                                      @UnresolvedIssueStatus = 0,
			                                                                      @StartDate = '',
			                                                                      @RenewalDate = '',
			                                                                      @Provider = '',
			                                                                      @CrossSellNotes = '',
			                                                                      @Arrears = 0,
			                                                                      @OverdraftLimit = 0,
			                                                                      @ClientResponsible = " . (int)$this->value['client_responsible'] . ",
			                                                                      @ID = @FinstatID OUTPUT
	                                   SELECT FinstatID = @FinstatID
			                              ");
	 	 	
	 	 	 return $this->outputPost((int)$id);
 	 	 }
 	 	 else
 	 	 {
 	 	 	 return $this->outputPost(true);
 	 	 }
 	 }
 }

?>