<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Output
 {
 	 protected $result;
 	 protected $function;
 	 protected $output;
 	 
 	 public function __construct($result, $function, $output)
 	 {
 	 	 $this->result = $result;
 	 	 $this->function = $function;
 	 	 $this->output = $output;
 	 	 
 	 	 $this->outputExists();
 	 }
 	 
 	 private function outputExists()
 	 {
 	 	 if(!class_exists($this->output))
 	 	 {
 	 	 	 throw new apiException($this->output . ' output Does\'t exist');
 	 	 	 die();
 	 	 }
 	 }
 	 
 	 public function __destruct()
 	 {
 	 	 unset($this->result);
 	 }
 }
?>