<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Json extends Output
 {
 	 public function sendOutput()
 	 {
 	 	 return json_encode($this->result);
 	 }
 }
?>