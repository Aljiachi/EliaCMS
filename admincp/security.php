<?php
	
	session_start();
	
	include("../includes/config.php");
	
	include("../includes/class.db.php");

	include("adinc/class.adinc.php");
		
	include("adinc/class.security.php");
	
	include("adinc/class.uploader.php");

	include("adinc/class.objectsManager.php");

	$adinc->getStyle();

	$securityManager = new securityManager();

	$Action 	= strip_tags($_GET['action']);

	if($Action == "ppop_debug"){
		
		$securityManager->debugCenter(true);
	
		exit(1);	
	}
							
	$adinc->getRight('security');

	$adinc->getNavbar(array(
		' إعدادات الحماية ' => 'security.php?action=setting' , 
		' نظرة عامة ' => 'security.php?action=new' , 
		' إشعارات الأمان ' => 'security.php?action=logs' , 	
		' العناوين المحظورة ' => 'security.php?action=ips' , 	
		' مركز المتابعة ' => 'security.php?action=debug'
	));
	
	print '<div id="loader"></div>';

	$adinc->getJs("security");
	
	switch($Action){
	
		case "view" :
		default 	 :
			print '1';
		break;
		
		case "setting":	$securityManager->getSetting();	break;
		
		case "logs":	print $securityManager->getLogs();	break;
	
		case "debug" : 	print $securityManager->debugCenter();	break;
		
		case "multiaction_logs":	$securityManager->multiActionLogs();break;
		
		case "multiaction_ips":		$securityManager->multiActionIPS();	break;
		
		case "ips":		print $securityManager->getIPS();	break;
	}
	
	$adinc->closePage();

?>