<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Post extends Debtsolv_API
 { 	
 	 protected function outputPost($result)
	 {
	 	 if(is_array($result))
	 	 	 $result = json_encode($result);
	 	   
		 return $result;
	 }
 }
?>