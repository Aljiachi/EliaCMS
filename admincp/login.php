<?php

	session_start();
	
	include_once("../includes/config.php");

	include_once("../includes/class.db.php");

	include_once("../includes/functions.php");

	include_once("adinc/class.adinc.php");

	include_once("adinc/class.logger.php");
	
	$logger = new eliaLogger;
	
	$access = $db->getfastRows("select admin_log from core");
	
	$logger->getTop();
		
	if(!$logger->isBlocked() and ($_REQUEST['action'] !== "reopen" and $_REQUEST['in'] !== "accounts")){ 
	
		$logger->loginForm();
		
	}else if($_REQUEST['action'] == "reopen"){ 
	
		$logger->reopenForm();
		
	}else{
		
		$logger->blockedMessage();
		
	} 
	
	$logger->getButtom(); 
	
?>