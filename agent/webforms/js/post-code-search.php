<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include_once('../includes/functions.inc.php');

// -- Get a list of all the addresses
// ----------------------------------
$request = $diallerDB->query_first("SELECT
				                                post_code, address_1, address_2, town, county
				                              FROM
																			  gab_post_codes
				                              WHERE
																				post_code = '" . str_replace(" ", "", trim(mysql_real_escape_string($_POST['postCode']))) . "'
				                              LIMIT 1
                             ");


echo json_encode($request);
?>