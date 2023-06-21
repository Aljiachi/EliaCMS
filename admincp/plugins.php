<?php
	
	session_start();
	
	include("../includes/config.php");

	include("adinc/class.adinc.php");
	
	include("../includes/class.db.php");

	include("adinc/functions.php");
		
	include("adinc/class.plugins.php");
	
	include("adinc/class.uploader.php");

	include("adinc/class.objectsManager.php");

	$adinc->getStyle();
			
	$ControlPlugins = new ControlPlugins;
	
	$ControlPlugins->run();
	
	$Action 	= strip_tags($_GET['action']);
	
	$adinc->getRight('plugins');

	$adinc->getNavbar(array(
		$adinc->_lang("NAVBAR_PLUGINS_MANGE") => 'plugins.php?action=view' , 
		$adinc->_lang("NAVBAR_PLUGINS_UNINSTALLED") => 'plugins.php?action=install'));
	
	switch($Action){
	
		case "view" :
		default 	 :
		
			$form =  $ControlPlugins->Management();
			
			$adinc->getMenu($adinc->_lang("CONTROL_PLUGINS_ADD") , $form);
			
			break;
		
		case "setting":		$ControlPlugins->setting();		break;
				
		case "uninstall" :	$ControlPlugins->unInstall();	break;
			
		case "active" :	$ControlPlugins->active();	break;
	
		case "unactive" :	$ControlPlugins->unActive();	break;
			
		case "admin" :	$ControlPlugins->admin();	break;
				  
		case "install" : 

			$table = $ControlPlugins->viewUnInstalled();
			
			$adinc->getMenu($adinc->_lang("CONTROL_PLUGINS_UNINSTALLED") , $table);
			
		break;
		
		case "setup" :	$ControlPlugins->install();	break;
	}
	
	$adinc->closePage();

?>