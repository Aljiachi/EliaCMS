<?php

	class ControlPages{
	
		public function addPage(){
		
			global $db , $adinc;
			
			if(isset($_POST['addpage'])){
				
					$title = strip_tags($_POST['page_title']);
					
					$meta  = strip_tags($_POST['page_meta']);
					
					$active = intval(abs($_POST['page_active']));

					$content   =  $_POST['page_content'];
										
					$insert = $db->query("insert into " . PFX . "pages values('' , '$title' , '$meta' , '0' , '$content' , '$active')");
				
					if($insert){
						
							$adinc->greenAlert($adinc->_lang("CONTROL_PAGES_ADD_DONE"));
							
							$adinc->location('pages.php?action=control');
							
						}
				}
				
			
			$form = '<script type="text/javascript">
						function access(){		
							var title = $("#block_title").val();
							title.replace(/ /gi , "");
							if(title == ""){
								$("#alert_title").html(\'<span class="red_alert">'.$adinc->_lang("ERROR_EMPTY_INPUT").'</span>\').fadeIn("slow");
								return false;
							}	
						}
					</script>
            <form method="post">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="20%">'.$adinc->_lang("CONTROL_PAGES_TITLE").'</td>
				<td><input type="text" id="block_title" name="page_title" /> <span id="alert_title" style="display:none;"></span></td>
			  </tr>
			  <tr>
				<td>'.$adinc->_lang("CONTROL_PAGES_KEYWORDS").' (keyword,keyword...)</td>
				<td><input type="text" name="page_meta" /></td>
			  </tr>
			  <tr>
				<td>'.$adinc->_lang("CONTROL_PAGES_STATUS").'</td>
				<td>
				<div class="radio_box">
				'.$adinc->_lang("ENABLE").' : <input type="radio" checked value="1" name="block_active" /> 
				</div>
				<div class="radio_box">
				 '.$adinc->_lang("DISABLE").' : <input type="radio" value="0" name="block_active" />
				 </div>
				 </td>
			  </tr>
				<tr>
				<td>'.$adinc->_lang("CONTROL_PAGES_CONTENT").'</td>
				<td><textarea name="page_content" cols="40" rows="5"></textarea></td>
				</tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="addpage" onclick="return access();" value="'.$adinc->_lang("SAVE").'" /></td>
			  </tr>
			</table>
						</form>';
			
			$adinc->getMenu($adinc->_lang("CONTROL_PAGES_ADD") , $form);	
				
		}
		
		public function Management(){
		
			global $db , $adinc;
					
			$control = $db->query("select * from " . PFX . "pages");
			
			$table   = '<table class="table" width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="td_title">#</td>
				<td class="td_title">'.$adinc->_lang("CONTROL_PAGES_TITLE").'</td>
				<td class="td_title">'.$adinc->_lang("CONTROL_PAGES_STATUS").'</td>
				<td class="td_title">'.$adinc->_lang("EDIT").'</td>
				<td class="td_title">'.$adinc->_lang("DELETE").'</td>
			  </tr>';
						
				while($rows_control = $db->rows($control)){
							
					$table .= '<tr>
					<td class="td_row"><span class="id_cont">'.$rows_control['id'].'</span></td>
					<td class="td_row">'.$rows_control['page_title'].'</td>';
					
					if($rows_control['page_actived'] == 0){
							
						$table .= '<td class="td_row">'.$adinc->_lang("ENABLED").'</td>';
					
					}else{
				
						$table .= '<td class="td_row_red">'.$adinc->_lang("DISABLED").'</td>';
						
					}
						
					$table .='<td class="td_row"><a href="pages.php?action=editpage&id='.$rows_control['id'].'"><img src="icons/edit.png" border="0" /></a></td>
							  <td class="td_row"><a onclick="if(window.confirm(\''.$adinc->_lang("ALERT_CONFIRM").'\') == false){return false;}" href="pages.php?action=deletepage&id='.$rows_control['id'].'"><img src="icons/remove.png" /></a></td>
							  </tr>';
				}
						
			$table .= '</table>';
		
			$adinc->getMenu($adinc->_lang("CONTROL_PAGES_MANGE") , $table);	
		}
	
		public function deletePage(){
		
			global $db , $adinc;
			
			$id = _safeInt($_REQUEST['id']);

			if($db->getTotalById(PFX . "pages" , $id) == 0){ die('false');}
				
			$delete = $db->query("delete from " . PFX . "pages where id = $id");
				
			if($delete){
					
				$adinc->greenAlert($adinc->_lang("DELETE_DONE"));
					
				$adinc->location('pages.php?action=control');
			
			}
		
		} # end deletePage function.
		
		
		public function editPage(){
		
			global $db , $adinc;
			
			$id = _safeInt($_REQUEST['id']);

			if(isset($_POST['addpage'])){
				
					$title = strip_tags($_POST['page_title']);
					
					$meta  = strip_tags($_POST['page_meta']);
					
					$active = intval(abs($_POST['page_active']));

					$content   =  $_POST['page_content'];
										
					$update = $db->query("update " . PFX . "pages set 
					page_title='$title',
					page_meta='$meta',
					page_actived='$active',
					page_content='$content'
					where id = $id");
				
					if($update){
						
						$adinc->greenAlert($adinc->_lang("EDIT_DONE"));
							
						$adinc->location('pages.php?action=control');
							
					}
			}	
				
			$editpage = $db->getfastRows("select * from " . PFX . "pages where id = $id");
			
			$form = '<script type="text/javascript">
						function access(){		
							var title = $("#block_title").val();
							title.replace(/ /gi , "");
							if(title == ""){
								$("#alert_title").html(\'<span class="red_alert">'.$adinc->_lang("ERROR_EMPTY_INPUT").'</span>\').fadeIn("slow");
								return false;
							}	
						}
					</script>
            <form method="post">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="20%">'.$adinc->_lang("CONTROL_PAGES_TITLE").'</td>
				<td><input type="text" id="block_title" value="'.$editpage['page_title'].'" name="page_title" /> <span id="alert_title" style="display:none;"></span></td>
			  </tr>
			  <tr>
				<td>'.$adinc->_lang("CONTROL_PAGES_KEYWORDS").' (keyword,keyword...)</td>
				<td><input type="text" value="'.$editpage['page_meta'].'" name="page_meta" /></td>
			  </tr>
			
			  <tr>
				<td>'.$adinc->_lang("CONTROL_PAGES_STATUS").'</td>
				<td>
				<div class="radio_box">
				'.$adinc->_lang("ENABLED").' : <input type="radio"'; if($editpage['page_actived'] == 0){ $form.= ' checked="checked" '; }$form .=' value="0" name="page_active" /> 
				</div>
				<div class="radio_box">
				 '.$adinc->_lang("DISABLED").' : <input type="radio" '; if($editpage['page_actived'] == 1){ $form.= ' checked="checked" '; }$form .=' value="1" name="page_active" />
				 </div>
				 </td>
			  </tr>
				<tr>
				<td>'.$adinc->_lang("CONTROL_PAGES_CONTENT").'</td>
				<td><textarea name="page_content" cols="40" rows="5">'.$editpage['page_content'].'</textarea></td>
				</tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="addpage" onclick="return access();" value="'.$adinc->_lang("SAVE").'" /></td>
			  </tr>
			</table>
			</form>';
			
			$adinc->getMenu($adinc->_lang("CONTROL_PAGES_EDIT") . " '".$editpage['page_title']."'" , $form);		
		}
		
		
	}
?>