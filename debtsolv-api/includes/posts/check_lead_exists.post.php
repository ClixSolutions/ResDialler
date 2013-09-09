<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Check_lead_exists extends Post
 {
 	 public function run()
 	 { 	 	 
 	 	 $id = $this->db->get_val("SELECT Top (1)
 	 	                             ClientID
                               FROM
                                 dbo.Client_Details
                               WHERE
                                 ClientID = " . (int)$this->value['ClientID'] . "
			                        ");
	   
	   #print "ID: " . (int)$id;
	   #die(' STOP: 300');
 	 	 return $this->outputPost((int)$id);
 	 }
 }

?>