<?php
	
	session_start();
	
	include("../includes/config.php");

	include("../includes/class.db.php");

	include("adinc/class.adinc.php");
	
	include("adinc/functions.php");
		
	include("adinc/class.coreapi.php");
	
	include("adinc/class.modules.php");

	include("adinc/class.uploader.php");

	include("adinc/class.objectsManager.php");

	$adinc->getStyle();
	
	$api = new eliaCoreApi;
			
	$controlMod = new ControlModules;
	
	$controlMod->run();
	
	define('ModuleID' , intval(abs($_REQUEST['module'])));
	$Action 	= strip_tags($_GET['action']);
	
	$adinc->getRight('mods');

	$adinc->getNavbar(array(
		
		$adinc->_lang("NAVBAR_MODULES_MANGE") => 'modules.php?action=view' ,
		$adinc->_lang("NAVBAR_MODULES_ADD") => 'modules.php?action=new' , 
		$adinc->_lang("NAVBAR_MODULES_UNINSTALLED") => 'modules.php?action=install'));
	
	switch($Action){
	
		default 	 		:			$controlMod->Management();		break;
		
		case "setting"		:			$controlMod->setting();			break;
				
		case "uninstall" 	: 			$controlMod->unInstall();		break;
			
		case "active" 		: 			$controlMod->active();			break;
	
		case "unactive" 	: 			$controlMod->unActive();		break;
			
		case "admin" 		: 			$controlMod->admin();			break;
			
		case "new" 			:  			$controlMod->add();				break;
				  
		case "install" 		:  			$controlMod->viewUnInstalled();	break;
		
		case "setup" 		: 			$controlMod->install();			break;
		
		case "edit" 		: 			$controlMod->edit();			break;
	
	}
	
	$adinc->closePage();

?>