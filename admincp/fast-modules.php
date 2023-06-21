<?php
	
	session_start();
	
	include("../includes/config.php");

	include("../includes/class.db.php");

	include("adinc/functions.php");

	include("adinc/class.adinc.php");
	
	$adinc->_checkSession();
								
	include("adinc/class.modules.php");
	
	include("adinc/class.uploader.php");

	include("adinc/class.objectsManager.php");
			
	$controlMod = new ControlModules;
	
	$controlMod->run();
	
	define('ModuleID' , _safeInt($_REQUEST['module']));

	$Action 	= _safeString($_GET['action']);
	
	$controlMod->admin();

?>