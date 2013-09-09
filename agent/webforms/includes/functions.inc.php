<?php
// ------------------------
// -- Webforms Functions --
// ------------------------

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors','On');

// -- Include Database Files
// -------------------------
include_once('mysql.class.inc.php');

// -- Debtsolv API Settings
// ------------------------
$apiKey = 'KJAH8qw';

// -- Settings
// -----------
$diallerDBConfig = array();

// -- Dialler Database
// -------------------
$diallerDBConfig['type']     = 'mysql';
$diallerDBConfig['hostname'] = '10.150.4.10';
$diallerDBConfig['database'] = 'asterisk';
$diallerDBConfig['username'] = 'cron';
$diallerDBConfig['password'] = '1234';
$diallerDBConfig['newlink']  = true;

$diallerDB = new Database_Mysql($diallerDBConfig);

// --------------------
// -- Main functions --
// --------------------

function sendCurlRequest($key, $function, $data)
{
	// -- Check that all the variable have been sent
	// ---------------------------------------------
	if(empty($key) || empty($function) || empty($data))
	  return false;
	  
  // -- Create CURL Request
  // ----------------------
  $ch = curl_init();
	#curl_setopt($ch, CURLOPT_URL, "http://api.gregsonandbrooke.co.uk/debtsolv-api/apipost.php");
  curl_setopt($ch, CURLOPT_URL, "http://debtsolv.res.clixconnect.net/apipost.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, true);

  $dataSend['value'] = json_encode(array('key' => $key,
		                                     'function' => $function,
																		     'data' => json_encode($data)
																		    ));

	curl_setopt($ch, CURLOPT_POSTFIELDS, $dataSend);
	$output = curl_exec($ch);
	curl_close($ch);
	
	$jsonOutput = json_decode($output, true);
	
	if(count($jsonOutput) > 0)
	{
	  return $jsonOutput;
  }
  else
	  return $output;
}

function getCreditors()
{
	// -- Get a list of creditors
	// --------------------------
}

function getCreditType()
{
	// -- Get credit type
	// ------------------
	global $diallerDB;
	$creditTypes = array();
	
	$getCreditTypes = $diallerDB->query_read("SELECT
	                                            id, description
                                            FROM
                                              gab_credit_type
                                            ORDER BY
                                              description ASC
                                           ");
                                  
  while($creditTypRow = $diallerDB->fetch_row($getCreditTypes))
    $creditTypes[] = $creditTypRow;
    
  return $creditTypes;
}

function getCreditorList()
{
	// -- Get list of creditors
	// ------------------------
	global $diallerDB;
	
	$getCreditorList = $diallerDB->query_read("SELECT
	                                             id, name
                                             FROM
                                               gab_creditor_list
                                             ORDER BY
                                               name ASC
	                                          "); 
}

function getCreditorInformation($lead_id)
{
	// -- Get creditor information
	// ---------------------------
	global $diallerDB;
	
	if(!(int)$lead_id)
	  return;
	
	$getLeadCreditors = $diallerDB->query_read("SELECT
	                                              GCI.id, GCI.client_id, GCI.category_id, GCI.description, GCT.description AS credit_type, GCI.monthly_amount,
																								GCI.amount_owed, GCI.client_responsible
                                              FROM
                                                gab_lead_creditors AS GCI
                                              INNER JOIN
                                                gab_credit_type AS GCT ON GCI.category_id = GCT.id
                                              WHERE
                                                GCI.lead_id = " . (int)$lead_id . "
	                                           ");
	                                           
  while($resultRow = $diallerDB->fetch_row($getLeadCreditors))
 	  $results[] = $resultRow;
 	  
  return $results;
}
?>
