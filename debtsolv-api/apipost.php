<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */

// -- Include the Debtsolv API and Settings
// ----------------------------------------
include_once('includes/settings.inc.php');
include_once('includes/debtsolv-api.php');

if(!$_POST['value'])
  die('No Posted Data');
  
$posted = json_decode($_POST['value'], true);
$data = json_decode($posted['data'], true);

if(!$posted['key'] || !$posted['function'] || !$data)
  die('Invalid API Post call');
  
if($enableAPI === false)
{
	echo "Error Code 80: Debtsolv API is currently disabled";
	die();
}

$api = new $posted['function']($posted['key'], $posted['function'], $data);

echo $api->run(); 

?>