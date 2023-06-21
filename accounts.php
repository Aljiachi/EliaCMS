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
		"includes/class.accounts.php"		
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

	// Accounts Object
	$accounts 	 = new eliaAccounts();
	$pageContent = $accounts->display();
	
	// trigger Event 2 Events Manager
	$events->triggerEvent('BeforeMenusLoad');
	
	// Menus Objects
	$menu = new eliaMenus(array('indexEnabled' => true));		
	
	// trigger Event 2 Events Manager
	$events->triggerEvent('BeforeHeaderDisplay');
	
	// display Header
	$setting->getsubHeader(array(
		'title' => $setting->rows['title'] . ' - ' . $rows['page_title'] ,
		'style' => $setting->rows['style'] , 
		'HeaderCommon' => $GET_GLOBAL_HEADER_PARAMS
	));
	
	// trigger Event 2 Events Manager
	$events->triggerEvent('AfterHeaderDisplay');
	
	_print("<div id='blocks_area'>\n");
	
	switch($setting->rows['block_view']){
		
			case 1 : 

				_print('<div id="right_block">');
        
					$menu->getRight(); 
					
  				_print('</div>');
    
				_print('<div id="left_block">');
        
					$menu->getLeft(); 
					
  				_print('</div>');
    
				_print('<div id="center_block">');
        
					_print($pageContent);
					
  				echo '</div>';
							
			break;

			case 2 : 
				
				echo '<div id="right_block">';
        
					$menu->getRight(); 
					
  				echo '</div>';

    
 				echo '<div id="allright_block">';
        
					_print($pageContent);
					
  				echo '</div>';
				
				break;

			case 3 : 
			
				_print('<div id="left_block">');
        
					$menu->getLeft(); 
					
  				_print('</div>');
				
				_print('<div id="allleft_block">');
        
					_print($pageContent);
					
  				_print('</div>');
				
			break;

		}
	
	_print("\n</div>\n");
	
	// trigger Event 2 Events Manager
	$events->triggerEvent('AfterContentDisplay');
	
	$setting->getFooter();

	// trigger Event 2 Events Manager
	$events->triggerEvent('AfterFooterDisplay');

	// trigger Event 2 Events Manager
	$events->triggerEvent('BeforeConnectionClose');
	
	// Close Mysql Connection
	$db->close();

	// trigger Event 2 Events Manager
	$events->triggerEvent('AfterConnectionClose');
	
?>
