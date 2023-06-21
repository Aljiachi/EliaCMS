<?php

	class eliaCoreApi{
	
		public function addMenu($options , $moveFile=false){
		
			global $db , $setting , $config;
			
			if(is_array($options) && count($options) !== 0){
					
				switch($options['menu_type']){
					case "HTML" : $MenuType = 2;
					case "CODE" : $MenuType = 2;
					case "FILE" : $MenuType = 1;	
				}
				
				switch($options['menu_algin']){
					case "RIGHT" :  $MenuAlgin = 1;
					case "CENTER" : $MenuAlgin = 2;
					case "LEFT" :   $MenuAlgin = 3;					
				}
				
				switch($options['menu_inTemplate']){
					case "YES" : $inTemplate = 0;
					case "NO" : $inTemplate = 1;
					default   :  $inTemplate = 0;
				}
			
				switch($options['menu_indexEnabled']){
					case "YES" : $inIndex = 0;
					case "NO" :  $inIndex = 1;
					default   :  $inIndex = 0;
				}
				
				switch($options['menu_pageEnabled']){
					case "YES" : $inPage = 0;
					case "NO" :  $inPage = 1;
					default   :  $inPage = 0;
				}
				
				switch($options['menu_active']){
					case "YES" : $MenuActived = 1;
					case "NO" :  $MenuActived = 0;
					default   :  $MenuActived = 1;
				}
								
				if($moveFile == true && $MenuType == 1){
				
					if(file_exists("modules/" . $options['module_path'] . "/addons/" . $options['menu_file'] . ".php")){
												
						$MakeMenuFolder		   = mkdir("../blocks/" . $options['menu_file'] , 755);					
						$MoveFileToMenusFolder = rename("modules/" . $options['module_path'] . "/addons/" . $options['menu_file'] . ".php", "../blocks/" . $options['menu_file'] . "/block.php");
					
					}
				}
							
				$db->query("insert into " . PFX . "blocks values  ('' , 
														'".$options["menu_name"]."' , 
														'".intval(abs($options["menu_sort"]))."' , 
														'".$MenuType."' , 
														'".$options["menu_file"]."' , 
														'".$options["menu_title"]."' , 
														'".$MenuActived."' , 
														'".$MenuAlgin."' ,
														'".$inTemplate."' , 
														'".$options["menu_module"]."' ,  
														'".intval(abs($inIndex))."' ,  
														'".intval(abs($inPage))."'
														)");
				
			}
			
		}
		
		public function deleteMenu($actionType , $moveFile=false , $modulePath=''){
		 
			global $db , $setting , $config;
			
			$actions = explode("." , $actionType);
			
			$SQL = "DELETE FROM " . PFX . "blocks WHERE ";
			
			switch($actions[0]){
				case "byFile" 	: 
				
					$SQL .= "file='".$actions[1]."'"; 
					
					if($moveFile == true){

						if(file_exists("../blocks/" . $actions[1] . "/block.php")){
													
							$MoveFileToMenusFolder = rename("../blocks/" . $actions[1] . "/block.php" , "modules/" . $modulePath . "/addons/" . $actions[1] . ".php");
							$deleteMenuFolder	   = rmdir("../blocks/" . $actions[1]);					
							
						}

					}
					
				break;	
				case "byModule" : $SQL .= "area='".$actions[1]."'"; break;	
				case "byId" : $SQL .= "id='".$actions[1]."'"; break;	
				case "byTitle" : $SQL .= "title='".$actions[1]."'"; break;					
			}
			
			$SQL .= " LIMIT 1";
			
			$Delete = $db->query($SQL);
			
			if($Delete) return true;

			
		}
		
		public function addPlugin($pluginFile , $moveFile=false , $modulePath=''){
		
				global $module , $adinc , $config;
				
				$pluginFile .= ".php";
				
				if($moveFile == true){
				
					print $pluginFile;
					
					if(file_exists("modules/" . $modulePath . "/addons/" . $pluginFile)){
												
						$MoveFileToPluginsFolder = rename("modules/" . $modulePath . "/addons/" . $pluginFile , "../plugins/" . $pluginFile);
					
					}
				}
													
				if(!file_exists("../" . $config->hooksPath . "/" . $pluginFile) ){
					
					echo "Dir Was Not Exists";
						
					return false;	
						
				}
	
				include_once("../" . $config->hooksPath . "/" . $pluginFile);
											
				$className = str_replace(".php" , "" , $pluginFile);
				
				$HookInfo = new $className();
				if(method_exists($HookInfo,'install')){
				
					$HookInfo->install();	
				}
				
				$Insert = mysql_query("insert into  " . PFX . "hooks values ('' , '$HookInfo->plugin_name' , '$HookInfo->plugin_version' , '$HookInfo->plugin_about' , '1' , '$HookInfo->plugin_moduleID' , '$pluginFile')");
				
				print mysql_error();
	
				if($Insert) { 
										
					return true;
														
				}
	
			return false;
		}
		
		public function deletePlugin($pluginFile , $moveFile=false , $modulePath=''){
		 
			global $db , $setting , $config;
		
			$pluginFile .= ".php";
			
			$Query = mysql_query("select * from " . PFX . "hooks where hook_file = '$pluginFile'");
			
			$Rows  = mysql_fetch_assoc($Query);
			
			if(file_exists("../" . $config->hooksPath . "/" . $Rows['hook_file'])){
				
				include_once("../" . $config->hooksPath . "/" . $Rows['hook_file']);
		
			}
			
			$ClassName = str_replace(".php" , "" , $Rows['hook_file']);
			$HookSetting = new $ClassName();
			
			if(method_exists($HookSetting,'unInstall')){
			
				$HookSetting->unInstall();	
			
			}
			
			$Delete = mysql_query("delete from " . PFX . "hooks where hook_file = '$pluginFile'");

			if($moveFile == true){
	
				if($Delete){
					
					if(file_exists("../plugins/" . $pluginFile)){
														
						$MoveFileToMenusFolder = rename("../plugins/" . $pluginFile , "modules/" . $modulePath . "/addons/" . $pluginFile);
								
					}
	
					return true;
				}
			}
			
			return false;
		}
		
		public function addPhrase($xmlFile , $modulePath){
			
			global $db , $config , $setting;
			
			if(file_exists("modules/" . $modulePath . "/addons/" . $xmlFile . ".xml")){
			
				$eliaFile = simplexml_load_file("modules/" . $modulePath . "/addons/" . $xmlFile . ".xml");
				
				$eliaLangCode = $eliaFile->language['lang_code'];
				$eliaModule   = $eliaFile->language['module'];
				$eliaLangType = $eliaFile->language['admin'];
				
				if(isset($eliaLangType) && !empty($eliaLangType) && $eliaLangType == "admin"){ $type = 1; }
				
				foreach($eliaFile->language[0] as $key => $var){
									
					if(isset($var['module']) && !empty($var['module'])){ $eliaModule = $var['module']; }
					if(isset($var['admin']) && !empty($var['admin'])){ $type = 1; }
										
					if($db->getFastTotal("select id from " . PFX . "language_words where language_code='$eliaLangCode' and word_name='$var[code]' and module_id='$eliaModule' and word_type='$type'") == 0 and !empty($var) and !empty($var['code'])){
						
						$insertWord  = $db->query("insert into " . PFX . "language_words values('' , '".$var['code']."' , '$var' , '$eliaLangCode' , '$eliaModule' , '$type')");
						
						$eliaModule = $eliaFile->language['module'];

					} // end check
				
				} // end Foreach
					
			} // end if
			
			return true;
		
		} // end function
		
		public function deletePhrase($moduleName){
		
			global $db , $config , $setting;
			
			// Delete 
			$Delete = $db->query("DELETE FROM " . PFX . "language_words WHERE module_id='$moduleName'");	
			
			if($Delete) return true; else return false;
			
		}
		
	} // end Class

?>