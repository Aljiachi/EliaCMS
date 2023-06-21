<?php

	class ControlModules{
			
		public $module , $installId , $installPath , $elia;
		
		public function run(){
		
			$this->module = _safeString($_GET['module']);

		}
		
		public function getAnswer($ask , $return='1|0'){

			$returnArray = explode("|" , $return);
			
			if(!in_array($ask , array("NO" , "disable" , "DISABLE" , "no" , "hidden" , false , "FALSE"))){
							
				return $returnArray[0];
						
			}else if(empty($ask)){
					
				return $returnArray[0];
								
			}else{
						
				return $returnArray[1];	
			
			}
				
		}
		
		public function Management(){
	
			global $db , $adinc;
			
			$Query = $db->query("select * from " . PFX . "modules");
			
			$html = "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$html .= "<tr>\n
			<td class='td_title' width='30'>#</td>\n
			<td class='td_title'>". $adinc->_lang("CONTROL_MODULES_MODNAME") ."</td>\n
			<td class='td_title' width='80' align='center'>". $adinc->_lang("CONTROL_MODULES_STATUS") ."</td>\n
			<td class='td_title' width='80' align='center'>". $adinc->_lang("CONTROL_MODULES_MODULE_SETTING") ."</td>\n
			<td class='td_title' width='80' align='center'>". $adinc->_lang("CONTROL_MODULES_MANGE_MODULE") ."</td>\n
			<td class='td_title' width='80' align='center'>". $adinc->_lang("CONTROL_MODULES_MANGE_TEMPLATES") ."</td>\n
			<td class='td_title' width='80' align='center'>". $adinc->_lang("CONTROL_MODULES_MANGE_WORDS") ."</td>\n
			<td class='td_title' width='80' align='center'>". $adinc->_lang("CONTROL_MODULES_EDIT") ."</td>\n
			<td class='td_title' width='80' align='center'>". $adinc->_lang("CONTROL_MODULES_DELETE") ."</td>\n
			</tr>\n
			";

		while($rows = $db->object($Query) ) {
			
		 $html .= "<tr>\n
				<td class='td_row'><span class='id_cont'>$rows->module_id</span></td>\n
				<td class='td_row'>
				<div class='module_name'>$rows->module_name</div>
				<div class='module_about'>$rows->about <span style='margin-left:10px;margin-right:10px;'>|</span> $rows->site </div>
				</td>\n
				"; 
				
				if($rows->module_active == 0){ 
					
					$html .= "<td class='td_row' align='center'><a href='modules.php?action=unactive&module=$rows->module_id'><img src='icons/off.png' border='0' /></a></td>\n"; 
				
				}else{ 
				
					$html .= "<td class='td_row' align='center'><a href='modules.php?action=active&module=$rows->module_id'><img src='icons/on.png' border='0' /></a></td>\n"; 
				
				}
				
				$html .= "<td class='td_row' align='center'><a href='modules.php?action=setting&module=$rows->module_id'><img src='icons/setting.png' border='0' /></a></td>\n";
				$html .= "<td class='td_row' align='center'><a href='modules.php?action=admin&module=$rows->module_id'><img src='icons/admin.png' border='0' /></a></td>\n";
				$html .= "<td class='td_row' align='center'><a href='themes.php?action=editor&module=$rows->module_path'><img src='icons/template_editor.png' border='0' /></a></td>\n"; 
				$html .= "<td class='td_row' align='center'><a href='languages.php?action=words&module=$rows->module_path&langid=1'><img src='icons/edit.png' border='0' /></a></td>\n"; 
				$html .= "<td class='td_row' align='center'><a href='modules.php?action=edit&module=$rows->module_id'><img src='icons/edit.png' border='0' /></a></td>\n"; 				
				$html .= "<td class='td_row' align='center'><a onclick=\"if(window.confirm('". $adinc->_lang("ALERT_CONFIRM") ."') == false){return false;}\"  href='modules.php?action=uninstall&module=$rows->module_id'><img src='icons/remove.png' border='0' /></a></td>\n"; 
				$html .= "</tr>\n";	
			}
			
			$html .= "</table>\n";
			
			$adinc->getMenu($adinc->_lang("CONTROL_MODULES") , $html);
		}
		
		public function edit(){
		
			global $db , $adinc;
			
			$query = $db->query("select * from " . PFX . "modules where module_id='$this->module' limit 1");
			
			if($db->total($query) !== 0){
			
				$rows = $db->rows($query);
	
				if(isset($_POST['edit_module'])){
					
					$info = array(
								'moduleName' => $_POST['module_name'], 
								'moduleDisplayMenus' => _safeInt($_POST['module_display_menus']) , 
								'moduleAnymenu' => _safeInt($_POST['module_anymenu']));
				
					if(!empty($info['moduleName'])){
					
						$updateQuery = $db->query("update " . PFX . "modules set 
														module_name='$info[moduleName]' , 
														menu='$info[moduleDisplayMenus]' ,
														review='$info[moduleAnymenu]'
														where module_id='$this->module'");	
					
						if($updateQuery){
							
							// display message
							$adinc->greenAlert($adinc->_lang("UPDATE_DONE"));	
							// back to modules.php
							$adinc->location("modules.php");
						}
						
					}
				}
				
				$html = "<form method=\"post\">\n<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
				// Module Name
				$html .= "<tr>\n";
				$html .= "<td width=\"30%\">" . $adinc->_lang("CONTROL_MODULES_MODNAME") . "</td>";
				$html .= "<td><input type=\"text\" name=\"module_name\" value=\"$rows[module_name]\" /></td>";
				$html .= "</tr>\n";
				// Module (Display Menus option)
				$html .= "<tr>\n";
				$html .= "<td width=\"30%\">" . $adinc->_lang("CONTROL_MODULES_DISPLAY_MENU") . "</td>";
				$html .= "<td><select name=\"module_display_menus\">\n";
				$html .= "<option value=\"1\""; if($rows['menu'] == 1){ $html .= " selected=\"selceted\""; } $html.= ">" . $adinc->_lang("YES") . "</option>";
				$html .= "<option value=\"0\""; if($rows['menu'] == 0){ $html .= " selected=\"selceted\""; } $html.= ">" . $adinc->_lang("NO") . "</option>";
				$html .= "</select>\n</td>\n";
				$html .= "</tr>\n";
				// Module (Display Right menus in Left Side when right menus count = 0)
				$html .= "<tr>\n";
				$html .= "<td width=\"30%\">" . $adinc->_lang("CONTROL_MODULES_ANYMENU") . "</td>";
				$html .= "<td><select name=\"module_anymenu\">\n";
				$html .= "<option value=\"1\""; if($rows['review'] == 1){ $html .= " selected=\"selceted\""; } $html.= ">" . $adinc->_lang("YES") . "</option>";
				$html .= "<option value=\"0\""; if($rows['review'] == 0){ $html .= " selected=\"selceted\""; } $html.= ">" . $adinc->_lang("NO") . "</option>";
				$html .= "</select>\n</td>\n";
				$html .= "</tr>\n";
				// Submit
				$html .= "<tr>\n";
				$html .= "<td width=\"30%\"></td>";
				$html .= "<td><input type=\"submit\" name=\"edit_module\" value=\"".$adinc->_lang("SAVE")."\" /></td>";
				$html .= "</tr>\n";
				// End				
				$html .= "\n</table>\n</form>";
				
				$adinc->getMenu($adinc->_lang("CONTROL_MODULES_EDIT") , $html);
			}
		}
	
		
		public function viewUnInstalled(){
	
			global $db , $adinc;;
			
			$dir = opendir("../" . modulesPath);
			
			$html = "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			$html .= "<tr>\n
			<td class='td_title'>#</td>\n
			<td class='td_title'>". $adinc->_lang("CONTROL_MODULES_MODNAME") ."</td>\n
			<td class='td_title'>". $adinc->_lang("CONTROL_MODULES_INSTALL") ."</td>\n
			</tr>\n
			";

			while($file = readdir($dir) ) {
							 					
				if(strlen(str_replace("." , "" , $file)) > 1){
						
				$counter++;
			
				$query = $db->query("select module_id from " . PFX . "modules where module_path = '$file'");
				
					if($db->total($query) == 0){
					
						$html .= "<tr>\n<td class='td_row'>$counter</td>\n<td class='td_row'>$file</td>\n"; 
						$html .= "<td class='td_row'><a href='modules.php?action=setup&module=$file'><img src='icons/add.png' border='0' /></a></td>\n"; 
						$html .= "</tr>\n";	
					
					}
					
				}
			
			}

			$html .= "</table>\n";
			
			
			$adinc->getMenu($adinc->_lang("CONTROL_MODULES_UNINSTALLED") , $html);

		}
	
		public function setting(){
			
			global $adinc , $db;
		
			$Query = mysql_query("select * from " . PFX . "modules where module_id = $this->module");
			
			$Rows  = mysql_fetch_assoc($Query);
			
			if(file_exists("modules/" . $Rows['module_path'] . "/control/setting.php")){
				
				include_once("modules/" . $Rows['module_path'] . "/control/setting.php");
		
			}
		}

		public function admin(){
					
			global $adinc , $db , $api;
					
			$id = intval(abs($_REQUEST['module']));
			
			$Query = mysql_query("select * from " . PFX . "modules where module_id = $id");
			
			$Rows  = mysql_fetch_assoc($Query);
			
			$mod   = $Rows['module_path'];
									
			if(!$_REQUEST['method']){
			
				$method = "main";
			
			}else{
				
				$method = strip_tags(trim($_REQUEST['method']));
				
				}
			
						
			if(file_exists("modules/" . $mod . "/admin/module.admin.php") ){
											
				include_once("modules/" . $mod . "/admin/module.admin.php");
			
			}else{
			
				$adinc->redAlert($adinc->_lang("CONTROL_MODULES_PAGE_404"));
			
			}
			
			$moduleAdmin = new eliaModuleAdmin();
			
			if(method_exists($moduleAdmin , $method)){
				
				$moduleAdmin->$method();
			}

		}
	

		public function includeAdminfile($page){
					
			global $adinc , $db;
					
			$id = intval(abs($_REQUEST['module']));
			
			$Query = mysql_query("select * from " . PFX . "modules where module_id = $id");
			
			$Rows  = mysql_fetch_assoc($Query);
			
			$mod   = $Rows['module_path'];
						
			if(file_exists("modules/" . $mod . "/admin/" . $page . ".php") ){
											
				include_once("modules/" . $mod . "/admin/" . $page . ".php");
			
			}else{
			
				$adinc->redAlert($adinc->_lang("CONTROL_MODULES_PAGE_404"));
			
			}


		}
		
		public function unInstall(){

			global $adinc , $db;
			
			$Query = $db->query("select * from " . PFX . "modules where module_id = $this->module");
			
			if($db->total($Query) == 0) exit;
			
			$Rows  = $db->rows($Query);
						
			$RenameInstallFile = rename(			
								"modules/" . $Rows['module_path'] . "/control/" . $Rows['installISD'],
								"modules/" . $Rows['module_path'] . "/control/module.install.php"
								);
								
			$RenameUnInstallFile = rename(
								"modules/" . $Rows['module_path'] . "/control/" . $Rows['unInstallISD'],
								"modules/" . $Rows['module_path'] . "/control/module.uninstall.php"
								);
			
			if(file_exists("modules/" . $Rows['module_path'] . "/control/module.uninstall.php")) include_once("modules/" . $Rows['module_path'] . "/control/module.uninstall.php");

			$moduleAdmin = new eliaModuleUnInstall();
				
			if(method_exists($moduleAdmin , 'onBeforeUnInstall')){
					
				$moduleAdmin->onBeforeUnInstall();
			}
						
			if(method_exists($moduleAdmin , 'main')){
					
				$moduleAdmin->main();
			}
				
			$Delete = $db->query("delete from " . PFX . "modules where module_id = $this->module");
			
			if($Delete){

				if(method_exists($moduleAdmin , 'onAfterUnInstall')){
						
					$moduleAdmin->onAfterUnInstall();
				}
									
				$adinc->greenAlert($adinc->_lang("CONTROL_MODULES_UNINSTALL_DONE"));
			}
			
		}
		
		// يجب العودة
		public function add(){
		
			global $module , $adinc;
			
			if(isset($_POST['addmodule'])){
				
				$Dir = $_POST['module_dir'];
				
				if(!file_exists("../" . modulesPath . "/" . $Dir . "/index.php") ){
				
					$adinc->redAlert($adinc->_lang("CONTROL_MODULES_CHECK_FOLDER"));
					
					exit;					
				}
				
			$InstallISD = md5(rand(11111,89898));
			$UnInstallISD = md5(rand(11111,89898));

			if(!file_exists("modules/" . $Dir . "/control/install.php")){
				
					$adinc->redAlert($adinc->_lang("CONTROL_MODULES_CHECK_FOLDER"));
					
					exit;					

				}
				
			include_once("modules/" . $Dir . "/control/install.php");
			
			if(function_exists("beforeInstall")) beforeInstall();
								
			if(empty($_POST['module_name']) ) {   $name = $module['name']; }else{ $name = $_POST['module_name']; }
			if(empty($_POST['module_active']) ) { $act = $module['actived']; }else{ $act = $_POST['module_active']; }
			
			$about= $module['about'];
			
			$website= $module['website'];
			
			$moduleID = $module['moduleID'];

			$menuShown = $this->getAnswer($module['menuShown']);
			
			if(empty($moduleID)){
			
				$moduleID = $Dir . '.elia';
			}
			
			$Insert = mysql_query("insert into  " . PFX . "modules values ('' , '$name' , '$act' , '$Dir' , '$InstallISD' , '$UnInstallISD' , '$moduleID' , '$about' , '$website' , '$menuShown')");
			
			if($Insert) { 
				
				$RenameInstallFile = rename(
									"modules/" . $Dir . "/control/install.php" , 
									"modules/" . $Dir . "/control/$InstallISD"
									);
									
				$RenameUnInstallFile = @rename(									
									"modules/" . $Dir . "/control/uninstall.php" ,
									"modules/" . $Dir . "/control/$UnInstallISD"
									);
									
					$adinc->greenAlert($adinc->_lang("CONTROL_MODULES_INSTALL_DONE"));
							
					if(function_exists("afterInstall")) afterInstall();

					$adinc->location('modules.php');
					
				}
			}
			
			$form = '<script type="text/javascript">
			function access(){
				var folder = $("#dir").val();
				folder.replace(/ /gi , "");
				if(folder == ""){
					$("#alert_folder").html(\'<span class="red_alert">'. $adinc->_lang("ERROR_EMPTY_INPUT") . '</span>\').fadeIn("slow");
					return false;
							
				}	
			}
			</script>' . "\n";
			$form .= "<form method='post'>\n
			<table cellpadding='0' width='100%' cellspacing='0' class='table' border='0'>
			<tr>
			<td width='20%'>". $adinc->_lang("CONTROL_MODULES_MODNAME") ." : </td>
			<td class='td'><input type='text' name='module_name' /> </td>
			</tr>
			<tr>
			<td>". $adinc->_lang("CONTROL_MODULES_MODFOLDER") ." : </td>
			<td class='td'><input type='text' id='dir' name='module_dir' /> <span id='alert_folder' style='display:none;'></span>
				<select onchange='document.getElementById(\"dir\").value = this.value'>
			    <option value=''>". $adinc->_lang("CONTROL_FAST_MENU") ."</option>";
			
				$blcks_opendir = opendir("../" . modulesPath);
				
				while($file = readdir($blcks_opendir)){
						
						$test = str_replace("." , "" , $file);
						
						if(strlen($test) > 1 && $file !== ".htaccess"){
							
							$form .= '<option value="'.$file.'">' . $file . '</option>';		
					
						}
					}
			
			$form .= "</select>\n</td>\n</tr>\n
					  <tr>\n<td class='td'><input type='submit' onclick='return access()' value='إضافة' name='addmodule' /></td>\n</tr>
					  </table>";
				
			$adinc->getMenu($adinc->_lang("CONTROL_MODULES_ADD") , $form);

		}
		
		
		public function install(){
		
			global $module , $adinc , $db;
				
				$Dir = $_REQUEST['module'];
				
				if(!file_exists("../" . modulesPath . "/" . $Dir . "/index.php") ){
				
					$adinc->redAlert($adinc->_lang("CONTROL_MODULES_CHECK_FOLDER"));
					
					return false;	
					
				}
					
				$InstallISD = md5(rand(11111,89898));
				$UnInstallISD = md5(rand(11111,89898));	
				
				if(file_exists("modules/" . $Dir . "/control/module.install.php")) include_once("modules/" . $Dir . "/control/module.install.php");
				
				$moduleAdmin = new eliaModuleInstall();
				
				if(method_exists($moduleAdmin , 'onBeforeInstall')){
					
					$moduleAdmin->onBeforeInstall();
				}
						
				if(method_exists($moduleAdmin , 'main')){
					
					$moduleAdmin->main();
				}
																	
				$name 		= 		$moduleAdmin->module['name'];
				
				$act 		= 		$moduleAdmin->module['actived'];
				
				$about		= 		$moduleAdmin->module['about'];
				
				$website	=	 	$moduleAdmin->module['website'];
				
				$moduleID 	=		$moduleAdmin->module['moduleID'];
	
				$menuShown 	= 		$this->getAnswer($moduleAdmin->module['menuShown']);
	
				$anyMenu 	= 		$this->getAnswer($moduleAdmin->module['anyMenu']);
				
				if(empty($moduleID)){
				
					$moduleID = $Dir . '.elia';
				}
				
				$Insert = $db->query("insert into  " . PFX . "modules values ('' , '$name' , '$act' , '$Dir' , '$InstallISD' , '$UnInstallISD' , '$moduleID' , '$about' , '$website' , '$menuShown' , '$anyMenu')");
				
				if($Insert) { 
							
			
						$RenameInstallFile = rename("modules/" . $Dir . "/control/module.install.php" , 
													"modules/" . $Dir . "/control/$InstallISD");
													
						$RenameUnInstallFile = @rename("modules/" . $Dir . "/control/module.uninstall.php" ,
														"modules/" . $Dir . "/control/$UnInstallISD");
												
						$adinc->greenAlert($adinc->_lang("CONTROL_MODULES_INSTALL_DONE"));
	
						$this->installId 		 = mysql_insert_id();
						$this->installPath		 = $Dir;
								
						if(method_exists($moduleAdmin , 'onAfterInstall')){
							
							$moduleAdmin->onAfterInstall();
						}
												
						$adinc->location('modules.php');
						
				}
	
		}
		
		
		
		public function active(){
		
			global $adinc , $db;
			
			$Query =  $db->query("select * from " . PFX . "modules where module_id = $this->module");

			if($db->total($Query) == false){ echo "Error;"; return false; }
			
			$Active =  $db->query("update " . PFX . "modules set module_active = 0 where module_id = $this->module");
			
			if($Active) {
				
					$adinc->greenAlert($adinc->_lang("CONTROL_ENABLE_ACTION_DONE"));
							
					$adinc->location('modules.php');
					
				}
		}
		
		public function unActive(){
		
			global $adinc , $db;
			
			$Query = $db->query("select * from " . PFX . "modules where module_id = $this->module");

			if($db->total($Query) == false){ echo "Error;"; return false; }
			
			$Active = $db->query("update " . PFX . "modules set module_active = 1 where module_id = $this->module");
			
			if($Active){
				
					$adinc->greenAlert($adinc->_lang("CONTROL_DISABLE_ACTION_DONE"));
							
					$adinc->location('modules.php');
					
				}
		}
		
	}
	
?>