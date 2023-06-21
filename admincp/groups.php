<?php

	session_start();

	include("../includes/config.php");
	
	include("../includes/class.db.php");

	include("adinc/class.adinc.php");

	include("adinc/functions.php");
		
	include("adinc/class.simulator.php");
	
	include("adinc/class.groups.php");
	
	include("../includes/class.objectsManager.php");
	
	$simulator = new eliaSimulator();
	
	$adinc->events->triggerEvent('BeforeAdmincpStyleLoad');
	
	$adinc->getStyle();

	$ControlGroups = new ControlGroups;
	
	$action = _safeString($_REQUEST['action']);

	$adinc->events->triggerEvent('BeforeAdmincpMenuDisplay');
	
	$adinc->getRight('groups');

	$adinc->events->triggerEvent('BeforeAdmincpNavbarDisplay');

	$adinc->getNavbar(array(
		$adinc->_lang("NAVBAR_GROUPS_MANGE") => 'groups.php?action=control' , 
		$adinc->_lang("NAVBAR_GROUPS_ADD") => 'groups.php?action=addgroup'));
	
	switch($action){
	
		default : 
		
			$adinc->events->triggerEvent('GroupsManagementLoad');

			$ControlGroups->Management(); 
			
		break;

		case "addgroup" : 

			$adinc->events->triggerEvent('AddGroupLoad');

			$ControlGroups->addGroup(); 
		
		break;

		case "editgroup" : 
		
		
			$adinc->events->triggerEvent('EditGroupLoad');

			$ControlGroups->editGroup(); 
		
		break;

		case "deletegroup" : 
		
			$adinc->events->triggerEvent('DeleteGroupLoad');

			$ControlGroups->deleteGroup(); 
			
		break;
		
		case "merge" : 
		
			$adinc->events->triggerEvent('MergeGroupLoad');

			$ControlGroups->mergeGroup(); 
			
			break;
		
	}
	
	$adinc->events->triggerEvent('BeforeAdmincpPageEnd');

	$adinc->closePage();
	
?>