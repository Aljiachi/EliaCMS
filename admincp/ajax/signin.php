<?php
	
	ob_start();
	
	session_start();
	
	include_once("../../includes/config.php");

	include_once("../../includes/class.db.php");

	include_once("../adinc/class.adinc.php");
	
	include_once("../../includes/functions.php");
	
	include_once("../adinc/class.logger.php");

	$logger = new eliaLogger;
	$logger->ajaxLoginRequest();
	
?>
