<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */

// ------------------
// -- Debtsolv API --
// ------------------

/* *********** *
** Error Codes *
** *********** *
*
* Code   Message
* ****   *******
* 100    Invalid API Key
* 101    Output method not found
* 102    Function Call not found
*
*/

class Debtsolv_API
{
	private $error = array();
	
	protected $key;
	protected $db;
	protected $dbDialler;
	protected $function;
	protected $value = array();
	protected $result;
	protected $output;
	
	public function __construct($key, $function, $value, $output = 'none')
	{
		global $db, $dbDialler, $apiKeys;
		
		$this->db = $db;
		$this->dbDialler = $dbDialler;
		$this->key = $key;
		
		$this->function = $function;
		
    $this->output = $output;
    
    if(!is_array($value))
    	$this->value[] = $value;
    else
      $this->value = $value;
    
    // Protect against SQL Injection
    if(!get_magic_quotes_gpc())
    {
      // -- Go through and sanitize all _GET, _POST, _COOKIE
      // ---------------------------------------------------
      $this->value = $this->fixInputs($this->value);
      $_GET = $this->fixInputs($_GET);
      $_POST = $this->fixInputs($_POST);
      $_COOKIE = $this->fixInputs($_COOKIE);
	    $_SERVER = $this->fixInputs($_SERVER);
    }
		
		if($output != 'none')
		{
			try
			{
				$this->checkApiKey();
				
				try
				{
					$this->outputExists();
				}
				catch(apiException $e)
				{
					$e->display();
					die();
				}
			}
			catch(apiException $e)
			{
				$e->display();
				die();
			}
		}
	}
	
	public function __destruct()
	{
		// -- Clear all the big variables
		// ------------------------------
		unset($this->value);
		unset($this->function);
		unset($this->error);
	}
	
	// -- Check for a valid API Key
	// ----------------------------
	private function checkApiKey()
	{
		global $apiKeys;
		
		// -- Check Key
		// ------------
		if(!in_array($this->key, $apiKeys))
		{
		  throw new apiException("Invalid API Key");
    }
    else
    {
    	// -- Clear API Keys
    	// -----------------
    	unset($apiKeys);
      return true;
    }
	}
	
	private function outputExists()
  {
 	  if(!method_exists($this->function, $this->output))
    {
    	throw new apiException("Output " . $this->output . " isn't supported");
    }
    else
    {
    	return true;
    }
  }
  
  // -- Recusively make all the possible data inputs database safe
  // -------------------------------------------------------------
  private function fixInputs($Input)
  {
  	$Output = array();

    foreach($Input as $Field => $Value)
    {
      if (is_array($Value))
      { // If it is an array then recusively parse
        $Output[$Field] = $this->fixInputs($Value);
      }
      else
      { // Otherwise trim, make safe and carry on
        $Output[$Field] = htmlentities(str_replace("'", "''", trim($Value)), ENT_QUOTES, 'UTF-8');
      }
    }
    return $Output;
  }
	
	protected function run()
	{
		
	}
}
?>