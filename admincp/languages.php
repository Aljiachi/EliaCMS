<?php

	session_start();

	include("../includes/config.php");
	
	include("../includes/class.db.php");

	include("adinc/class.adinc.php");
	
	include("adinc/class.languages.php");
	
	include("adinc/class.uploader.php");

	include("adinc/class.objectsManager.php");
	
	$languagesManager = new languagesManager;

	$Action = strip_tags(trim($_REQUEST['action']));
	
	if($Action == "export"){
	
		$languagesManager->export();
		
		exit;	
	}

	$adinc->getStyle();
		
	$adinc->getRight('languages');

	$adinc->getJs("languages");

	$adinc->getNavbar(array(
	
		' عرض اللغات ' => 'languages.php' , 
		' إضافة لغة ' =>  'languages.php?action=addlang' , 
		' إستيراد عبارات ' => 'languages.php?action=import' ,
		' إضافة عبارة ' => 'languages.php?action=words&langid=1#addword' 
	));
	
	print '<div id="loader"></div>';

	switch($Action){
	
		case "view" :
		default 	 :
			
			$languagesManager->languagesControl();
			
		break;
		
		case "addlang":
			
			$languagesManager->addLang();
			
		break;
		
		case "import" : 		
			
			print $languagesManager->import();
		
		break;
		
		case "words":
		
			print $languagesManager->wordsControl();
		
		break;
		
		case "delete":
		
			$languagesManager->deleteLanguage();
		
		break;
		
	}
			
	$adinc->closePage();

?>
