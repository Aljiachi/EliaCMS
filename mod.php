<?php

	// ob_start
	ob_start();
	// start session
	session_start();
	
	define('script_run' , true);
	define('PAGE_NAME' , 'MODULE_PAGE');
		
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
		"includes/class.objectsManager.php" , 
		"includes/class.modules.php");
	
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
	
	// Modules Object
	$mod = new eliaModules();						
	$mod->run();
	
	// Hooks Object
	$plugins = new eliaPlugins();
	$plugins->importPlugins();
		
	// Events Objects 
	$events = new eliaEvents($plugins);

	$antispam = new eliaAntispam();
	$antispam->run();
		
	// Menus Objects
	$menu = new eliaMenus(array('moduleId' => $mod->modInfo['module_id']));		
	
	$modOutput = $mod->page();
		
	addMeta(array('name' => 'viewport' , 'content' => 'width=device-width, initial-scale=1.0, main-scale=1.0'));

	$params = array(
		'title' => $setting->rows['title'] . ' || ' . $mod->modInfo['module_name'] ,
		'module_theme' => $mod->modInfo['module_path'] ,
		'style' => $setting->rows['style'] , 
		'keywords' => $setting->rows['keywords'] ,
		'description' => $setting->rows['meta'] , 
		'HeaderCommon' => $GET_GLOBAL_HEADER_PARAMS
	);
	
	$events->triggerEvent('BeforeHeaderDisplay');
	
	if(file_exists(modulesPath . "/" . $mod->mod . "/style.css")){
		
		$params['theme'] = modulesPath . "/" . $mod->mod . "/style.css";
	}
		
	if($mod->templates !== false){
		
		$setting->getsubHeader($params);	
		
	}

	$events->triggerEvent('AfterHeaderDisplay');

	_print("<div id='blocks_area'>\n");

	
	if($mod->modInfo['menu'] == 1 && $mod->menu !== false && $mod->templates !== false){
						
		switch($setting->rows['block_view']){
			
				case 1 : 
	
					_print('<div id="right_block">');
			
						$menu->getRight(); 
						
					_print('</div>');
		
		
					_print('<div id="left_block">');
			
						$menu->getLeft(); 
						
					_print('</div>');
	   
		
					_print('<div id="center_block">');
			
						_print($modOutput);
						
					_print('</div>');
								
				break;
	
				case 2 : 
					
					_print('<div id="right_block">');
									
						if($mod->modInfo['review'] == 1 && $menu->getMenus(1) == 0){ 
													
							$menu->getLeft(); 
							
						}else{
						
							$menu->getRight();	

						}
						
					_print('</div>');
	
					_print('<div id="allright_block">');
			
						_print($modOutput);
						
					_print('</div>');
					
					break;
	
				case 3 : 
				
					_print('<div id="left_block">');
			
						if($mod->modInfo['review'] == 1 && $menu->getMenus(3) == 0){ 
																				
							$menu->getRight(); 
							
						}else{
						
							$menu->getLeft(); 

						}
												
					_print('</div>');
					
					_print('<div id="allleft_block">');
			
						_print($modOutput);
						
					_print('</div>');
					
				break;
	
			}

	}else{
	
		_print($modOutput);

	}
	
	_print("\n</div>\n");	
	
	$events->triggerEvent('AfterContentDisplay');

	if($mod->templates !== false){
			
		$setting->getFooter();
	
	}
	
	$events->triggerEvent('AfterFooterDisplay');

	$events->triggerEvent('BeforeConnectionClose');
	
	// Close Mysql Connection
	$db->close();

	$events->triggerEvent('AfterConnectionClose');
	
?>