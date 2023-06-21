<?php

	class ControlPlugins{
			
		public $hook;
		
		public function run(){
		
			$this->hook = _safeInt($_GET['hook']);
			
		}
		
		public function Management(){
	
			global $db , $adinc;
			
			$Query = $db->query("select * from " . PFX . "hooks");
			
			$t = "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$t .= "<tr>\n
			<td class='td_title'>#</td>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_PLUGINS_NAME") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_PLUGINS_STATUS") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_PLUGINS_SETTING") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_PLUGINS_MANGE") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_PLUGINS_DELETE") . "</td>\n
			</tr>\n
			";

		while($rows = $db->object($Query) ) {
			
		 $t .= "<tr>\n
				<td class='td_row'><span class='id_cont'>$rows->hook_id</span></td>\n
				<td class='td_row'><div>$rows->hook_name</div>
				<div style=\"color:#666;\">". $adinc->_lang("CONTROL_PLUGINS_VERSION") ." : $rows->hook_version | $rows->hook_about</div>
				</td>\n
				"; 
				if($rows->hook_state == 0){ 
					
					$t .= "<td class='td_row'><a href='plugins.php?action=active&hook=$rows->hook_id' title='". $adinc->_lang("ENABLE") ."'><img src='icons/off.png' border='0' /></a></td>\n"; 
				
				}else{ 
				
					$t .= "<td class='td_row'><a href='plugins.php?action=unactive&hook=$rows->hook_id' title='".$adinc->_lang("DISABLE")."'><img src='icons/on.png' border='0' /></a></td>\n"; 
				}
				
				$t .= "<td class='td_row'><a href='plugins.php?action=setting&hook=$rows->hook_id' title='".$adinc->_lang("CONTROL_PLUGINS_SETTING")."'><img src='icons/setting.png' border='0' /></a></td>\n";

				$t .= "<td class='td_row'><a href='plugins.php?action=admin&hook=$rows->hook_id' title='".$adinc->_lang("CONTROL_PLUGINS_MANGE")."'><img src='icons/admin.png' border='0' /></a></td>\n";

				$t .= "<td class='td_row'><a href='plugins.php?action=uninstall&hook=$rows->hook_id' title='".$adinc->_lang("DELETE")."'><img src='icons/remove.png' border='0' /></a></td>\n"; 

				$t .= "</tr>\n";	
			}
			
			$t .= "</table>\n";
			
			return $t;
		}
	
		
		public function viewUnInstalled(){
	
			global $db , $config , $adinc;
			
			$dir = opendir("../" . $config->pluginsPath);
			
			$t = "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$t .= "<tr>\n
			<td class='td_title'>#</td>\n
			<td class='td_title'>".$adinc->_lang("CONTROL_PLUGINS_NAME")."</td>\n
			<td class='td_title'>".$adinc->_lang("CONTROL_PLUGINS_INSTALL_PLUGIN")."</td>\n
			</tr>\n
			";

		while($file = readdir($dir) ) {
						 
			if($file !== ".htaccess"){
					
					$test = str_replace("." , "" , $file);
							
					if(strlen($test) > 1){
							
						$mod_id++;
					
						$query = $db->query("select hook_id from " . PFX . "hooks where hook_file = '$file'");
						
						if($db->total($query) == 0){
							
						 $t .= "<tr>\n
								<td class='td_row'>$mod_id</td>\n
								<td class='td_row'>$file</td>\n
								"; 
								$t .= "<td class='td_row'><a href='plugins.php?action=setup&hook=$file' title='".$adinc->_lang("CONTROL_PLUGINS_INSTALL_PLUGIN")."'><img src='icons/add.png' border='0' /></a></td>\n"; 
								$t .= "</tr>\n";	
						}
						
					}
					
				}
		
			}
	
			$t .= "</table>\n";
				
			return $t;
		}
	
		public function setting(){
			
			global $adinc , $db , $config;
		
			$Query = mysql_query("select * from " . PFX . "hooks where hook_id = $this->hook");
			
			$Rows  = mysql_fetch_assoc($Query);
			
			if(file_exists("../" . $config->pluginsPath . "/" . $Rows['hook_file'])){
				
				include_once("../" . $config->pluginsPath . "/" . $Rows['hook_file']);
		
			}
			
			$ClassName = str_replace(".php" , "" , $Rows['hook_file']);
			$HookSetting = new $ClassName();
			
			if(method_exists($HookSetting,'setting')){
			
				$HookSetting->setting();	
			
			}else{
			
				$adinc->redAlert($adinc->_lang("CONTROL_PLUGINS_NO_SETTING"));	
			}
			
		}

		public function admin(){
					
			global $adinc , $db , $config;
					
			$Query = $db->query("select * from " . PFX . "hooks where hook_id = $this->hook");
			
			$Rows  = $db->rows($Query);
			
			if(file_exists("../" . $config->pluginsPath . "/" . $Rows['hook_file'])){
				
				include_once("../" . $config->pluginsPath . "/" . $Rows['hook_file']);
		
			}
			
			$ClassName = str_replace(".php" , "" , $Rows['hook_file']);
			$HookSetting = new $ClassName();
			
			if(method_exists($HookSetting,'admin')){
			
				$HookSetting->admin();	
			
			}else{
			
				$adinc->redAlert($adinc->_lang("CONTROL_PLUGINS_NO_MANGE"));	
			}
		}
	

		public function unInstall(){

			global $adinc , $config , $db;
			
			$Query = $db->query("select * from " . PFX . "hooks where hook_id = $this->hook");
			
			$Rows  = $db->rows($Query);
			
			if(file_exists("../" . $config->pluginsPath . "/" . $Rows['hook_file'])){
				
				include_once("../" . $config->pluginsPath . "/" . $Rows['hook_file']);
		
			}
			
			$ClassName = str_replace(".php" , "" , $Rows['hook_file']);
			$HookSetting = new $ClassName();
			
			if(method_exists($HookSetting,'unInstall')){
			
				$HookSetting->unInstall();	
			
			}
			
			$Delete = $db->query("delete from " . PFX . "hooks where hook_id = '$this->hook'");
			
			if($Delete){
				
				$adinc->greenAlert($adinc->_lang("DELETE_DONE"));

				$adinc->location('plugins.php');
				
			}
			
		}

		public function install(){
		
			global $db , $adinc , $config;
				
				$file = _safeString($_REQUEST['hook']);
				
				if(!file_exists("../" . $config->pluginsPath . "/" . $file) ){
				
					$adinc->redAlert($adinc->_lang("CONTROL_PLUGINS_PAGE_404"));
					
					return false;	
					
				}

			include_once("../" . $config->pluginsPath . "/" . $file);
										
			$className = str_replace(".php" , "" , $file);
			
			$HookInfo = new $className();
			if(method_exists($HookInfo,'install')){
			
				$HookInfo->install();	
			}
			
			$Insert = $db->query("insert into  " . PFX . "hooks values ('' , '$HookInfo->plugin_name' , '$HookInfo->plugin_version' , '$HookInfo->plugin_about' , '1' , '$HookInfo->plugin_moduleID' , '$file')");
			
			if($Insert) { 
									
				$adinc->greenAlert($adinc->_lang("CONTROL_PLUGINS_INSTALL_DONE"));
							
				$adinc->location('plugins.php');
					
			}
	
		}
		
		
		
		public function active(){
		
			global $adinc , $db;
			
			$Query = $db->query("select * from " . PFX . "hooks where hook_id = $this->hook");

			if(!$db->total($Query)){ 
			
				$adinc->redAlert($adinc->_lang("ERROR_ACTION"));
				
				return false; 
			
			}
			
			$Active = $db->query("update " . PFX . "hooks set hook_state = '1' where hook_id = $this->hook");
			
			if($Active) {
				
					$adinc->greenAlert($adinc->_lang("CONTROL_PLUGINS_ENABLED"));
							
					$adinc->location('plugins.php');
					
				}
		}
		
		public function unActive(){
		
			global $adinc , $db;
			
			$Query = $db->query("select * from " . PFX . "hooks where hook_id = $this->hook");

			if(!$db->total($Query)){ 
			
				$adinc->redAlert($adinc->_lang("ERROR_ACTION"));
				
				return false; 
			
			}
			
			$unActive = $db->query("update " . PFX . "hooks set hook_state = '0' where hook_id = $this->hook");
			
			if($unActive){
				
					$adinc->greenAlert($adinc->_lang("CONTROL_PLUGINS_ENABLED"));
							
					$adinc->location('plugins.php');
					
				}
		}
		
	}
	
?>