<?php

	ob_start();
	session_start();
	
	define('script_run' , true);
	define('PAGE_NAME' , 'PAGE_PAGE');
		
	$includes = array(
		"includes/config.php" ,
		"includes/functions.php",
		"includes/class.antispam.php" ,
		"includes/class.db.php" , 
		"includes/class.setting.php" ,
		"includes/class.events.php" , 
		"includes/class.plugins.php" ,
		"includes/class.logs.php" , 
		"includes/class.template.php" , 
		"includes/class.menus.php" , 
		"includes/class.objectsManager.php");
	
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
		
	// Events Objects 
	$events = new eliaEvents($plugins);
	
	// trigger Event 2 Events Manager
	$events->triggerEvent('BeforeMenusLoad');
	
	// Menus Objects
	$menu = new eliaMenus(array('pageEnabled' => true));		
			
	$id = _id($_REQUEST['id']);
		
	$query = $db->query("select * from " . PFX . "pages where id = $id");
	
	if($db->total($query) == 0){
	
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');	
	}
	
	$rows = $db->rows($query);

	$addview = $db->query("update " . PFX . "pages set page_views=page_views+1 where id = $id");
	
	// trigger Event 2 Events Manager
	$events->triggerEvent('BeforeHeaderDisplay');
	
	addMeta(array(
		'name' => 'keywords' , 
		'content' => $rows['page_meta']
	));
	
	addMeta(array(
		'name' => 'description' , 
		'content' => $rows['page_title']
	));
	
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
        
					$setting->getTemplate("page" , $params);
					
  				_print('</div>');
							
			break;

			case 2 : 
				
				_print('<div id="right_block">');
        
					$menu->getRight(); 
					
  				_print('</div>');

    
 				_print('<div id="allright_block">');
        
					$setting->getTemplate("page" , $params);
					
  				_print('</div>');
				
				break;

			case 3 : 
			
				_print('<div id="left_block">');
        
					$menu->getLeft(); 
					
  				_print('</div>');
				
				_print('<div id="allleft_block">');
        
					$setting->getTemplate("page" , $params);
					
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