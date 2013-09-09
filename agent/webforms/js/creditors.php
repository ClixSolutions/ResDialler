<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors','On');

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/functions.inc.php');

// -- Get a list of all the creditors
// ----------------------------------
$getCreditors = $diallerDB->query_read("SELECT
	                                          name
	                                        FROM
	                                          gab_creditor_list
	                                       ");
	                                       
while($creditorRow = $diallerDB->fetch_row($getCreditors))
  $a[] = $creditorRow['name'];

//get the q parameter from URL
$q = $_GET["q"];

//lookup all hints from array if length of q>0
if (strlen($q) > 0)
{
	$hint = "";
	for($i = 0; $i < count($a); $i++)
  {
  	if (strtolower($q)==strtolower(substr($a[$i],0,strlen($q))))
    {
    	if ($hint == "")
      {
      	@$hint = "<div onclick=\"fillAjaxInput('CreditorsName', '" . addslashes($a[$i]) . "', 'ajaxCreditors');setOriginalCreditor('" . addslashes($a[$i]) . "')\" class=\"Ajax-Value\">" . addslashes($a[$i]) . "</div>";
      }
    	else
      {
      	@$hint .= "<div onclick=\"fillAjaxInput('CreditorsName', '" . addslashes($a[$i]) . "', 'ajaxCreditors');setOriginalCreditor('" . addslashes($a[$i]) . "')\" class=\"Ajax-Value\">" . addslashes($a[$i]) . "</div>";
      }
    }
  }
}

// Set output to "no suggestion" if no hint were found
// or to the correct values
if ($hint == "")
{
	$response = "No Suggestion";
}
else
{
	$response = $hint;
}

//output the response
echo $response;
?>