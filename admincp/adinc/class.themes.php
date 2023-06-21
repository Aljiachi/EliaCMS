<?php

	class ControlThemes{
	
		public function addTheme(){
		
			global $db , $adinc ;
			
			if(isset($_POST['addstyle'])){
					
				$title = _safeString($_POST['title']);
						
				$dir   = _safeString($_POST['dir']);
						
				$folder = _safeString($_POST['folder']);
						
				$insert = $db->query("insert into " . PFX . "styles values('' , '$title' , '$folder' , '$dir')");
						
				if($insert){
							
					$adinc->greenAlert($adinc->_lang("CONTROL_THEMES_ADD_DONE"));
								
					$adinc->location('themes.php?all');
				}
			
			}
			
			$form = '<script type="text/javascript">
					function access(){
						var title = $("#style_title").val();
						title.replace(/ /gi , "");
						if(title == ""){
								$("#alert_title").html(\'<span class="red_alert">'.$adinc->_lang("ERROR_EMPTY_INPUT").'</span>\').fadeIn("slow");
								return false;
							}	
		
						var folder = $("#dir").val();
						folder.replace(/ /gi , "");
						if(folder == ""){
								$("#alert_folder").html(\'<span class="red_alert">'.$adinc->_lang("ERROR_EMPTY_INPUT").'</span>\').fadeIn("slow");
								return false;
							}	
						}
				</script>';
				
			$form .= "<form method='post'>\n
				<table cellpadding='0' cellspacing='0' width='100%' border='0'>
				<tr>
				<td width='20%'>".$adinc->_lang("CONTROL_THEMES_TITLE")." : </td>
				<td class='td'><input type='text' id='style_title' name='title' />  <span id=\"alert_title\" style=\"display:none;\"></span></td>
				</tr>
				<tr>
				<td>".$adinc->_lang("CONTROL_THEMES_DIR")." : </td>
				<td class='td'>
				<select name='dir'>
				<option value='rtl'>".$adinc->_lang("CONTROL_THEMES_DIR_RTL")."</option>
				<option value='ltr'>".$adinc->_lang("CONTROL_THEMES_DIR_RTL")."</option>
				</select>
				</td>
				</tr>
				<tr>
				<td>".$adinc->_lang("CONTROL_THEMES_FOLDER")." : </td>
				<td class='td'><input type='text' name='folder' id='dir' />   <span id=\"alert_folder\" style=\"display:none;\"></span>
					<select onchange='document.getElementById(\"dir\").value = this.value'>
					<option value=''>".$adinc->_lang("CONTROL_FAST_MENU")."</option>";
					$blcks_opendir = opendir("../themes");
					
					while($file = readdir($blcks_opendir)){
							
							$test = str_replace("." , "" , $file);
							
							if(strlen($test) > 1 && $file !== ".htaccess"){
								
								$form .= '<option value="'.$file.'">' . $file . '</option>';		
						
							}
						}
								
				$form .= "</select>\n</td>\n</tr>\n
					  	  <tr>\n<td class='td'><input type='submit' onclick='return access()' value='".$adinc->_lang("SAVE")."' name='addstyle' /></td>\n</tr>
						  </table>";			
				
				$adinc->getMenu($adinc->_lang("CONTROL_THEMES_ADD") , $form);	
				
		} # end Add Theme Function
		
		public function Management(){
			
			global $db , $adinc;
				
			$getStyles = $db->query("select * from styles");
			
			$core      = $db->getfastRows("select style_id_default from " . PFX . "core");
						
			$table = '<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">';
			
			while($getStyle = $db->rows($getStyles)){
				
					$num++;
									
					if($num == 1){ $table .= '<tr>'; }
					
					$table .= "<td width=\"30%\" align=\"center\">";
					
					$table .= '<table width="250" align="center" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="200" style="';if($getStyle['id'] !== $core['style_id_default']){ $table .= 'background:#ccc;'; }else{$table .= 'background:#88bd5d;';} $table.='"><img src="../themes/'.$getStyle['style_folder'].'/screenshot.png" width="250" height="200" /></td>
				  </tr>
				  <tr>
					<td>
					<h2 style="margin:2px;font-family:Arial, Helvetica, sans-serif;">'.$getStyle['style_name'].'</h2>
					<div class="temp_links">';
					
					if($getStyle['id'] !== $core['style_id_default']){ $table.= ' <a href="themes.php?action=setdefault&id='. $getStyle['id'] .'">'.$adinc->_lang("ENABLE").'</a>';}
					
					$table.='<a href="themes.php?action=edit&id='. $getStyle['id'] .'">'.$adinc->_lang("EDIT").'</a>
					<a href="themes.php?action=editor&id='. $getStyle['id'] .'">'.$adinc->_lang("CONTROL_THEMES_EDIT_TEMPLATES").'</a>
					<a href="themes.php?action=remove&id='. $getStyle['id'] .'">'.$adinc->_lang("DELETE").'</a>
					</div>
					</td>
				  </tr>
				</table>';
									
				$table .= "</td>\n";
								
				if($num == 3){ 
				
					$table .= "</tr>\n";
				
					$num = 0; 	
				}
										
			}

			$table .= '</table>';

			$adinc->getMenu($adinc->_lang("CONTROL_THEMES_MANGE") , $table);	
		
		} # end Mange Function
		
		public function setDefault(){

			global $db , $adinc;
					
			$id = _safeInt($_REQUEST['id']);
			
			$setQuery = $db->query("select * from " . PFX . "styles where id = $id");
			
			$setRows  = $db->rows($setQuery);
						
			if($db->total($setQuery) == false){
				
					$adinc->redAlert($adinc->_lang("CONTROL_THEMES_NOT_EXISTS"));
					
					$adinc->location('themes.php?action=control');

				}else{
					
					$setRun = $db->query("update " . PFX . "core set style = '" . $setRows['style_folder'] . "' , style_id_default = $id");
						
					if($setRun){
							
						$adinc->greenAlert($adinc->_lang("CONTROL_THEMES_SET_DEFAULT_DONE"));
								
						$adinc->location('themes.php?action=control');
	
					}
	
				}
		
		} # end setDeafult Function.
		
		public function editTheme(){
			
			global $adinc , $db;
			
			$id = _safeInt($_REQUEST['id']);
			
			if(isset($_POST['edit_style'])){
				
					$title = strip_tags(trim($_POST['title']));
					
					$dir   = strip_tags(trim($_POST['dir']));
					
					$folder = strip_tags(trim($_POST['folder']));
					
					$update = $db->query("update " . PFX . "styles set style_name='$title' , style_folder='$folder' , style_dir='$dir' where id = $id");
					
					if($update){
						
							$adinc->greenAlert('تمت عملية التعديل بنجاح');
							
							$adinc->location('themes.php?action=control');
						}
				}
				
			$setQuery = $db->query("select * from " . PFX . "styles where id = $id");
			
			$setRows  = $db->rows($setQuery);
			
			if($db->total($setQuery) == false){
				
					$adinc->redAlert('الستايل المطلوب غير موجود');
					
					$adinc->location('themes.php?action=control');

				}else{
					
					
			$form = '<script type="text/javascript">
					function access(){
						var title = $("#style_title").val();
						title.replace(/ /gi , "");
						if(title == ""){
								$("#alert_title").html(\'<span class="red_alert">'.$adinc->_lang("ERROR_EMPTY_INPUT").'</span>\').fadeIn("slow");
								return false;
							}	
		
						var folder = $("#dir").val();
						folder.replace(/ /gi , "");
						if(folder == ""){
								$("#alert_folder").html(\'<span class="red_alert">'.$adinc->_lang("ERROR_EMPTY_INPUT").'</span>\').fadeIn("slow");
								return false;
							}	
						}
			</script>';
			$form .= "<form method='post'>\n
			<table cellpadding='0' width='100%' cellspacing='0' class='table' border='0'>
			<tr>
			<td width='20%'>".$adinc->_lang("CONTROL_THEMES_TITLE")." : </td>
			<td class='td'><input type='text' value='".$setRows['style_name']."' id='style_title' name='title' />  <span id=\"alert_title\" style=\"display:none;\"></span></td>
			</tr>
			<tr>
			<td>".$adinc->_lang("CONTROL_THEMES_DIR")." : </td>
			<td class='td'>
			<select name='dir'>
			<option value='rtl'"; if($setRows['style_dir'] == "rtl"){ $form .= " selected='selected' "; } $form .= ">".$adinc->_lang("CONTROL_THEMES_DIR_RTL")."</option>
			<option value='ltr'"; if($setRows['style_dir'] == "ltr"){ $form .= " selected='selected' "; } $form .= ">".$adinc->_lang("CONTROL_THEMES_DIR_LTR")."</option>
			</select>
			</td>
			</tr>
			<tr>
			<td>".$adinc->_lang("CONTROL_THEMES_FOLDER")." : </td>
			<td class='td'><input type='text' value='".$setRows['style_folder']."' name='folder' id='dir' />   <span id=\"alert_folder\" style=\"display:none;\"></span>
				<select onchange='document.getElementById(\"dir\").value = this.value'>
			    <option value=''>".$adinc->_lang("CONTROL_FAST_MENU")."</option>";
			
				$blcks_opendir = opendir("../themes");
				
				while($file = readdir($blcks_opendir)){
						
						$test = str_replace("." , "" , $file);
						
						if(strlen($test) > 1 && $file !== ".htaccess"){
							
							$form .= '<option value="'.$file.'">' . $file . '</option>';		
					
						}
					}
				
			$form .=   "</select>\n</td>\n</tr>\n
						<tr>\n<td class='td'><input type='submit' onclick='return access()' value='".$adinc->_lang("SAVE")."' name='edit_style' /></td>\n</tr>\n
						</table>";			
				
			$adinc->getMenu($adinc->_lang("CONTROL_THEMES_EDIT_THEME") . ' " ' . $setRows['style_name'] . ' " ' , $form);
			
			}
		
		} # end editTheme Function.
		
		public function deleteTheme(){
		
			global $adinc , $db;
			
			$id = _safeInt($_REQUEST['id']);

			$removeQuery = $db->query("select * from " . PFX . "styles where id = $id");
			
			$coreQuery = $db->query("select style_id_default from " . PFX . "core");
								
			$getCore = $db->rows($coreQuery);
				
			if($getCore['style_id_default'] == $id){
					
					$adinc->redAlert($adinc->_lang("CONTROL_THEMES_ERROR_THIS_DEFAULT"));
			}
					
			$delete = $db->query("delete from " . PFX . "styles where id = $id");
				
			if($delete){
					
				$adinc->greenAlert($adinc->_lang("DELETE_DONE"));
						
				$adinc->location('themes.php?action=control');
			
			}	
		
		} # end deleteTheme function.
		
		public function editor(){
		
			global $adinc , $db;
			
			$id   = _safeInt($_REQUEST['id']);
			
			$file = _safeString($_REQUEST['file']);
			
			$mod  = _safeString($_REQUEST['module']);
			
			if(empty($mod)){ 
			
				$stylesQuery = $db->query("select * from " . PFX . "styles");
				
				$coreQuery = $db->query("select style_id_default from " . PFX . "core");
									
				$getCore = $db->rows($coreQuery);
				
				if(empty($id)){ $id = $getCore['style_id_default']; }
	
				$setQuery = $db->query("select * from " . PFX . "styles where id = $id");
				
				$setRows  = $db->rows($setQuery);
					
			}
			
			if(isset($_POST['editor_post'])){
			
			if(eregi("\'" , $_POST['editor']) or eregi('\"' , $_POST['editor'])){
				
				$post = stripslashes($_POST['editor']);
				
			}else{

				$post = $_POST['editor'];
				
			}
			
				if(empty($mod)){
													
					if(empty($_REQUEST['file'])){ 
						
						$style_path = "../themes/" . $setRows['style_folder'] . "/style.css";
					
					 }else{

						$style_path = "../themes/" . $setRows['style_folder'] . "/templates/" . $file;
						 
					 }
					 
				}else{
					
					
					if(empty($_REQUEST['file'])){ 
						
						$style_path = "../" . modulesPath . "/" . $mod . "/style.css";
					
					 }else{

						$style_path = "../" . modulesPath . "/" . $mod . "/templates/" . $file;
						 
					 }
					 
				}
				
				if(empty($mod)){
				
					if($db->total($setQuery) == 0){

						$adinc->redAlert($adinc->_lang("CONTROL_THEMES_NOT_EXISTS"));
						$adinc->location('themes.php?action=control');

					}
				}
					 
				if(!file_exists($style_path)){
				
					$adinc->redAlert($adinc->_lang("CONTROL_THEMES_NOT_EXISTS"));
					$adinc->closePage();
					$adinc->stop();
						
				}
					 
				if(!is_writeable($style_path)){
					
						chmod($style_path , "0777");
					
					}
					
					$edit = @file_put_contents($style_path , $post);
					
					if($edit){

						$adinc->greenAlert($adinc->_lang("CONTROL_THEMES_EDITOR_DONE"));
					
					}
	
				}

				if(!empty($_REQUEST['file']) and strrchr($_REQUEST['file'] , '.') !== ".html"){
					
					$adinc->redAlert($adinc->_lang("CONTROL_THEMES_CHECK_TEMPLATE_TYPE"));
					$adinc->closePage();
					$adinc->stop();
					
				}
				
				if(empty($mod)){			
					 
						if(empty($_REQUEST['file'])){ 
													
							$tempPath = ("../themes/" . $setRows['style_folder'] . "/style.css");
						
						 }else{
	
							$tempPath = ("../themes/" . $setRows['style_folder'] . "/templates/" . $file);
							 
						 }
						 
					}else{
						
						if(empty($_REQUEST['file'])){ 
							
							$tempPath = ("../" . modulesPath . "/" . $mod . "/style.css");
						
						 }else{
	
							$tempPath = ("../" . modulesPath . "/" . $mod . "/templates/" . $file);
							 
						 }
						 
					}
										 										 
					if(!file_exists($tempPath) || 
						preg_match('/..\//', $_REQUEST['file']) || 
						preg_match('/.\//', $_REQUEST['file']) || 
						preg_match('/\//', $_REQUEST['file'])						
						){
					
						$adinc->redAlert($adinc->_lang("CONTROL_THEMES_CHECK_TEMPLATE_TYPE"));
						$adinc->closePage();
						$adinc->stop();
					
					}
					
					$content = @file_get_contents($tempPath);
					$content = htmlspecialchars($content);
					
					if(empty($mod)){ 
					
					 $form = '
					 <div align="left">
					 <span style="font-weight:bold">'. $adinc->_lang("CONTROL_THEMES_SELECT_THEME") .' : </span>
					 <select onchange="location = \'themes.php?action=editor&id=\'+this.value+\'&file='.$file.'\'">
					 ';
					 while($menu_styles = $db->rows($stylesQuery)){
					
						$form.= '<option value="'.$menu_styles['id'].'"'; if($menu_styles['id'] == $id){ $form.= ' selected="selected" ';} $form.='>'.$menu_styles['style_name'].'</option>';	 
					 }
					 
					 $form.='
					 </select>
					 </div>';
					 
						}
					
					 $form .= '
					 <div style="float:left; width:150px;" class="temps">
					 <ul>
					 ';
						
						
					if(empty($mod)){ 
						
						$styles_opendir = opendir("../themes/" . $setRows['style_folder'] . "/templates");

					}else{

						$styles_opendir = opendir("../" . modulesPath . "/" . $mod . "/templates");
							
					}
						
					$form .= '<li><a href="themes.php?action=editor&id='.$id.'&module='.$mod.'"'; if($_REQUEST['file'] == ""){ $form.= 'class="select"'; } $form.='>#0 . style.css</a></li>';		
		
					while($menu_file = readdir($styles_opendir)){
							
						$test = str_replace("." , "" , $menu_file);
				
						if(strlen($test) > 1 and strrchr($menu_file , '.') == ".html"){
							
							$num_list++;
							
							$form .= '<li><a href="themes.php?action=editor&id='.$id.'&module='.$mod.'&file='.$menu_file.'"'; if($menu_file == $file){ $form.= 'class="select"'; } $form.='>#' . $num_list . ' . ' . str_replace(".html" , "" , $menu_file) . '</a></li>';		
			
						}
					}
			
					$form.='
					</ul>
					</div>
					<div style="margin-left:152px;">
					 <form method="post">
					 <center>
					 	<textarea name="editor" dir="ltr" cols="120" rows="25" tabindex="1">'.$content.'</textarea>
					 </center>
					 <center>
						 <input type="submit" name="editor_post" value="'.$adinc->_lang("SAVE").'" />
					 </center>
					 </div>
					 </form>';
					 
					$adinc->getMenu($adinc->_lang("CONTROL_THEMES_EDITOR") , $form);	
		
		} # end editor function.
		
	}
?>