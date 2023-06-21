<?php

	class eliaModuleAdmin{
	
		public $extraLink;

		public function __construct(){
			
			global $adinc;
			
			$this->extraLink = array(
				$adinc->_lang("BLOG_ADD_TOPIC") 		=> "modules.php?action=admin&module=".ModuleID."&method=addTopic" ,
				$adinc->_lang("BLOG_MANGE_TOPICS") 		=> "modules.php?action=admin&module=".ModuleID."&method=mangeTopics" ,
				$adinc->_lang("BLOG_ADD_SECTION") 		=> "modules.php?action=admin&module=".ModuleID."&method=addSection" ,
				$adinc->_lang("BLOG_MANGE_SECTIONS") 		=> "modules.php?action=admin&module=".ModuleID."&method=mangeSections" ,
				$adinc->_lang("BLOG_MANGE_COMMENTS") 		=> "modules.php?action=admin&module=".ModuleID."&method=mangeComments" ,
				);
		}
		
		private function getLinksBar(){
			
			global $adinc;
			
			$adinc->getExtraLinks($this->extraLink);
				
		}
		
		public function main(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
				
		}
		
		public function addTopic(){
		
			global $adinc , $db;
			
			$this->getLinksBar();
			
			// Rec Request				
			$this->postAddTopic();
						
			$sections = $db->query("select * from blog_module_sections");
			
			$table = '<form method="post" enctype="multipart/form-data">
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
		  <tr>
			<td>'.$adinc->_lang("BLOG_TITLE").' : </td>
			<td><input type="text" name="title" id="input_1" /></td>
		  </tr>
		  <tr>
			<td>'.$adinc->_lang("BLOG_CAPTION").' :</td>
			<td><input type="text" name="more" style="width:80%;" id="input_2" /></td>
		  </tr>
		 <tr>
			<td>'.$adinc->_lang("BLOG_PHOTO").' :</td>
			<td><input type="file" name="photo" id="input_3" /></td>
		  </tr>
		  <tr>
			<td>'.$adinc->_lang("BLOG_DATE").' :</td>
			<td>
			'.$adinc->_lang("DATE_MONTH").' : <select name="date_m">';
			
			for($a=0;$a<=12;$a++){
			
				$table .= '<option value="'.$a.'">' . $adinc->_lang("DATE_M" . $a) . '</option>' . "\n";	
			}
			
			$table .= '</select>
			'.$adinc->_lang("DATE_DAY").'  : <input type="text" name="date_d" style="width:30px;" />
			'.$adinc->_lang("DATE_YEAR").'  : <input type="text" name="date_y" style="width:30px;" />
			'.$adinc->_lang("DATE_HOUR").'  : <input type="text" name="date_h" style="width:30px;" />
			'.$adinc->_lang("DATE_MINUTE").'  : <input type="text" name="date_i" style="width:30px;" />
			</td>
		  </tr>
		  <tr>
			<td>'.$adinc->_lang("BLOG_SECTION").' :</td>
			<td>
			<select name="cat_id">';
			
			while($rows = $db->rows($sections)){
			
				$table .= '<option value="'.$rows['id'].'"> . '.$rows['title'].'</option>';	
			}
			
			$table .='</select>
			</td>
		  </tr>
		  <tr>
			<td>'.$adinc->_lang("BLOG_CONTENT").'</td>
			<td><textarea name="text" id="text" cols="50" rows="5"></textarea></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="submit" value="'.$adinc->_lang("SAVE").'" onclick="addtopic();" /></td>
		  </tr>
		</table>
		</form>
		';
		
			$adinc->getMenu($adinc->_lang("BLOG_ADD_TOPIC") , $table);
			
		}
		
		public function postAddTopic(){
		
			global $adinc , $db;
				
			if(isset($_POST['submit'])){
					
					$title = _safeString($_POST['title']);
				
					$more  = _safeString($_POST['more']);
					
					if(empty($title) || empty($more)) {
			
						$adinc->redAlert($adinc->_lang('EMPTY_INPUTS'));
						$adinc->closepage();
						$adinc->stop();
					}
					
					$date_m = $_POST['date_m'];
					$date_y = $_POST['date_y'];
					$date_d = $_POST['date_d'];
					$date_h = $_POST['date_h'];
					$date_i = $_POST['date_i'];
		
					$date   = $date_m . "/" . $date_d . "/" . $date_y . "-" . $date_h . ":" . $date_i;
							
					$uploader = new Uploader;
					$uploader->DIR_TO = "../upload/blog/";
					$uploader->FILE_VER = "photo";
					$uploader->FILES_SIZE = 1296287;
					$uploader->TYPES = array('image/jpeg' , 'image/png' , 'image/gif' , 'image/jpg');
					$uploader->FILE_EXT = array('.jpg' , '.png' , '.PNG' , '.JPG' , '.GIf' , '.gif' , '.jpeg' , '.JPEG');
					
					$uploader->getFileInformation();
							
					if(!$uploader->checkFileContent() || !$uploader->checkFileSize() || !$uploader->checkFileType() ){
					
						$adinc->redAlert($adinc->_lang('CHECK_PHOTO'));
						$adinc->closepage();
						$adinc->stop();
												
					}

					if($uploader->upload() ){}else{print 'error In Upload ' . $uploader->file_error;}
					
					$photo_name= $uploader->file_hash . $uploader->file_name;
					
					$all = $_POST['text'];
			
					$cat_id  = intval(abs($_POST['cat_id']));
					
					$insert = $db->query("insert into blog_module_topics values ('' , '$title' , '$more' , '$all' , '0' , '$cat_id' , '$date' , '$photo_name')");
					
					if($insert){
						
							$adinc->greenAlert($adinc->_lang('BLOG_TOPIC_ADDED'));
						}
		
				}
			
		}
		
		public function mangeTopics(){
		
			global $adinc , $db;
					
			print '<div id="loadArea" style="display:none;"></div>';
				
			$objectsManager = new objectsManager;
				
			$page = $objectsManager->getPaginatorObject();
			
			$page->SetPerPage(20);
			
			$Topics = $page->CreatePaginator("select * from blog_module_topics order by id desc");
			
			$table = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
			
			$table .= '<tr>
				<td class="td_title">#</td>
				<td class="td_title">{R.BLOG_TITLE}</td>
				<td class="td_title">{R.COMMENTS_TOTAL}</td>
				<td class="td_title">{R.EDIT}</td>
				<td class="td_title">{R.DELETE}/td>
				</tr>
				';		
		
			while($rows = $db->rows($Topics)){
			
				$posts = $db->query("select id from blog_module_comments where topic_id =" . $rows['id']);
				
				$posts = $db->total($posts);
				
				$table .= '
				<tr id="topic-'.$rows['id'].'">
				<td class="td_row">'.$rows['id'].'</td>
				<td class="td_row">'.$rows['title'].'</td>
				<td class="td_row">'.$posts.'</td>
				<td class="td_row"><a href="modules.php?action=admin&module='.ModuleID.'&method=editTopic&id='.$rows['id'].'"><img src="icons/edit.png" /></a></td>
				<td class="td_row"><a onclick="if(window.confirm(\''.$adinc->_lang("ALERT_CONFIRM").'\') == false){return false;}" href="javascript:deleteTopic('.$rows['id'].')"><img src="icons/remove.png" /></a></td>
				</tr>
				';
			}
			
			$table .= '</table>';
			$table .= '<script type="text/javascript">';
			$table .= 'function deleteTopic(id){';
			$table .= '$("#topic-" + id).fadeOut(\'slow\');';
			$table .= '$("#loadArea").load("fast-modules.php?action=admin&module='.ModuleID.'&method=deleteTopic&id=" + id);';
			$table .= '}</script>';
			
			$adinc->getMenu($adinc->_lang("BLOG_MANGE_TOPICS") , $table);
				
		}
		
		public function deleteTopic(){
		
			global $db;
			
			$id 	 = _SafeInt($_REQUEST['id']);
		
			$delete = $db->query("delete from blog_module_topics where id = $id"); 
			
			if($delete){ print mysql_error(); }
		
		}
		
		public function deleteSection(){
		
			global $db;
					
			$id 	 = _SafeInt($_REQUEST['id']);
		
			$delete = $db->query("delete from blog_module_sections where id = $id"); 
			
			if($delete){ print mysql_error(); }
		
		}
	
		public function deleteComment(){
		
			global $db;
					
			$id 	 = _SafeInt($_REQUEST['id']);
		
			$delete = $db->query("delete from blog_module_comments where id = $id"); 
			
			if($delete){ print mysql_error(); }
				
		}

		public function addSection(){
		
			global $adinc;
			
			// Links Bar		
			$this->getLinksBar();
			
			// Rec Request
			$this->postAddSection();
			
			$table = '<form method="post">
					 <table width="100%" border="0" cellspacing="0" cellpadding="5">
		  			 <tr>
					 <td width="20%">'.$adinc->_lang("BLOG_SECTION_NAME").'</td>
					 <td><input type="text" name="section_name" id="section_name" /></td>
		  			 </tr>
		 	 		 <tr>
					 <td>&nbsp;</td>
					 <td><input type="submit" value="'.$adinc->_lang("SAVE").'" name="submit" onclick="if(empty($(\'#section_name\').val()) == \'\'){ alert(\''.$adinc->_lang("INPUTS_EMPTY").'\'); return false; }" /></td>
		  			 </tr></table></form>';

			$adinc->getMenu($adinc->_lang("BLOG_ADD_SECTION") , $table);
		
		}
		
		public function postAddSection(){
		
			global $db , $adinc;
					
			if(isset($_POST['submit']) and $_POST['submit'] !== "submited"){
				
					$section = _SafeString($_POST['section_name']);
					
					$insert = $db->query("insert into blog_module_sections values ('' , '$section')");
			
					if($insert){
						
							$adinc->greenAlert($adinc->_lang("SAVE_DONE"));
						
						}
				}
			
		}
		
		public function editComment(){
		
			global $db , $adinc;
					
			$this->getLinksBar();
			
			$id = _SafeInt($_REQUEST['id']);
			
			$rows = $db->rows($db->query("select * from blog_module_comments where id = $id"));
			
			$table = '<table class="editor" dir="rtl" width="500" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td>'.$adinc->_lang("COMMENT_NAME").'</td>
						<td><input type="text" id="post_name" value="'.$rows['name'].'" /></td>
					  </tr>
					  <tr>
						<td>'.$adinc->_lang("COMMENT_MESSAGE").'</td>
						<td><textarea id="post" cols="30" rows="4">'.$rows['comment'].'</textarea></td>
					  </tr>
					  <tr>
						<td></td>
						<td><input type="button" onclick="postEdit('.$id.')" value="'.$adinc->_lang("SAVE").'" /> <input type="button" onclick="close_box()" value="'.$adinc->_lang("CLOSE").'" /></td>
					  </tr>
					</table>
					</div>';
				
				print $table;
		
		}
		
		public function mangeComments(){
		
			global $adinc , $db;
			
			$this->getLinksBar();
			
			$html  = '<script type="text/javascript">';
			
			$html .= 'function deleteComment(id){';
			$html .= '$("#topic-" + id).fadeOut(\'slow\');';
			$html .= '$("#loadArea").load("fast-modules.php?action=admin&module='.ModuleID.'&page=deletepost&id=" + id);';	
			$html .= '}';
		
			$html .= 'function activeComment(id){';
			$html .= '$("#topic-" + id + " td").fadeOut(\'slow\');';
			$html .= '$("#loadArea").load("fast-modules.php?action=admin&module='.ModuleID.'&method=activePost&active=1&id=" + id);';
			$html .= '}';
			
			$html .= 'function unActiveComment(id){';
			$html .= '$("#topic-" + id + " td").fadeOut(\'slow\');';
			$html .= '$("#loadArea").load("fast-modules.php?action=admin&module='.ModuleID.'&method=activePost&active=0&id=" + id);';
			$html .= '}';
			
			$html .= 'function deleteComment(id){';
			$html .= '$("#topic-" + id + " td").fadeOut(\'slow\');';
			$html .= '$("#loadArea").load("fast-modules.php?action=admin&module='.ModuleID.'&page=deletecomment&id=" + id);';
			$html .= '}';
	
			$html .= 'function updateComment(id){';
			$html .= '$("#TB_overlay").fadeIn(\'slow\');';
			$html .= '$("#TB_window").show().html(\'Loading...\');';
			$html .= '$("#TB_window").load("fast-modules.php?action=admin&module='.ModuleID.'&method=editComment&id=" + id);';
			$html .= '}';
	
			$html .= 'function close_box(){';
			$html  .= '$("#TB_overlay").fadeOut(\'slow\');';
			$html .= '$("#TB_window").hide().html();';
			$html .= '}';
	
			$html .= 'function postEdit(id){';	
			$html  .= 'var name = $("#post_name").val();';
			$html  .= 'var post = $("#post").val();';
			$html .= '$.post("fast-modules.php?action=admin&module='.ModuleID.'&page=post_edit_comment&id=" + id , {';
			$html .= '"sender" : 1 ,"name" : name , "comment" : post';
			$html .= '} , function(){';
			$html .= '$("#writer-" + id).html(name);';
			$html .= 'var new_post = post.replace(/\n/gi , "<br />");';
			$html .= '$("#comment-" + id).html(new_post);';
			$html .= 'close_box();';
			$html .= '});}';
	
			$html .= '</script>';
			
			$html .= '<div id="loadArea" style="display:none;"></div>';
			$html .= '<div id="TB_overlay" class="TB_overlayBG" ></div><div id="TB_window"></div>';
			
			$objectsManager = new objectsManager;
				
			$page = $objectsManager->getPaginatorObject();	
			
			$page->SetPerPage(15);
			
			$active = _SafeInt($_REQUEST['active']);
			
			$page->SetPageUrl("modules.php?action=admin&module=".ModuleID."&method=mangeComments&active=" . $active);
			
			$Topics = $page->CreatePaginator("select * from blog_module_comments where active = $active order by id desc");
			
			$total_actived = $db->total($db->query("select * from blog_module_comments where active = 1"));
			$total_unactived = $db->total($db->query("select * from blog_module_comments where active = 0"));
		
			$html .= '<a href="modules.php?action=admin&module='.ModuleID.'&method=mangeComments&active=1">'.$adinc->_lang("COMMENTS_SHOWN").' ('.$total_actived.')</a> | ';	
			$html .= '<a href="modules.php?action=admin&module='.ModuleID.'&method=mangeComments&active=0">'.$adinc->_lang("COMMENTS_UNSHOWN").' ('.$total_unactived.')</a>';	
			
			$table = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
			
				$table .= '
				<tr>
				<td class="td_title">#</td>
				<td class="td_title">{R.WRITER}</td>
				<td class="td_title" width="70%">{R.COMMENTS_POST}</td>
				</tr>
				';		
		
			while($rows = $db->rows($Topics)){
			
				$posts = $db->query("select id from blog_module_topics where cat_id =" . $rows['id']);
				
				$posts = $db->total($posts);
				
				$table .= '
				<tr id="topic-'.$rows['id'].'">
				<td class="td_row">'.$rows['id'].'</td>
				<td class="td_row">
					<div>
					<div><img src="icons/admin.png" /> <span id="writer-'.$rows['id'].'">'.$rows['name'].'</span></div>
					<img src="icons/ip.png" /> '.$rows['ip'].'
					</div>
					</td>
				<td class="td_row">
					<div id="comment-'.$rows['id'].'">'.str_replace("\n" , "<br />" , $rows['comment']).'</div>
					<div class="blog_adminbar">
					';
					if($rows['active'] == 0){
						
						$table .='<a href="javascript:activeComment('.$rows['id'].')">{R.COMMENTS_ACTION_SHOW}</a>';
				
					}else{
		
						$table .='<a href="javascript:unActiveComment('.$rows['id'].')">{R.COMMENTS_ACTION_HIDE}</a>';
						
					}
					
					$table .=' 
					| <a href="javascript:updateComment('.$rows['id'].')">{R.EDIT}</a>
					| <a onclick="if(window.confirm(\''.$adinc->_lang("ALERT_CONFIRM").'\') == false){return false;}" href="javascript:deleteComment('.$rows['id'].')">حذف</a>
					
					</div></td>
				</tr>
				';
			}
			
			$table .= '</table>';
		
			$html .= $table;
			
			echo $page->GetPagination();
			
			$adinc->getMenu($adinc->_lang("BLOG_MANGE_COMMENTS") , $html . $page->GetPagination() );
			
	
		}
		
		public function activePost(){
		
			global $db;
			
			$id 	 = _SafeInt($_REQUEST['id']);
		
			$active  =  _SafeInt($_REQUEST['active']);
			
			$delete  = $db->query("update blog_module_comments set active = $active where id = $id"); 
			
			if($delete){ print mysql_error(); }
				
		}
		
		public function mangeSections(){
		
			global $db , $adinc;
						
			print '<div id="loadArea" style="display:none;"></div>';
			
			echo '<script type="text/javascript">';
			echo 'function deleteSection(id){';
			echo '$("#topic-" + id).fadeOut(\'slow\');';
			echo '$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=deletesection&id=" + id);';
			echo '}';
			echo '</script>';

			$this->getLinksBar();
						
			$objectsManager = new objectsManager;
				
			$page = $objectsManager->getPaginatorObject();
			
			$page->SetPerPage(20);
			
			$Topics = $page->CreatePaginator("select * from blog_module_sections order by id desc");
			
			$table = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
			
			$table .= '
				<tr>
				<td class="td_title">#</td>
				<td class="td_title">'.$adinc->_lang("BLOG_SECTION_NAME").'</td>
				<td class="td_title">'.$adinc->_lang("BLOG_TOTAL_POSTS").'</td>
				<td class="td_title">'.$adinc->_lang("EDIT").'</td>
				<td class="td_title">'.$adinc->_lang("DELETE").'</td>
				</tr>
				';		
		
		
			while($rows = $db->rows($Topics)){
			
				$posts = $db->query("select id from blog_module_topics where cat_id =" . $rows['id']);
				
				$posts = $db->total($posts);
				
			$table .= '
				<tr id="topic-'.$rows['id'].'">
				<td class="td_row">'.$rows['id'].'</td>
				<td class="td_row">'.$rows['title'].'</td>
				<td class="td_row">'.$posts.'</td>
				<td class="td_row"><a href="modules.php?action=admin&module='.ModuleID.'&page=updatesection&id='.$rows['id'].'"><img src="icons/edit.png" /></a></td>
				<td class="td_row"><a onclick="if(window.confirm(\''.$adinc->_lang("ALERT_CONFIRM").'\') == false){return false;}" href="javascript:deleteSection('.$rows['id'].')"><img src="icons/remove.png" /></a></td>
				</tr>
				';
			
			}
			
			$table .= '</table>';
			
			$adinc->getMenu($adinc->_lang("BLOG_MANGE_SECTIONS") , $table);
		
		}
		
	}
	
	
?>