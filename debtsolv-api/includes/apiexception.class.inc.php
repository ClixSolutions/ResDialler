<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
class apiException extends Exception
{
	public function display()
	{
		ob_clean();
		
		echo $this->getMessage();
	} 
}
?>