<?php
// --------------------------
// -- Creditor Information --
// --------------------------
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 'On');

// -- Include Functions file
// -------------------------
include_once('includes/functions.inc.php');
include_once('includes/customer_info_form.inc.php');

// -- Log the transfer to the Senior Transfer Log
// ----------------------------------------------
$diallerDB->query_write("INSERT IGNORE INTO
                           gab_log_senior_transfer
                         (
                            lead_id
                           ,senior_username
                           ,transfered_date_time
                           ,completed_date_time
                           ,leadpool_id
                           ,error_message
                           ,status
                         )
                         VALUES
                         (
                            " . (int)$_GET['lead_id'] . "
                           ,'" . $_GET['agent'] . "'
                           ,NOW()
                           ,'0000-00-00 00:00:00'
                           ,0
                           ,''
                           ,1
                         )
                        ");

// -- Find Debtsolv ID for the lead in the Creditor Lead Table
// -----------------------------------------------------------
$debtsolvClientID = $diallerDB->get_val("SELECT
                                           client_id
                                         FROM
                                           gab_lead_creditors
                                         WHERE
                                           lead_id = " . (int)$_GET['lead_id'] . "
                                         LIMIT 1
                                        ");
                                        
if($debtsolvClientID > 0)
{
  // -- Get the Clients Name from the dialler
  // ----------------------------------------
  $clientName = $diallerDB->get_val("SELECT
                                       CONCAT(title, ' ', first_name, ' ', last_name) AS name
                                     FROM
                                       vicidial_list
                                     WHERE
                                       lead_id = " . (int)$_GET['lead_id'] . "
                                     LIMIT 1
                                    ");
}

if((int)isset($_GET['remove']))
{
	// -- Delete creditor from the lead
	// --------------------------------
	$diallerDB->query_write("DELETE FROM gab_lead_creditors
	                         WHERE id = " . (int)$_GET['remove'] . "
	                         LIMIT 1
	                        ");
}

if(isset($_POST['AddCreditor']))
{
	// --------------------
	// -- Form Submitted --
	// --------------------
	
	// -- Check for required fields
	// ----------------------------
	$requiredFields = array('Creditor_Name',
	                        'Type_of_Credit',
	                        'Signatory'
	                       );
	      
	$errors = array();
	$i = 1;               
  foreach($_POST as $field => $value)
  {
  	if(in_array($field, $requiredFields) && $value == "")
  	  $errors[$i++] = $field;
  }
  
  unset($i);
  
  // -- No Errors
  // ------------
  if(count($errors) <= 0)
  {
  	// -- Save the creditor to the database
  	// ------------------------------------
  	$diallerDB->query_write("INSERT INTO gab_lead_creditors
  	                         (id, lead_id, client_id, category_id, description, monthly_amount, amount_owed, client_responsible)
  	                         VALUES
  	                         (NULL,
														   " . (int)$_GET['lead_id'] . ",
															 " . (int)$_GET['client_id'] . ",
															 " . (int)$_POST['Type_of_Credit'] . ",
															'" . mysql_escape_string($_POST['Current_Creditors_Name']) . "',
															 " . (int)$_POST['Original_Payment'] . ",
															 " . (int)$_POST['Balance'] . ",
															 " . (int)$_POST['Signatory'] . "
															)
		                        ");
  }
}

// -- Get a list of Creditors for the lead
// ---------------------------------------
$creditorList = getCreditorInformation((int)$_GET['lead_id']);

if(isset($_POST['SaveWebForm']))
{
	// -- Save Customer and Creditor Information to Debtsolv
	// -- Creditor information must be entered
	// -----------------------------------------------------
	#if(count($creditorList) > 0)
	#{
		$data = $_POST['data'];
    
    $diallerDB->query_write("UPDATE
                                 gab_log_senior_transfer
                               SET
                                 status = 2
                               WHERE
                                 lead_id = " . (int)$_GET['lead_id'] . "
                               LIMIT 1
                              ");
    
    // -- Get the agents login ID
    // --------------------------
   $agentDetails = $diallerDB->query_first("SELECT
                                                 VXL.user AS agent
                                                ,VU.full_name AS agentFullName
                                              FROM
                                                vicidial_xfer_log AS VXL
                                              LEFT JOIN
                                                vicidial_users AS VU USING(user)
                                              WHERE
                                                VXL.lead_id = " . (int)$_GET['lead_id'] . "
                                              ORDER BY
                                                call_date DESC
                                              LIMIT 1
                                             ");
                                           
     $data['AgentID'] = $agentDetails['agent'];
     $data['AgentFullName'] = $agentDetails['agentFullName'];
                                           
    // -- Get the list name
    // --------------------
    $data['ListName'] = $diallerDB->get_val("SELECT
                                               list_name
                                             FROM
                                               vicidial_lists
                                             WHERE
                                               list_id = " . (int)$_GET['list_id'] . "
                                             LIMIT 1
                                            ");
		
		// -- Check to see if we can match the Debtsolv Client ID
		// ------------------------------------------------------
		if((int)$_GET['client_id'] > 0 && sendCurlRequest($apiKey, 'Check_lead_exists', array('ClientID' => (int)$_GET['client_id'])) > 0)
		{
			// -- Lead found in Debtsolv, so just update. If it fails then add it as a new lead
			// --------------------------------------------------------------------------------
			if(!sendCurlRequest($apiKey, 'update_client_details', $data))
			  $errors[] = "Failed to update Lead " . (int)$_GET['client_id'];
		}
		else
		{
			// -- Check for Duplicate
			// ----------------------
			#$isDuplicate = json_decode(sendCurlRequest($apiKey, 'check_duplicate', $data), true);
			
			// -- Duplicate handling
			// ---------------------
			if($isDuplicate['ClientID'] > 0)
			{
				// -- Duplicate found
				// ------------------
				if($isDuplicate['result'] == 'duplicate' && $isDuplicate['database'] == 'debtsolv')
				{
					// -- Debtsolv Duplicate found
					// ---------------------------
					$emailData['to'] = "sales@gregsonandbrooke.co.uk";
					$emailData['subject'] = 'Existing Client for Consolidation ID ' . $isDuplicate['ClientID'];
					$emailData['body'] = "Client ID " . $isDuplicate['ClientID'] . " was found in Debtsolv. Please load this client up and check thier status.";
					
					sendCurlRequest($apiKey, 'send_email', $emailData);
					
					$errors[] = 'Client found in Debtsolv Database. Please notify the consolidator';
					
					$data['ClientID'] = $isDuplicate['ClientID'];
					
					$saved = true;
				}
				else if($isDuplicate['result'] == 'duplicate' && $isDuplicate['database'] == 'leadpool')
				{
					// -- Leadpool Duplicate found, so just update and carry on
					// --------------------------------------------------------
					$data['ClientID'] = $isDuplicate['ClientID'];
					
					if(!sendCurlRequest($apiKey, 'update_client_details', $data))
			      $errors[] = 'Failed to update Existing lead in Debtsolv';
				}
			}
			else
			{
				// -- Lead isn't a duplicate, so add it as a new lead
				// --------------------------------------------------
				$data['ClientID'] = sendCurlRequest($apiKey, 'add_new_lead', $data);
			}
			
			if($data['ClientID'] <= 0)
			{
			  $errors[] = '101: Failed to add new lead into Debtsolv (' . $data['ClientID'] . ')';
	    }
      else
      {
        // -- Log it as completed
        // ----------------------
        $diallerDB->query_write("UPDATE
                                   gab_log_senior_transfer
                                 SET
                                    completed_date_time = NOW()
                                   ,leadpool_id = " . (int)$data['ClientID'] . "
                                   ,error_message = ''
                                   ,status = 3
                                 WHERE
                                   lead_id = " . (int)$_GET['lead_id'] . "
                                 LIMIT 1
                                ");
      }
		}
		
		// -- Add all the creditors to Debtsolv
		// ------------------------------------
		if(count($errors) <= 0 && count($creditorList) > 0)
		{
			foreach($creditorList as $creditor)
			{
				$creditor['client_id'] = $data['ClientID'];
				
				if(!sendCurlRequest($apiKey, 'add_client_creditor', $creditor))
				  $errors[] = 'Creditor ' . $creditor['description'] . ' was not saved';
			}
		}
				
		// -- No Errors so email sales
		// ---------------------------
		if(count($errors) <= 0)
		{
			sendCurlRequest($apiKey, 'Update_call_result', array('ClientID' => $data['ClientID'], 'ContactResult' => 900));
			
			$emailData = array();
			
			$emailData['to']      = "sales@gregsonandbrooke.co.uk";
			$emailData['subject'] = "Request for Consolidation Client ID: " . $data['ClientID'] . " " . $data['title'] . " " . $data['first_name'] . " " . $data['last_name'];
			$emailData['body']    = "Lead ID: " . $data['ClientID'] . "<br /><br />";
			$emailData['body']   .= "Please call <b>" . $data['title'] . " " . $data['first_name'] . " " . $data['last_name'] . "</b> which has been referred by <b>" . $_GET['agent'] . "</b><br /><br />";
			
      if(count($creditorList) > 0)
      {
        $emailData['body'] .= "<table>
			                        <tr>
															  <th>Creditor Name</th>
															  <th>Type of Credit</th>
															  <th>Balance</th>
															  <th>Original Payment</th>
															  <th>Signatory</th>
			                        </tr>
			                       ";
			                        
        foreach($creditorList as $creditor)
        {
        	$emailData['body'] .= "<tr>
        	                         <td>" . $creditor['description'] . "</td>
        	                         <td>" . $creditor['credit_type'] . "</td>
        	                         <td>" . $creditor['amount_owed'] . "</td>
        	                         <td>" . $creditor['monthly_amount'] . "</td>
        	                         <td>" . $creditor['client_responsible'] . "</td>
                                 </tr>
  				                      ";
        }
  			                        
        $emailData['body'] .= "</table>";
      }
      
      $emailData['body'] .= "<br /><br />";
      $emailData['body'] .= "<b>Lead Notes:</b> " . $data['comments'];
			
			$emailData['header'] = "From: " . $_GET['agent'] . " <dialler@gregsonandbrooke.co.uk>\nContent-Type: text/html; charset=iso-8859-1";
			
			sendCurlRequest($apiKey, 'send_email', $emailData);
			
			$ClientID = $data['ClientID'];
      
      // -- Save the new Debtsolv Client ID to the creditor table
      // --------------------------------------------------------
      $diallerDB->query_write("UPDATE
                                 gab_lead_creditors
                               SET
                                 client_id = " . (int)$ClientID . "
                               WHERE
                                 lead_id = " . (int)$_GET['lead_id'] . "
                             ");
			
			// -- Unset un-needed variables
			// ----------------------------
			unset($data, $_POST, $emailData);
			
			$saved = true;
		}
    else if(count($errors) > 0)
    {
      $errorData = array_merge($errors, $data);
      
      $diallerDB->query_write("UPDATE
                                 gab_log_senior_transfer
                               SET
                                  error_message = '" . serialize($errorData) . "'
                                 ,status = 4
                               WHERE
                                 lead_id = " . (int)$_GET['lead_id'] . "
                               LIMIT 1
                              ");
    }
    
    
	#}
  #else
  #{
  #  print "No Creditor Information";
  #}
  
  
}

header("Expires: 0"); header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); header("cache-control: no-store, no-cache, must-revalidate"); header("Pragma: no-cache");
?>
<html>
  <head>
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
		<meta http-equiv="expires" content="FRI, 13 APR 1999 01:00:00 GMT">
		<META name="ROBOTS" content="NOINDEX, NOFOLLOW, NOARCHIVE">
	  <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
	  <link rel="stylesheet" type="text/css" href="css/style.css" />
	  <link rel="stylesheet" type="text/css" href="css/creditor_information.css" />
	  <script type="text/javascript" src="js/jquery-1.7.min.js"></script>
	  <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
	  <script type="text/javascript" src="js/jquery-calls.js"></script>
	  <script type="text/javascript" src="js/scripts.js"></script>
	</head>
	<body>
	  <div id="TopNav">
		  Debtsolv Lead ID: <?=(int)$debtsolvClientID;?>
		  &nbsp;&nbsp;
		  Name: <?=$clientName;?>
		  <div style="width:450px;text-align:right;float:right;">
			  <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>?client_id=<?=(int)$_GET['client_id'];?>&list_id=<?=(int)$_GET['list_id'];?>&lead_id=<?=$_GET['lead_id'] . '&client_id=' . $_GET['client_id'] . '&agent=' . $_GET['agent'];?>" style="margin:0px" onsubmit="getCustomerInfoValues();changeProgressStatus('Please Wait')" style="margin:0px;">
			    <?=customer_info_form();?>
			    <input type="hidden" name="data[ClientID]" value="<?=(int)$_GET['client_id'];?>" />
			    <input type="hidden" name="data[ListID]" value="<?=(int)$_GET['list_id'];?>" />
			    <input type="hidden" name="data[LeadID]" value="<?=(int)$_GET['lead_id'];?>" />
			    <input type="hidden" name="data[LeadRef2]" value="1TICK-GBS" />
          <!--<input type="hidden" name="data[AgentFullName]" value="<?=$_GET['agent'];?>" />-->
			    <span id="Status" style="font-weight:bold;"></span>
				  <input type="submit" name="SaveWebForm" id="SaveWebForm" value="Create Debtsolv Lead!" style="background-color:#00CC00;color:#FFF;font-weight:bold;" />
				</form>
			</div>
			<div style="clear:both;"><!-- --></div>
		</div>
	  <?php
	  if(count($errors) > 0)
	  {
	  	?>
	  	<div id="TopErrorBox">
			  Error has occured;
			  <?php
			  foreach($errors as $error)
			  {
			  	?>
			  	<br /><li><?=$error;?></li>
			  	<?php
			  }
			  
			  print_r($data);
			  ?>
			</div>
	  	<?php
	  }
	  ?>
	  
	  
	  <?php
	  if($saved)
	  {
	  	?>
	  	<script type="text/javascript">
			  changeProgressStatus('Saved to Debtsolv! Lead ID: <?=(int)$ClientID;?>');
			</script>
	  	<?php
	  }
	  ?>
	</body>
</html>