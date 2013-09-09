<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Reports extends Debtsolv_API
 { 	 
 	 protected function outputReport($result)
 	 {
 	 	 $outputMethod = new $this->output($result, $this->function, $this->output);
 	 	 return $outputMethod->sendOutput();
 	 }
 }
?>