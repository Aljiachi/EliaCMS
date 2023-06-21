<?php

	session_start();

	include("../includes/config.php");
	
	include("../includes/class.db.php");

	include("adinc/class.adinc.php");

	include("../includes/functions.php");
		
	include("adinc/class.accounts.php");
	
	include("../includes/class.objectsManager.php");
	
	$adinc->getStyle();

	$ControlAccounts = new ControlAccounts;
	
	$action = _safeString($_REQUEST['action']);
	
	$adinc->getRight('accounts');

	$adinc->getNavbar(array(
		$adinc->_lang("NAVBAR_ACCOUNTS_MANGE") => 'accounts.php' , 
		$adinc->_lang("NAVBAR_ACCOUNTS_NEW") => 'accounts.php?action=addaccount' , 
		$adinc->_lang("NAVBAR_ACCOUNTS_SEARCH") => 'accounts.php?action=search'  ,
		$adinc->_lang("NAVBAR_ACCOUNTS_SETTING") => 'accounts.php?action=setting'
		));
	
	switch($action){
	
		default 			: $ControlAccounts->Management();		break;

		case "addaccount" 	: $ControlAccounts->addaccount(); 		break;

		case "edit"			: $ControlAccounts->editAccount(); 		break;

		case "delete" 		: $ControlAccounts->deleteAccount(); 	break;
		
		case "profile" 		: $ControlAccounts->editProfile(); 		break;
		
		case "multiaction" 	: $ControlAccounts->multiaction(); 		break;

		case "setting" 		: $ControlAccounts->setting(); 			break;
		
		case "search"		: $ControlAccounts->search();		 	break;
		
	}
	
	$adinc->closePage();
	
?>