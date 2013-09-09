777<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Update_call_result extends Post
 {
 	 public function run()
 	 {
 	 	 
 	 	
 	 	 $this->db->query_write("UPDATE Top (1)
 	 	                           dbo.Campaign_Contacts
                             SET
                               LastContactAttempt = getDate(),
                               ContactResult = " . (int)$this->value['ContactResult'] . "
                             WHERE
                               ClientID = " . (int)$this->value['ClientID'] . "
			                      ");
 	 	
 	 	 return $this->outputPost(true);
 	 }
 }

?>