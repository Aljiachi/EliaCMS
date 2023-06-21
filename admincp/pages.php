<?php

	session_start();

	include("../includes/config.php");

	include("../includes/class.db.php");

	include("adinc/class.adinc.php");
	
	include("adinc/functions.php");
		
	include("adinc/class.pages.php");
	
	include("adinc/class.objectsManager.php");
	
	$adinc->getStyle();

	$controlPages = new ControlPages;
	
	$action = _safeString($_REQUEST['action']);
	
	$adinc->getRight('pages');

	$adinc->getNavbar(array(
		$adinc->_lang("NAVBAR_PAGES_MANGE") => 'pages.php?action=control' , 
		$adinc->_lang("NAVBAR_PAGES_ADD") => 'pages.php?action=addpage'));
	
	switch($action){
	
		default : $controlPages->Management(); break;

		case "addpage" : $controlPages->addPage(); break;

		case "editpage" : $controlPages->editPage(); break;

		case "deletepage" : $controlPages->deletePage(); break;
		
	}
	
	$adinc->closePage();
	
?>