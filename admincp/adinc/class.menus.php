<?php

	class ControlMenus{
	
		public function addMenu(){
		
				global $config , $adinc , $db;
				
				$modules = $db->query("select * from " . PFX . "modules");
	
				if(isset($_POST['addblock'])){
										
						$title = $_POST['block_title'];
					
						$sort   = _safeInt($_POST['block_sort']);
						
						$active = _safeInt($_POST['block_active']);
						
						$type   = _safeInt($_POST['block_type']);
					
						$file_include = _safeString($_POST['file']);
						
						$code     =  $_POST['code'];
						
						$align    = _safeInt($_POST['align']);
	
						$display  = _safeInt($_POST['display']);
						
						$in_index = _safeInt($_POST['in_index']);
	
						$in_page  = _safeInt($_POST['in_page']);
					
						$allModules = _safeString($_POST['ALL_MODULES']);
	
						if($allModules && $allModules == "ALL"){ 
						
							$areas = "ALL";
							
						}else {
								
							$areas  =  $_POST['area'];
							$areas  =  implode("," , $areas);
	
						}
							
						$insert = $db->query("insert into " . PFX . "blocks values('' , '$title' , '$sort' , '$type' , '$file' , '$code' , '$active' , '$align' , '$display' , '$areas' , '$in_index' , '$in_page')");
					
						if($insert){
							
								$adinc->greenAlert($adinc->_lang("SAVE_DONE"));
								
								$adinc->location('blocks.php?action=control');
								
							}
					}
					
				
				$form = '<script type="text/javascript">
				
					function viewcode(){
						
							$("#code").fadeIn("slow");
							
							$("#file_include").hide();
							
							var Empty = document.getElementById("select_file").value = "code";
						}
						
					function viewinclude(){
						
							$("#code").hide(); 
							
							$("#file_include").fadeIn("slow");
							
							var Empty = document.getElementById("text_code").value = "";
						}
		function access(){
			
			
				var title = $("#block_title").val();
				
					title.replace(/ /gi , "");
					
				if(title == ""){
					
						$("#alert_title").html(\'<span class="red_alert">'. $adinc->_lang("ERROR_EMPTY_INPUT") . '</span>\').fadeIn("slow");
			
						return false;
						
					}	
			}
				</script>
				<form method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="20%">'. $adinc->_lang("CONTROL_MENUS_TITLE") . '</td>
		<td><input type="text" id="block_title" name="block_title" /> <span id="alert_title" style="display:none;"></span></td>
	  </tr>
	  <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_SORT") . '</td>
		<td><input type="text" name="block_sort" /></td>
	  </tr>
	 <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_ALIGN") . '</td>
		<td>
		<select name="align" id="align">
		<option value="1">'. $adinc->_lang("CONTROL_MENUS_RIGHT") . '</option>
		<option value="2">'. $adinc->_lang("CONTROL_MENUS_CENTER") . '</option>
		<option value="3">'. $adinc->_lang("CONTROL_MENUS_LEFT") . '</option>
	
		</select>
		</td>
	  </tr>
	 <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_DISPLAY_IN_TEMPLATE") . '</td>
		<td>
		<select name="display" id="align">
		<option value="0">'. $adinc->_lang("YES") . '</option>
		<option value="1">'. $adinc->_lang("NO") . '</option>
	
		</select>
		</td>
	  </tr>
	  <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_STATUS") . '</td>
		<td>
		<div class="radio_box">
		'. $adinc->_lang("ENABLED") . ' : <input type="radio" checked value="1" name="block_active" /> 
		</div>
		<div class="radio_box">
		 '. $adinc->_lang("DISABLED") . ' : <input type="radio" value="0" name="block_active" />
		 </div>
		 </td>
	  </tr>
	  <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_IN_INDEX") . '</td>
		<td>
		<div class="radio_box">
		'. $adinc->_lang("YES") . ' : <input type="radio" checked value="1" name="in_index" /> 
		</div>
		<div class="radio_box">
		 '. $adinc->_lang("NO") . ' : <input type="radio" value="0" name="in_index" />
		 </div>
		 </td>
	  </tr>
	  <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_IN_PAGES") . '</td>
		<td>
		<div class="radio_box">
		'. $adinc->_lang("YES") . ' : <input type="radio" checked value="1" name="in_page" /> 
		</div>
		<div class="radio_box">
		 '. $adinc->_lang("NO") . ' : <input type="radio" value="0" name="in_page" />
		 </div>
		 </td>
	  </tr>
	  <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_IN_MODULES") . '</td>
		<td>';
	
			$form .= '<div> <input type="checkbox" name="ALL_MODULES" value="ALL" class="checkbox_id" /> <span class="module_name">'. $adinc->_lang("CONTROL_MENUS_ALL_MODULES") . '</span></div>';	
		
			while($rmodule = $db->rows($modules)){
			
				$form .= '<div> <input type="checkbox" name="area[]" value="'.$rmodule['module_id'].'" class="checkbox_id" /> <span class="module_name">'.$rmodule['module_name'].'</span></div>';	
			}
			
		$form .='</td>
	  </tr>
	  <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_TYPE") . '</td>
		<td>
			<div class="radio_box"> '. $adinc->_lang("CONTROL_MENUS_TYPE_CODE") . ' : <input type="radio" onChange="viewcode()" checked value="2" name="block_type" /> </div>
			<div class="radio_box">  '. $adinc->_lang("CONTROL_MENUS_TYPE_FILE") . ' : <input type="radio"  onclick="viewinclude()"  value="1" name="block_type" /></div>
		</td>
	  </tr>
	  <tr>
		<td>'. $adinc->_lang("CONTROL_MENUS_CONTENT") . '</td>
		<td>
		<div id="code">
		<textarea name="code" id="text_code"></textarea>
		</div>
		<div id="file_include" style="display:none;">
		<select name="file" id="select_file">
		<option value="code">'. $adinc->_lang("NO_FILE_SELECTED") . '</option>';
		
			$blcks_opendir = opendir("../blocks");
			
			while($file = readdir($blcks_opendir)){
					
					$test = str_replace("." , "" , $file);
					
					if(strlen($test) > 1 and $file !== ".htaccess"){
						
						$form .= '<option value="'.$file.'">' . $file . '</option>';		
				
					}
				}
		
		$form .= '
			</select>
			</div>
			</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="addblock" onclick="return access();" value="'. $adinc->_lang("SAVE") . '" /></td>
		  </tr>
		</table>
		</form>';
				
		 return($form);
		}
		
		public function Management(){

			global $db , $adinc;
			
			// append Language to js Scripts
			$adinc->jsLanguage(array(
				'ENABLE' => $adinc->_lang("ENABLE") ,
				'DISABLE' => $adinc->_lang("DISABLE") ));
				 
			// Include JS Scripts
			$adinc->getJs("ui.mouse");
			$adinc->getJs("ui.draggable");
			$adinc->getJs("ui.droppable");
			$adinc->getJs("ui.sortable");
			$adinc->getJs("menus");
		
			$controlRight = $db->query("select * from " . PFX . "blocks where align='1' order by sort desc");
			$controlLeft = $db->query("select * from " . PFX . "blocks where align='3' order by sort desc");
			$controlCenter = $db->query("select * from " . PFX . "blocks where align='2' order by sort desc");

			$html .= "<div class=\"rightMenusArea\">\n"; 
			
				while($rows_control = $db->rows($controlRight)){

							if($rows_control['active'] == 0){$classPlus = "menuBox menuDisabled";}else{$classPlus = "menuBox";}
							
							$html .= '<div class="'.$classPlus.'" id="menuBox-'.$rows_control['id'].'">
								<div data-id="'.$rows_control['id'].'" data-sort="'.$rows_control['sort'].'" data-type="'.$rows_control['align'].'">'.$rows_control['title'].'</div>
								<p style="padding:3px; float:left; padding-left:10px;">';
								$html .= '<a href="blocks.php?action=editmenu&id='.$rows_control['id'].'" title="'. $adinc->_lang("EDIT") .'" class="menuControlItem"><img src="icons/edit.png" border="0" /></a>';
								$html .= '<a onclick="if(window.confirm(\''. $adinc->_lang("ALERT_CONFIRM") .'\') == false){return false;}" title="'. $adinc->_lang("DELETE") .'" href="blocks.php?action=deletemenu&id='.$rows_control['id'].'" class="menuControlItem"><img src="icons/remove.png" border="0" /></a>';
								if($rows_control['active'] == 1){
									
									$html .= '<a href="#" onclick="activeMenu(\'disable\' , '.$rows_control['id'].')" title="'. $adinc->_lang("DISABLE") .'" class="menuControlItem" id="disable-menu-'.$rows_control['id'].'"><img src="icons/off.png" border="0" /></a>';	
								
								}else{

									$html .= '<a href="#" onclick="activeMenu(\'enable\' , '.$rows_control['id'].')" title="'. $adinc->_lang("ENABLE") .'" class="menuControlItem"  id="enable-menu-'.$rows_control['id'].'"><img src="icons/on.png" border="0" /></a>';	
									
								}
						$html .= '<a href="#" id="menuUp" class="menuControlItem" title="'. $adinc->_lang("MOVE_UP") .'"><img src="icons/up.png" /></a><a href="#" title="'. $adinc->_lang("MOVE_DOWN") .'" id="menuDown" class="menuControlItem"><img src="icons/down.png" /></a>
								</p>
								</div>' . "\n";
				}
				
			$html .= "\n</div>";
			$html .= "<div class=\"leftMenusArea\">"; 
			
				while($rows_control = $db->rows($controlLeft)){

							if($rows_control['active'] == 0){$classPlus = "menuBox menuDisabled";}else{$classPlus = "menuBox";}
	
							$html .= '<div class="'.$classPlus.'" id="menuBox-'.$rows_control['id'].'">
								<div data-id="'.$rows_control['id'].'" data-sort="'.$rows_control['sort'].'" data-type="'.$rows_control['align'].'">'.$rows_control['title'].'</div>
								<p style="padding:3px; float:left; padding-left:10px;">';
								$html .= '<a href="blocks.php?action=editmenu&id='.$rows_control['id'].'" title="'. $adinc->_lang("EDIT") .'" class="menuControlItem"><img src="icons/edit.png" border="0" /></a>';
								$html .= '<a onclick="if(window.confirm(\''. $adinc->_lang("ALERT_CONFIRM") .'\') == false){return false;}" title="'. $adinc->_lang("DELETE") .'" href="blocks.php?action=deletemenu&id='.$rows_control['id'].'" class="menuControlItem"><img src="icons/remove.png" border="0" /></a>';
								if($rows_control['active'] == 1){
									
									$html .= '<a href="#" onclick="activeMenu(\'disable\' , '.$rows_control['id'].')" title="'. $adinc->_lang("DISABLE") .'" class="menuControlItem" id="disable-menu-'.$rows_control['id'].'"><img src="icons/off.png" border="0" /></a>';	
								
								}else{

									$html .= '<a href="#" onclick="activeMenu(\'enable\' , '.$rows_control['id'].')" title="'. $adinc->_lang("ENABLE") .'" class="menuControlItem"  id="enable-menu-'.$rows_control['id'].'"><img src="icons/on.png" border="0" /></a>';	
									
								}
						$html .= '<a href="#" id="menuUp" class="menuControlItem" title="'. $adinc->_lang("MOVE_UP") .'"><img src="icons/up.png" /></a><a href="#" title="'. $adinc->_lang("MOVE_DOWN") .'" id="menuDown" class="menuControlItem"><img src="icons/down.png" /></a>
								</p>
								</div>' . "\n";
				}
				
			$html .= "</div>";
			$html .= "<div class=\"centerMenusArea\">\n";
		
				while($rows_control = $db->rows($controlCenter)){

							if($rows_control['active'] == 0){$classPlus = "menuBox menuDisabled";}else{$classPlus = "menuBox";}
	
							$html .= '<div class="'.$classPlus.'" id="menuBox-'.$rows_control['id'].'">
								<div data-id="'.$rows_control['id'].'" data-sort="'.$rows_control['sort'].'" data-type="'.$rows_control['align'].'">'.$rows_control['title'].'</div>
								<p style="padding:3px; float:left; padding-left:10px;">';
								$html .= '<a href="blocks.php?action=editmenu&id='.$rows_control['id'].'" title="'. $adinc->_lang("EDIT") .'" class="menuControlItem"><img src="icons/edit.png" border="0" /></a>';
								$html .= '<a onclick="if(window.confirm(\''. $adinc->_lang("ALERT_CONFIRM") .'\') == false){return false;}" title="'. $adinc->_lang("DELETE") .'" href="blocks.php?action=deletemenu&id='.$rows_control['id'].'" class="menuControlItem"><img src="icons/remove.png" border="0" /></a>';
								if($rows_control['active'] == 1){
									
									$html .= '<a href="#" onclick="activeMenu(\'disable\' , '.$rows_control['id'].')" title="'. $adinc->_lang("DISABLE") .'" class="menuControlItem" id="disable-menu-'.$rows_control['id'].'"><img src="icons/off.png" border="0" /></a>';	
								
								}else{

									$html .= '<a href="#" onclick="activeMenu(\'enable\' , '.$rows_control['id'].')" title="'. $adinc->_lang("ENABLE") .'" class="menuControlItem"  id="enable-menu-'.$rows_control['id'].'"><img src="icons/on.png" border="0" /></a>';	
									
								}
						$html .= '<a href="#" id="menuUp" class="menuControlItem" title="'. $adinc->_lang("MOVE_UP") .'"><img src="icons/up.png" /></a><a href="#" title="'. $adinc->_lang("MOVE_DOWN") .'" id="menuDown" class="menuControlItem"><img src="icons/down.png" /></a>
								</p>
								</div>' . "\n";
				}
				
				
			$html .= "</div>";
			
		
			$adinc->getMenu($adinc->_lang("CONTROL_MENUS_MANGE") , $html . "<div style=\"clear:both\"></div>");	
		}
	
		public function editMenu(){
		
			global $db , $adinc;
			
			$modules = $db->query("select * from " . PFX . "modules");

			$id = intval(abs($_REQUEST['id']));

			if(isset($_POST['editmenu'])){
				
					$title = $_POST['block_title'];
					
					$sort   = _safeInt($_POST['block_sort']);
					
					$active = _safeInt($_POST['block_active']);
					
					$type   = _safeInt($_POST['block_type']);
				
					$file_include = _safeString($_POST['file']);
					
					$code     =  $_POST['code'];
					
					$align    = _safeInt($_POST['align']);

					$display  = _safeInt($_POST['display']);
					
					$in_index = _safeInt($_POST['in_index']);

					$in_page  = _safeInt($_POST['in_page']);
				
					$allModules = _safeString($_POST['ALL_MODULES']);

					if($allModules && $allModules == "ALL"){ 
					
						$areas = "ALL";
						
					}else {
							
						$areas  =  $_POST['area'];
						if(!empty($areas)) $areas  =  implode("," , $areas);
						
					}
					
					if(!empty($title)){
						
							$update = $db->query("update `" . PFX . "blocks` set 
								title = '$title' , 
								sort='$sort',
								active='$active',
								type='$type',
								file='$file_include',
								code='$code',
								align='$align',
								display='$display' , 
								area='$areas' , 
								in_index='$in_index' ,
								in_page='$in_page'
								where id = $id");
						}
			
					if($update){
						
							$adinc->greenAlert($adinc->_lang("EDIT_DONE"));
							
							$adinc->location('blocks.php?action=control');
						
						}else{
						
							print mysql_error();	
						}
			}
			
			$query = $db->query("select * from " . PFX . "blocks where id = $id");
			
			$rows  = $db->rows($query);
			
			$form = '<script type="text/javascript">
						
							function viewcode(){
								
									$("#code").fadeIn("slow");
									
									$("#file_include").hide();
									
									var Empty = document.getElementById("select_file").value = "code";
							}
								
							function viewinclude(){
								
									$("#code").hide(); 
									
									$("#file_include").fadeIn("slow");
									
									var Empty = document.getElementById("text_code").value = "";
							}
								
						function access(){
							var title = $("#block_title").val();					
							title.replace(/ /gi , "");
							if(title == ""){
								$("#alert_title").html(\'<span class="red_alert">'. $adinc->_lang("ERROR_EMPTY_INPUT") . '</span>\').fadeIn("slow");
								return false;
							}	
						}
						</script>
						<form method="post">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="20%">'. $adinc->_lang("CONTROL_MENUS_TITLE") . '</td>
				<td><input type="text" id="block_title" value="'.$rows['title'].'" name="block_title" /> <span id="alert_title" style="display:none;"></span></td>
			  </tr>
			  <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_SORT") . '</td>
				<td><input type="text" name="block_sort" value="'.$rows['sort'].'"  /></td>
			  </tr>
			 <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_ALIGN") . '</td>
				<td>
				<select name="align" id="align">
				<option value="1"'; if($rows['align'] == 1){ $form .= 'selected="selceted"'; } $form .='>'. $adinc->_lang("CONTROL_MENUS_RIGHT") . '</option>
				<option value="2"'; if($rows['align'] == 2){ $form .= 'selected="selceted"'; } $form .='>'. $adinc->_lang("CONTROL_MENUS_CENTER") . '</option>
				<option value="3"'; if($rows['align'] == 3){ $form .= 'selected="selceted"'; } $form .='>'. $adinc->_lang("CONTROL_MENUS_LEFT") . '</option>
			
				</select>
				</td>
			  </tr>
			 <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_DISPLAY_IN_TEMPLATE") . '</td>
				<td>
				<select name="display" id="align">
				<option value="0"'; if($rows['display'] == 0){ $form .= 'selected="selceted"'; } $form .='>'. $adinc->_lang("YES") . '</option>
				<option value="1"'; if($rows['display'] == 1){ $form .= 'selected="selceted"'; } $form .='>'. $adinc->_lang("NO") . '</option>
			
				</select>
				</td>
			  </tr>
			  <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_STATUS") . '</td>
				<td>
					<div class="radio_box"> '. $adinc->_lang("ENABLED") . ' : <input type="radio"'; if($rows['active'] == 1){ $form .= 'checked'; } $form .=' value="1" name="block_active" /></div>
					<div class="radio_box"> '. $adinc->_lang("DISABLED") . ' : <input type="radio" value="0"'; if($rows['active'] == 0){ $form .= 'checked'; } $form .=' name="block_active" /></div>
				 </td>
			  </tr>
			   <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_IN_INDEX") . '</td>
				<td>
				<div class="radio_box">
				'. $adinc->_lang("YES") . ' : <input type="radio"'; if($rows['in_index'] == 1){ $form .= 'checked'; } $form .=' value="1" name="in_index" /> 
				</div>
				<div class="radio_box">
				 '. $adinc->_lang("NO") . ' : <input type="radio" value="0"'; if($rows['in_index'] == 0){ $form .= 'checked'; } $form .=' name="in_index" />
				 </div>
				 </td>
			  </tr>
			   <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_IN_PAGES") . '</td>
				<td>
				<div class="radio_box">
				'. $adinc->_lang("YES") . ' : <input type="radio"'; if($rows['in_page'] == 1){ $form .= 'checked'; } $form .=' value="1" name="in_page" /> 
				</div>
				<div class="radio_box">
				 '. $adinc->_lang("NO") . ' : <input type="radio" value="0"'; if($rows['in_page'] == 0){ $form .= 'checked'; } $form .=' name="in_page" />
				 </div>
				 </td>
			  </tr>
			 <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_IN_MODULES") . '</td>
				<td>';
				
					$form .= '<div> <input type="checkbox" name="ALL_MODULES" value="ALL" class="checkbox_id" '; 
					
						if($rows['area'] == "ALL"){  
						
							$form .= 'checked="checked"';  
							
						} 
						
					$form .= '/> <span class="module_name">'. $adinc->_lang("CONTROL_MENUS_ALL_MODULES") . '</span></div>';	
			
					while($rmodule = $db->rows($modules)){
					
						$form .= '<div> <input type="checkbox" name="area[]" value="'.$rmodule['module_id'].'"'; 
						
						if(in_array($rmodule['module_id'] , explode("," , $rows['area']) )){ $form .= 'checked="checked"'; }
						
						$form .= ' class="checkbox_id" /> <span class="module_name">'.$rmodule['module_name'].'</span></div>';	
					}
					
				$form .='</td>
			  </tr>
			   <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_TYPE") . '</td>
				<td>
					<div class="radio_box"> '. $adinc->_lang("CONTROL_MENUS_TYPE_CODE") . ': <input type="radio"'; if($rows['type'] == 2){ $form .= 'checked'; } $form .=' onChange="viewcode()" checked value="2" name="block_type" /></div>
					<div class="radio_box"> '. $adinc->_lang("CONTROL_MENUS_TYPE_FILE") . ' : <input type="radio"'; if($rows['type'] == 1){ $form .= 'checked'; } $form .=' onclick="viewinclude()"  value="1" name="block_type" /></div>
				</td>
			  </tr>
			  <tr>
				<td>'. $adinc->_lang("CONTROL_MENUS_CONTENT") . '</td>
				<td>
				<div id="code"'; if($rows['type'] == 1){ $form .= ' style="display:none;"'; } $form .='>
				<textarea name="code" id="text_code" rows="10">'.$rows['code'].'</textarea>
				</div>
				<div id="file_include"'; if($rows['type'] == 2){ $form .= ' style="display:none;"'; } $form .='>
				<select name="file" id="select_file">
				<option value="code">'. $adinc->_lang("NO_FILE_SELECTED") . '</option>';
				
					$blcks_opendir = opendir("../blocks");
					
					while($file = readdir($blcks_opendir)){
							
							$test = str_replace("." , "" , $file);
							
							if(strlen($test) > 1 && $file !== ".htaccess"){
								
								$form .= '<option value="'.$file.'"'; if($rows['file'] == $file){ $form .= 'selected="selected"'; } $form .='>' . $file . '</option>';		
						
							}
						}
						
				
				$form .= '
				</select>
				</div>
				</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="editmenu" onclick="return access();" value="'.$adinc->_lang("SAVE").'" /></td>
			  </tr>
			</table>

			</form>';
			
			$adinc->getMenu($adinc->_lang("CONTROL_MENUS_EDIT") . " ' " . $rows['title'] . " '" , $form);		
		}
		
		public function deleteMenu(){
			
			global $db , $adinc;
		
			$id = _safeInt($_REQUEST['id']);

			if($db->getTotalById(PFX . "blocks" , $id) == 0) die('false');
				
			$delete = $db->query("delete from " . PFX . "blocks where id = $id");
				
			if($delete){
					
				$adinc->greenAlert($adinc->_lang("DELETE_DONE"));
					
				$adinc->location('blocks.php?action=control');

			}	
		}
	}
?>