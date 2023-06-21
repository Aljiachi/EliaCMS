<?php

	session_start();
	
	include("../includes/config.php");

	include("../includes/class.db.php");

	include("adinc/class.adinc.php");
	
	include("adinc/functions.php");
		
	include("adinc/class.themes.php");
	
	include("../includes/class.objectsManager.php");
	
	$adinc->getStyle();

	$controlThemes = new ControlThemes;
	
	$action = _safeString($_REQUEST['action']);
	
	$adinc->getRight('themes');
	
	$adinc->getNavbar(array(
		$adinc->_lang("NAVBAR_THEMES_MANGE") => 'themes.php?action=control' , 
		$adinc->_lang("NAVBAR_THEMES_ADD") => 'themes.php?action=addtheme' , 
		$adinc->_lang("NAVBAR_THEMES_EDITOR") => 'themes.php?action=editor'));
	
	switch($action){
	
		default : $controlThemes->Management(); break;

		case "addtheme" : $controlThemes->addTheme(); break;

		case "editor" : $controlThemes->editor(); break;

		case "edit" : $controlThemes->editTheme(); break;

		case "remove" : $controlThemes->deleteTheme(); break;
		
	}
	
	$adinc->closePage();
	
?>