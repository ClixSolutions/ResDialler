<?php
	ini_set('display_errors', 1);
	date_default_timezone_set('Europe/London');
	error_reporting(0);
	//error_reporting(E_ALL);

	include_once("/var/www/html/api/framework/classes/database.class.php");

	function __autoload($class_name) {
    	include "classes/".$class_name . '.class.php';
	}
	
	$className = strtolower($_GET['report']);
	
	include_once("pages/".$className.".php");
	$sp = new $className();
	echo $_GET['callback'] . "(" . $sp->getContent() .");";
	
?>