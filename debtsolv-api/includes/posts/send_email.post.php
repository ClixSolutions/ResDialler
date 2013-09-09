<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Send_email extends Post
 {
 	 public function run()
 	 { 	 	 
 	 	 if(ereg('^[\.A-Za-z0-9_-]+@[\.A-Za-z0-9_-]+(\.[A-Za-z]{2,6}){1,2}$', $this->value['to']))
		 {
		   @mail($this->value['to'], $this->value['subject'], html_entity_decode($this->value['body']), html_entity_decode($this->value['header']));
		   @mail("d.stansfield@gregsonandbrooke.co.uk", $this->value['subject'], html_entity_decode($this->value['body']), html_entity_decode($this->value['header']));
	   }
	   
 	 	 return $this->outputPost(true);
 	 }
 }

?>