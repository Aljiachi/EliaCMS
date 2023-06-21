<?php

	ob_start();
	session_start();
	
	define('script_run' , true);
	define('PAGE_NAME' , 'INDEX_PAGE');

	$includes = array(
		"includes/config.php" 				,
		"includes/functions.php"			,
		"includes/class.antispam.php" 		,
		"includes/class.db.php" 			, 
		"includes/class.setting.php" 		,
		"includes/class.events.php" 		,	 
		"includes/class.plugins.php" 		,
		"includes/class.logs.php" 			, 
		"includes/class.template.php" 		,
		"includes/class.objectsManager.php" , 
		"includes/class.menus.php" 			,
		"includes/class.ajaxenginer.php"		
		);
	
	foreach($includes as $include){
	
		if(file_exists($include)){
		
			include_once($include);	
		
		}else{
		
			// Send E-mail 2 Admin
			$config->fileMissed($include);
			// End Page
			die("System Error.");	
		}
			
	}
	
	// Logs Object
	$logs = new Logs();

	$antispam = new eliaAntispam();
	$antispam->run();

	// Plugins Object
	$plugins = new eliaPlugins();
	$plugins->importPlugins();
		
	// Events Object
	$events = new eliaEvents($plugins);

	// trigger Event 2 Events Manager
	$events->triggerEvent('BeforeInitializeAjaxEnginer');
	
	$ajaxEnginer = new eliaAjaxEnginer();
	$ajaxEnginer->run();
	
	// trigger Event 2 Events Manager
	$events->triggerEvent('BeforeConnectionClose');
	
	// Close Mysql Connection
	$db->close();

	// trigger Event 2 Events Manager
	$events->triggerEvent('AfterConnectionClose');
	
?>
