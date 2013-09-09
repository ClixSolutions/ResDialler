<?php
/* ********************* */
/* Debtsolv API Settings */
/* ********************* */

// -- Includes
// -----------
include_once('mssql.class.inc.php');
include_once('mysql.class.inc.php');

$enableAPI = true;
$enableWAN = true;
$testMode  = false;
$debugMode = true;

define(REPORTS_FOLDER_PATH, 'includes/reports/');
define(POST_FOLDER_PATH, 'includes/posts/');
define(HOST_ADDRESS, 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']);

define(SALES_EMAIL_ADDRESS, 'sales@gregsonandbrooke.co.uk');

// -- Valid API Keys
// -----------------      
$apiKeys = array("KJAH8qw");

// -- Set Database Names
// ---------------------
if($testMode === true)
{
	// -- Test Mode
	// ------------
	define(LEADPOOL_DB, 'Leadpool_Test');
	define(DEBTSOLV_DB, 'Debtsolv_Test');
}
else
{
	// -- Live Mode
	// ------------
	define(LEADPOOL_DB, 'BS_Leadpool_DM');
	define(DEBTSOLV_DB, 'BS_Debtsolv_DM');
}

// -- Database Connection (SQL Server 2008 R2)
// -------------------------------------------
# 87.194.111.45 External IP
$dbConfig = array('hostname' => '192.168.1.100,1334',
                  'connectionInfo' => array('UID' => 'superuser',
														                'PWD'  => 'Rfd32xs12B',
														                'Database' => LEADPOOL_DB,
														                'ReturnDatesAsStrings' => true)
                 );

$db = new Database_Mssql($dbConfig);

// -- Dialler Connection (MySQL)
// -----------------------------
/*
$dbConfig2 = array('hostname' => '192.168.1.234',
                   'database' => 'asterisk',
                   'username' => 'root',
                   'password' => 'Air21circles'
                  );
*/
#$dbDialler = new Database_Mysql($dbConfig2);

// -- Find the class to load up
// ----------------------------
function __autoload($class)
{
	$class = strtolower($class);
	
	if(file_exists(REPORTS_FOLDER_PATH . $class . '.report.php'))
	{
		includeClass($class, REPORTS_FOLDER_PATH . $class . '.report.php');
	}
	else if(file_exists(POST_FOLDER_PATH . $class . '.post.php'))
	{
		includeClass($class, POST_FOLDER_PATH . $class . '.post.php');
	}
	else if(file_exists('includes/' . $class . '.class.inc.php'))
	{
		includeClass($class, 'includes/' . $class . '.class.inc.php');
	}
	else if(file_exists('includes/outputs/' . $class . '.output.inc.php'))
	{
		includeClass($class, 'includes/outputs/' . $class . '.output.inc.php');
	}
	else
	{
		echo json_encode(array('Error Code' => 92,
						               'Message' => 'Class file could not be found (Class = ' . $class . ')'
                          )
	                   );
    die();
	}
}

function includeClass($class, $file)
{
	require_once($file);
	if(!class_exists($class))
	{
		echo json_encode(array('Error Code' => 95,
						               'Message' => 'Class ' . $class . ' not found'
                          )
	                   );
    die();
	}
	
	return;
}
?>