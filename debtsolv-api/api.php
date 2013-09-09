<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
// -- Include the Debtsolv API and Settings
// ----------------------------------------
include_once('includes/settings.inc.php');
include_once('includes/debtsolv-api.php');

if($enableAPI === false)
{
	print json_encode(array('Error Code' => 80,
	                        'Message' => 'Debtsolv API is currenlt disabled'
	                       )
                   );
                   
  die();
}

// -- If no Key or Function is passed then Error
// ---------------------------------------------
if(!$_GET['key'] || !$_GET['function'])
{
	print json_encode(array('Error Code' => 90,
						              'Message' => 'Invalid API Use'
						              )
	                 );
  
  die();
}

$api = new $_GET['function']($_GET['key'], $_GET['function'], $_GET['value'], $_GET['output']);

// -- Run the API Call and display the output
// ------------------------------------------
try
{
	echo $api->run();
}
catch(apiException $e)
{
	$e->display();
}
?>