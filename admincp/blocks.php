<?php

	session_start();
	
	include("../includes/config.php");

	include("../includes/class.db.php");

	include("adinc/class.adinc.php");
	
	include("adinc/functions.php");
		
	include("adinc/class.menus.php");
	
	include("../includes/class.objectsManager.php");
	
	$adinc->getStyle();

	$action = _safeString($_REQUEST['action']);
		
	$menus = new ControlMenus;
	
	$adinc->getRight('blocks');

	$adinc->getNavbar(array(
		$adinc->_lang("NAVBAR_MENUS_MANGE") => 'blocks.php?action=control' , 
		$adinc->_lang("NAVBAR_MENUS_ADD") => 'blocks.php?action=addmenu'   ,
		$adinc->_lang("NAVBAR_MENUS_DISPLAY_MOD") => 'blocks_area.php'));
	
	switch($action){
	
		default : $menus->Management(); break;
		
		case "addmenu" : $adinc->getMenu($adinc->_lang("CONTROL_MENUS_ADD") , $menus->addMenu() );	break;
		
		case "editmenu" : $menus->editMenu(); break;
		
		case "deletemenu" : $menus->deleteMenu(); break;
	}

	$adinc->closePage();

?>
