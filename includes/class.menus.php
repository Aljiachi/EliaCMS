<?php

	class eliaMenus{
		
			private $indexEnabled;
			private $pageEnabled;
			private $moduleId;
			private $fileManager;
			
			public function __construct($options=array()){

				_debug("Menus Object Initialized" , "green");
	
				if(is_array($options) && count($options) !== 0){
					
					if($options['indexEnabled']) $this->indexEnabled = $options['indexEnabled'];
					if($options['pageEnabled'])  $this->pageEnabled  = $options['pageEnabled'];
					if($options['moduleId'])	 $this->moduleId	 = $options['moduleId'];
		
					// Import Objects Manager
					$objectsManager = new objectsManager;
					// Import Files Manager From objects Manager
					$this->filesManager   = $objectsManager->get('Files');
			
				}
	
			}

			/*(^_^) Elia-CMS (^_^)*/		
			public function getRight(){
				
					global $db , $setting;
					
					$query = $db->query("select * from " . PFX . "blocks where align = 1 and active = 1 order by sort desc");
					
					$sygic = new Template;
					
					$sygic->assign(array("title" , "siteTitle" , "siteName" , "sitename" , "sitetitle") , $setting->rows['title']);
			
					$sygic->assign("style" , $setting->rows['style']);
		
					$sygic->assign(array("siteurl" , "URL" , "sitelink") , $setting->rows['siteurl']);

					$sygic->assign( array("module" , "Module" , "MODULE" , "modulePath") , strip_tags(trim($_REQUEST['mod'])) );
			
					$sygic->assign("HeaderCommon" , "");
			
					$template = new Template;

					$template->Dir = StyleDir;
	
					while($rows = $db->rows($query)){
						
						if( (($this->moduleId !== 0) && in_array($this->moduleId , explode("," , $rows['area']))) || ($this->moduleId !== 0 && $rows['area'] == "ALL") || ($this->indexEnabled == 1 && $rows['in_index'] == 1) || ($this->pageEnabled == 1 && $rows['in_page'] == 1)){
	
							if($rows['display'] == 0){
								
								$template->get("right_block");
								
								$template->assign("title" , $rows['title']);
			
								if($rows['type'] == 2){
									
									$template->assign("content" , $sygic->replace($rows['code']));
								
								}else{
									
									$template->includeInTemplate("content" , "blocks/" . $rows['file'] . "/block.php");
									
									}
								
								$template->replace();
			
								$template->display();
			
								}else{
									
								if($rows['type'] == 2){
									
									_print($rows['code']);
								
								}else{
									
									$this->filesManager->incfile("blocks/" . $rows['file'] . "/block.php");
									
									}
									
								}
						
						}
					}
				}

			/*(^_^) Elia-CMS (^_^)*/		
			public function getLeft(){
				
					global $db , $GET_GLOBAL_TEMPS_ASSIGN , $events;
					
					$query = $db->query("select * from " . PFX . "blocks where align = 3 and active = 1 order by sort desc");
			
					$template = new Template;
	
					$template->Dir = StyleDir;
						
					while($rows = $db->rows($query)){

					if( (($this->moduleId !== 0) && in_array($this->moduleId , explode("," , $rows['area']))) || ($this->moduleId !== 0 && $rows['area'] == "ALL") || ($this->indexEnabled == 1 && $rows['in_index'] == 1) || ($this->pageEnabled == 1 && $rows['in_page'] == 1)){
					
						$sygic = new Template;
						
						$sygic->setTemp($rows['code']);
						
						$sygic->assign(array("title" , "siteTitle" , "siteName" , "sitename" , "sitetitle") , $setting->rows['title']);
				
						$sygic->assign("style" , $setting->rows['style']);
			
						$sygic->assign(array("siteurl" , "URL" , "sitelink") , $setting->rows['siteurl']);
	
						$sygic->assign(array("module" , "Module" , "MODULE" , "modulePath") , strip_tags(trim($_REQUEST['mod'])) );
											
						// check if you have global params
						if(is_array($GET_GLOBAL_TEMPS_ASSIGN) and count($GET_GLOBAL_TEMPS_ASSIGN) !== 0){
						
							// assign global params
							foreach($GET_GLOBAL_TEMPS_ASSIGN as $global_key => $global_var){
								
								$sygic->assign($global_key , $global_var);
							
							}
					
						}
							
						if($rows['display'] == 0){
	
							$template->get("left_block");
							
							$template->assign("title" , $rows['title']);
		
							if($rows['type'] == 2){
								
								ob_start();
								
								$newContent = $sygic->replace();
								
								eval("?>" . $newContent . "<?");
								$eval_buffer = ob_get_contents();
								ob_end_clean();
								$template->assign("content" , $eval_buffer);
							
							}else{
								
								$template->includeInTemplate("content" , "blocks/" . $rows['file'] . "/block.php");
								
								}
							
							$template->replace();
		
							$template->display();
		
							}else{
							
								if($rows['type'] == 2){
										
									_print($rows['code']);
								
								}else{ 
								
									$this->filesManager->incfile("blocks/" . $rows['file'] . "/block.php");
									
							}							
						}						
					}
				}
			}
			
			/*(^_^) Elia-CMS (^_^)*/		
			public function getCenter(){
				
					global $db;
					
					$query = $db->query("select * from " . PFX . "blocks where align = 2 and active = 1 order by sort desc");
										
					$template = new Template;

					$template->Dir = StyleDir;
						
					while($rows = $db->rows($query)){
	
						if( (($this->moduleId !== 0) && in_array($this->moduleId , explode("," , $rows['area']))) || ($this->moduleId !== 0 && $rows['area'] == "ALL") || ($this->indexEnabled == 1 && $rows['in_index'] == 1) || ($this->pageEnabled == 1 && $rows['in_page'] == 1)){
	
							if($rows['display'] == 0){
	
							$template->get("center_block");
							
							$template->assign("title" , $rows['title']);
		
							if($rows['type'] == 2){
									
								$template->assign("content" , $rows['code']);
								
							}else{
									
								$template->includeInTemplate("content" , "blocks/" . $rows['file'] . "/block.php");
									
							}
								
							$template->replace();
		
							$template->display();
	
							}else{
							
								if($rows['type'] == 2){ 
								
									_print($rows['code']);
									
								}else{ 
								
									$this->filesManager->incfile("blocks/" . $rows['file'] . "/block.php");
									
							}								
						}					
					}
				}
			}

			/*(^_^) Elia-CMS (^_^)*/		
			public function getMenus($align){
			
				global $db;
				
				return($db->getFastTotal("select * from " . PFX . "blocks where align = '$align' and active = 1 order by sort desc"));	
			}
		}	
?>