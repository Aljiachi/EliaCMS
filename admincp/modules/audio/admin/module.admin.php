<?php

	class eliaModuleAdmin{
	
		public $extraLink;

		public function __construct(){
			
			$this->extraLink = array(
				' إضافة ملف صوتي ' 		=> 'modules.php?action=admin&module='.ModuleID.'&method=upload' , 
				' التحكم بالتصانيف ' 	=> 'modules.php?action=admin&module='.ModuleID.'&method=mangeSections' , 
				' إضافة تصنيف ' 		=> 'modules.php?action=admin&module='.ModuleID.'&method=addSection' , 
				' التحكم بالملفات الصوتية ' 	=> 'modules.php?action=admin&module='.ModuleID.'&method=mangeFiles' , 
				' التحكم بالتعليقات  ' 	=> 'modules.php?action=admin&module='.ModuleID.'&method=mangeComments');
			
		}
	
		public function main(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
				
		}
		
		public function upload(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
						
			if(isset($_POST['submit'])){
						
						$title = strip_tags($_POST['title']);
					
						$more  = strip_tags($_POST['more']);
						
						$uploaderMp3 = new Uploader;
						$uploaderMp3->DIR_TO = "../upload/audio/files/";
						$uploaderMp3->FILE_VER = "file";
						$uploaderMp3->FILES_SIZE = 1296287*60;
						$uploaderMp3->TYPES = array('audio/mp3' , 'audio/wav');
						$uploaderMp3->FILE_EXT = array('.mp3' , '.wav');
						
						$uploaderMp3->getFileInformation();
								
						if(!$uploaderMp3->checkFileSize() ){ $adinc->redAlert('حجم الملف كبير جداً'); $adinc->closePage();  exit;}
						if(!$uploaderMp3->checkFileType() ){ $adinc->redAlert('نوع الملف غير صحيح'); $adinc->closePage(); exit;}
							
						if($uploaderMp3->upload() ){}else{print 'error In Upload ' . $uploaderMp3->file_error;}
						
						$file_name = $uploaderMp3->file_hash . $uploaderMp3->file_name;
						
						$all = $_POST['text'];
				
						$cat_id  = intval(abs($_POST['cat_id']));
						
						$date = time();
						
						$insert = $db->query("insert into audio_module_files values ('' , '$title' , '$more' , '' , '$file_name' , '0' , '0' , '$date' , '$cat_id')");
						
						if($insert){
							
								$adinc->greenAlert('تم إضافة التدوينة بنجاح');
							}
					}
					
					
					
				$sections = $db->query("select * from audio_module_sections");
				
				$table = '<form method="post" enctype="multipart/form-data">
				<table width="100%" border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td>عنوان التدوينة : </td>
				<td><input type="text" name="title" id="input_1" /></td>
			  </tr>
			  <tr>
				<td>مختصر عن الملف :</td>
				<td><input type="text" name="more" style="width:80%;" id="input_2" /></td>
			  </tr>
			 <tr>
				<td>الملف الصوتي :</td>
				<td><input type="file" name="file" id="input_4" /></td>
			  </tr>
			  <tr>
				<td>التصنيف :</td>
				<td>
				<select name="cat_id">
				';
				
				while($rows = $db->rows($sections)){
				
					$table .= '<option value="'.$rows['id'].'"> . '.$rows['title'].'</option>';	
				}
				
				$table .='</select>
					</td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="submit" value="إضافة" onclick="addtopic();" /></td>
				  </tr>
				</table>
				</form>';

			$adinc->getMenu("إضافة ملف صوتي" , $table);
		
		}

		public function addSection(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
				
			if(isset($_POST['submit']) and $_POST['submit'] !== "submited"){
					
						$section = strip_tags($_POST['section_name']);
						
						$uploader = new Uploader;
						$uploader->DIR_TO = "../upload/audio/sections/";
						$uploader->FILE_VER = "photo";
						$uploader->FILES_SIZE = 1296287;
						$uploader->TYPES = array('image/jpeg' , 'image/png' , 'image/gif' , 'image/jpg');
						$uploader->FILE_EXT = array('.jpg' , '.png' , '.PNG' , '.JPG' , '.GIf' , '.gif' , '.jpeg' , '.JPEG');
						
						$uploader->getFileInformation();
								
						if(!$uploader->checkFileContent()){print 'error file content'; exit;}
						if(!$uploader->checkFileSize() ){ print 'error file size'; exit;}
						if(!$uploader->checkFileType() ){ print 'error file type'; exit;}
							
						if($uploader->upload() ){}else{print 'error In Upload ' . $uploader->file_error;}
						
						$photo_name= $uploader->file_hash . $uploader->file_name;
						
						$insert = $db->query("insert into audio_module_sections values ('' , '$section' , '$photo_name' , '0')");
				
						if($insert){
							
								$adinc->greenAlert("تم إضافة التصنيف بنجاح");
							
							}
					}
				
				$table = '<form method="post" enctype="multipart/form-data">
					<table width="100%" border="0" cellspacing="0" cellpadding="5">
					  <tr>
						<td width="20%">إسم التصنيف</td>
						<td><input type="text" name="section_name" id="section_name" /></td>
					  </tr>
					  <tr>
						<td>صورة التصنيف :</td>
						<td><input type="file" name="photo" id="input_3" /></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="إضافة" name="submit" onclick="if(empty($(\'#section_name\').val()) == \'\'){ alert(\'تحقق من الحقل\'); return false; }" /></td>
					  </tr>
					</table>
					</form>';
			
				$adinc->getMenu('إضافة تصنيف' , $table);
					
		}
		
		public function mangeSections(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
			
			echo '<script type="text/javascript">
					function deleteSection(id){
						$("#topic-" + id).fadeOut();
						$("#loadArea").load("fast-modules.php?action=admin&module=' . ModuleID . '&method=deleteSection&id=" + id);	
					}
				</script>';	
						
			print '<div id="loadArea" style="display:none;"></div>';
						
			$objectsManager = new objectsManager;
				
			$page = $objectsManager->getPaginatorObject();
			
			$page->SetPerPage(20);
			
			$Topics = $page->CreatePaginator("select * from audio_module_sections order by id desc");
			
			$table = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
			
			$table .= '<tr>
				<td class="td_title">#</td>
				<td class="td_title">عنوان التصنيف</td>
				<td class="td_title">عدد الملفات</td>
				<td class="td_title">تعديل</td>
				<td class="td_title">حذف</td>
				</tr>';		
		
			while($rows = $db->rows($Topics)){
			
				$posts = $db->query("select id from blog_module_topics where cat_id =" . $rows['id']);
				
				$posts = $db->total($posts);
				
				$table .= '<tr id="topic-'.$rows['id'].'">
					<td class="td_row">'.$rows['id'].'</td>
					<td class="td_row">'.$rows['title'].'</td>
					<td class="td_row">'.$rows['files'].'</td>
					<td class="td_row"><a href="modules.php?action=admin&module='.ModuleID.'&method=editSection&id='.$rows['id'].'"><img src="icons/edit.png" /></a></td>
					<td class="td_row"><a onclick="if(window.confirm(\'هل أنت متأكد العملية\') == false){return false;}" href="javascript:deleteSection('.$rows['id'].')"><img src="icons/remove.png" /></a></td>
					</tr>';
			}
			
			$table .= '</table>';
			
			$adinc->getMenu('برنامج المدونة - التحكم بالتصنيفات' , $table);
				
		}
	
		public function mangeFiles(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
			
			echo '<script type="text/javascript">
					function deleteFile(id){
						$("#topic-" + id).fadeOut();
						$("#loadArea").load("fast-modules.php?action=admin&module=' . ModuleID. '&method=deleteFile&id=" + id);	
					}
				</script>';	
						
			
			print '<div id="loadArea" style="display:none;"></div>';
						
			$objectsManager = new objectsManager;
				
			$page = $objectsManager->getPaginatorObject();
			
			$page->SetPerPage(20);
			
			$Files = $page->CreatePaginator("select * from audio_module_files order by id desc");
			
			$table = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
			
			$table .= '<tr>
				<td class="td_title">#</td>
				<td class="td_title">عنوان الملف</td>
				<td class="td_title">التعليقات</td>
				<td class="td_title">تعديل</td>
				<td class="td_title">حذف</td>
				</tr>
				';		
		
		
			while($rows = $db->rows($Files)){
			
				$posts = $db->query("select id from audio_module_comments where topic_id =" . $rows['id']);
				
				$posts = $db->total($posts);
				
				$table .= '<tr id="topic-'.$rows['id'].'">
				<td class="td_row">'.$rows['id'].'</td>
				<td class="td_row">'.$rows['title'].'</td>
				<td class="td_row">'.$posts.'</td>
				<td class="td_row"><a href="modules.php?action=admin&module='.ModuleID.'&method=editFile&id='.$rows['id'].'"><img src="icons/edit.png" /></a></td>
				<td class="td_row"><a onclick="if(window.confirm(\'هل أنت متأكد العملية\') == false){return false;}" href="javascript:deleteFile('.$rows['id'].')"><img src="icons/remove.png" /></a></td>
				</tr>
				';
			}
			
			$table .= '</table>';
			
			$adinc->getMenu('المكتبة الصوتية - التحكم بالملفات' , $table);
	
		}
		
		public function editFile(){
			
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
			
			$id = intval(abs($_REQUEST['id']));
		
			if(!is_numeric($id) || $id == 0){exit;}
					
			$query = $db->query("select * from audio_module_files where id = $id");
			
			$edit  = $db->rows($query);
			
			if($db->total($query) == 0){exit;}
				
			$objectsManager = new objectsManager;
		
			$files = $objectsManager->getFilesObject();
			
			if(isset($_POST['submit'])){
					
					$title = strip_tags($_POST['title']);
				
					$more  = strip_tags($_POST['more']);
					
					if(isset($_FILES['file']) and !empty($_FILES['file']['name'])){
							
						$uploaderMp3 = new Uploader;
						$uploaderMp3->DIR_TO = "../upload/audio/files/";
						$uploaderMp3->FILE_VER = "file";
						$uploaderMp3->FILES_SIZE = 1296287*60;
						$uploaderMp3->TYPES = array('audio/mp3' , 'audio/wav');
						$uploaderMp3->FILE_EXT = array('.mp3' , '.wav');
						
						$uploaderMp3->getFileInformation();
								
						if(!$uploaderMp3->checkFileSize() ){ $adinc->redAlert('حجم الملف كبير جداً'); $adinc->closePage();  exit;}
						if(!$uploaderMp3->checkFileType() ){ $adinc->redAlert('نوع الملف غير صحيح'); $adinc->closePage(); exit;}
							
						if($uploaderMp3->upload() ){}else{print 'error In Upload ' . $uploaderMp3->file_error;}
						
						$DeleteAudioFile = $files->deleteFile("../upload/audio/files/" . $edit['file']);
		
						$file_name = $uploaderMp3->file_hash . $uploaderMp3->file_name;
						
					}else{
					
						$file_name = $edit['file'];
							
					}
						
					$cat_id  = intval(abs($_POST['cat_id']));
								
					$insert = $db->query("update audio_module_files set title='$title' , caption='$more' , file='$file_name' , catid='$cat_id' where id=$id");
					
					if($insert){
						
							$adinc->greenAlert('تم التعديل بنجاح');
							$adinc->location("modules.php?action=admin&module=".ModuleID."&page=files");
						}
				}
				
				
				
			$sections = $db->query("select * from audio_module_sections");
			
			$table = '<form method="post" enctype="multipart/form-data">
						<table width="100%" border="0" cellspacing="0" cellpadding="5">
					  <tr>
						<td>عنوان الملف : </td>
						<td><input type="text" name="title" value="'.$edit['title'].'" id="input_1" /></td>
					  </tr>
					  <tr>
						<td>مختصر عن الملف :</td>
						<td><input type="text" name="more" value="'.$edit['caption'].'" style="width:80%;" id="input_2" /></td>
					  </tr>
					 <tr>
						<td>الملف الصوتي :</td>
						<td><input type="file" name="file" id="input_4" /></td>
					  </tr>
					  <tr>
						<td>التصنيف :</td>
						<td>
						<select name="cat_id">';
						
			while($rows = $db->rows($sections)){
			
				$table .= '<option value="'.$rows['id'].'"'; if($rows['id'] == $edit['catid']){ $table .= ' selected="selected"';} $table .= '> . '.$rows['title'].'</option>';	
			}
			
			$table .='</select>
						</td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit" value="إضافة" onclick="addtopic();" /></td>
					  </tr>
					</table>
					</form>';
		
			$adinc->getMenu("تعديل الملف الصوتي '".$edit['title']."'" , $table);
		
		}
	
		public function deleteFile(){
		
			global $db , $adinc;
					
			$id = intval(abs($_REQUEST['id']));
		
			if(!is_numeric($id) || $id == 0){exit;}
					
			$query = $db->query("select * from audio_module_files where id = $id");
			
			$edit  = $db->rows($query);
			
			if($db->total($query) == 0){exit;}
				
			$objectsManager = new objectsManager;
		
			$files = $objectsManager->getFilesObject();
			
			$DeleteAudioFile = $files->deleteFile("../upload/audio/files/" . $edit['file']);
			
			$delete = $db->query("delete from audio_module_files where id = $id"); 
			
			if($delete){ print mysql_error(); }
				
		}
	
		public function deleteSection(){
		
			global $db , $adinc;
		
			$id 	 = intval(abs($_REQUEST['id']));
		
			if(!is_numeric($id) || $id == 0){exit;}

			$query = $db->query("select * from blog_module_sections where id = $id");
			
			$edit  = $db->rows($query);
			
			if($db->total($query) == 0){exit;}
				
			$objectsManager = new objectsManager;
		
			$files = $objectsManager->getFilesObject();
			
			$DeleteAudioFile = $files->deleteFile("../upload/audio/sections/" . $edit['photo']);
			
			$delete = $db->query("delete from blog_module_sections where id = $id"); 
			
			if($delete){ print mysql_error(); }	
		}


		public function mangeComments(){
			
			global $db , $adinc;
			
			echo '<script type="text/javascript">
			
				function activeComment(id){
				
					$("#topic-" + id + " td").fadeOut();
					
					$("#loadArea").load("fast-modules.php?action=admin&module=' . ModuleID . '&method=actComment&active=1&id=" + id);	
				}
				
				function unActiveComment(id){
				
					$("#topic-" + id + " td").fadeOut();
					
					$("#loadArea").load("fast-modules.php?action=admin&module=' . ModuleID . '&method=actComment&active=0&id=" + id);	
				}
				
					function deleteComment(id){
				
					$("#topic-" + id + " td").fadeOut();
					
					$("#loadArea").load("fast-modules.php?action=admin&module=' . ModuleID . '&method=deleteComment&id=" + id);	
				}
				
				function updateComment(id){
				
					$("#TB_overlay").fadeIn();
					$("#TB_window").show().html("Loading...");
					$("#TB_window").load("fast-modules.php?action=admin&module=' . ModuleID . '&method=editComment&id=" + id);
				}
				
				function activeAll($action){
				
					var ids = document.getElementsByName("do_all[]");
					
					var leg = ids.length;
									
					var all_ids = "";
								
					$(".systemAlert").fadeIn();
			
					for(var x=0; x!=leg;x++){

						if($(ids[x]).attr("checked") == "checked"){
							
							if(x==0){ all_ids += $(ids[x]).val(); }else{ all_ids += "," + $(ids[x]).val();  }
							
							$("#topic-" + $(ids[x]).val()).hide();
						}
															
						$("#loadArea").load("fast-modules.php?action=admin&module=' . ModuleID . '&method=actComment&active="+$action+"&ids=" + all_ids , 
						
						function(){
							
							$(".systemAlert").fadeOut();
				
						});	
			
					}
						
				}
				
			function deleteAll(){
				
					var ids = document.getElementsByName("do_all[]");
					
					var leg = ids.length;
				
					var all_ids = "";
								
					$(".systemAlert").fadeIn();
			
					for(var x=0; x!=leg;x++){
						
						if($(ids[x]).attr("checked") == "checked"){
							
							if(x==0){ all_ids += $(ids[x]).val(); }else{ all_ids += "," + $(ids[x]).val();  }
							
							$("#topic-" + $(ids[x]).val()).hide();
						}
									
						$("#loadArea").load("fast-modules.php?action=admin&module=' . ModuleID . '&method=deleteComment&ids=" + all_ids , 
						
						function(){
							
							$(".systemAlert").fadeOut();
				
						});	
			
					}
						
				}
				
				function close_box(){
					
					$("#TB_overlay").fadeOut();
					$("#TB_window").hide().html();
			
				}
				
				function postEdit(id){
				
					var name = $("#post_name").val();
					var post = $("#post").val();
					
					$.post("fast-modules.php?action=admin&module=' . ModuleID . '&method=editCommentPost&id=" + id , {
						
							"sender" : 1 ,
							"name" : name , 
							"comment" : post 
						} , function(){
						
							$("#writer-" + id).html(name);
										
							var new_post = post.replace(/\n/gi , "<br />");
							
							$("#comment-" + id).html(new_post);
							
							close_box();
							
						});	
				}
				
				function do_all(val){
				
					var ids = document.getElementsByName("do_all[]");
					
					var leg = ids.length;
							
					for(var x=0; x!=leg;x++){
					
						if(val == 1){
						
							$(ids[x]).attr("checked" , "checked");
											
							$("#all").attr("value" , 2);
							
						}else{
							
							$(ids[x]).removeAttr("checked");
				
							$("#all").attr("value" , 1);
			
						}
					}	
				}
			</script>';
					
			
			print '<div id="loadArea" style="display:none;"></div>';
			print '<div id="TB_overlay" class="TB_overlayBG" ></div>
			<div id="TB_window"></div>';
					
			$objectsManager = new objectsManager;
				
			$page = $objectsManager->getPaginatorObject();	
			
			$page->SetPerPage(15);
			
			$active = intval(abs($_REQUEST['active']));
			
			$page->SetPageUrl("modules.php?action=admin&module=".ModuleID."&method=mangeComments&active=" . $active);
			
			$Topics = $page->CreatePaginator("select * from audio_module_comments where active = $active order by id desc");
			
			$total_actived = $db->total($db->query("select * from audio_module_comments where active = 1"));
			$total_unactived = $db->total($db->query("select * from audio_module_comments where active = 0"));
		
			print '<a href="modules.php?action=admin&module='.ModuleID.'&method=mangeComments&active=1">مفعل ('.$total_actived.')</a> | ';	
			print '<a href="modules.php?action=admin&module='.ModuleID.'&method=mangeComments&active=0">غير مفعل ('.$total_unactived.')</a>';	
			
			$table = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
			
				$table .= '
				<tr>
				<td class="td_title"><input type="checkbox" id="all" value="1" onchange="do_all(this.value);" /></td>
				<td class="td_title">الكاتب</td>
				<td class="td_title" width="70%">التعليق</td>
				</tr>
				';		
		
			while($rows = $db->rows($Topics)){
			
				
				$table .= '
				<tr id="topic-'.$rows['id'].'">
				<td class="td_row"><input type="checkbox" name="do_all[]" value="'.$rows['id'].'" /></td>
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
						
						$table .='<a href="javascript:activeComment('.$rows['id'].')">تفعيل</a>';
				
					}else{
		
						$table .='<a href="javascript:unActiveComment('.$rows['id'].')">تعطيل</a>';
						
					}
					
					$table .=' 
					| <a href="javascript:updateComment('.$rows['id'].')">تحرير</a>
					| <a onclick="if(window.confirm(\'هل أنت متأكد العملية\') == false){return false;}" href="javascript:deleteComment('.$rows['id'].')">حذف</a>
					
					</div></td>
				</tr>
				';
			}
			
			$table .= '</table>';
		
			// display Links
			$adinc->getExtraLinks($this->extraLink);
					
			$GetPagination = $page->GetPagination();
		
			$adinc->getMenu('برنامج المدونة - التحكم بالتعليقات' , $table . $page->GetPagination() );
		
			print '<div class="tools_bar">
						<input type="button" onclick="activeAll(1)" value="تفعيل المحدد" />
						<input type="button" onclick="activeAll(0)" value="تعطيل المحدد" />
						<input type="button" onclick="deleteAll()" value="حذف المحدد" />
						<div style="float:left;">'.$GetPagination.'</div>		
					</div>';				
			
		}
		
		
		public function editComment(){
		
			global $db , $adinc;
			
			$id = intval(abs($_REQUEST['id']));
			
			$rows = $db->rows($db->query("select * from audio_module_comments where id = $id"));
			
			$table = '<table class="editor" dir="rtl" width="500" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td>الإسم</td>
						<td><input type="text" id="post_name" value="'.$rows['name'].'" /></td>
					  </tr>
					  <tr>
						<td>التعليق</td>
						<td><textarea id="post" cols="30" rows="4">'.$rows['comment'].'</textarea></td>
					  </tr>
					  <tr>
						<td></td>
						<td><input type="button" onclick="postEdit('.$id.')" value="تعديل" /> <input type="button" onclick="close_box()" value="إلغاء الأمر" /></td>
					  </tr>
					</table>
					</div>';
			
			echo $table;	
		}
	
		public function editCommentPost(){
			
			global $db , $adinc;
					
			$id 	 = intval(abs($_REQUEST['id']));
		
			$name = strip_tags(trim($_POST['name']));
			
			$post = strip_tags($_POST['comment']);
			
			$update = $db->query("update audio_module_comments set name='$name' , comment='$post' where id = $id");
		}
		
		public function deleteComment(){
		
			global $db , $adinc;
			
			$id 	 = intval(abs($_REQUEST['id']));
			$ids 	 = strip_tags(trim($_REQUEST['ids']));
		
			if(!empty($ids)){
					
				$action = $db->query("delete from audio_module_comments where `id` in (".$ids.")"); 
			
			}else if(!empty($id)){
		
				$delete = $db->query("delete from audio_module_comments where id = $id"); 
				
			}
				
			return true;			
		}
		
		public function actComment(){
		
			global $db , $adinc;
					
			$id 	 = _safeInt($_REQUEST['id']);
			$ids 	 = _safeString($_REQUEST['ids']);
			$active  = _safeInt($_REQUEST['active']);
		
			if(!empty($ids)){
					
				$action = $db->query("update audio_module_comments set active = $active where `id` in (".$ids.")"); 
			
			}else if(!empty($id)){
		
				$action = $db->query("update audio_module_comments set active = $active where id = $id"); 
				
			}
			
			return true;
		}
	
	
		public function editSection(){
		
			global $db , $adinc;
		
			// display Links
			$adinc->getExtraLinks($this->extraLink);
			
			$objectsManager = new objectsManager;
		
			$files = $objectsManager->getFilesObject();
			
			$id = intval(abs($_REQUEST['id']));
			
			$edit = $db->rows($db->query("select * from audio_module_sections where id = $id"));
			
			if(isset($_POST['submit']) and $_POST['submit'] !== "submited"){
				
					$section = strip_tags($_POST['section_name']);
					
				if(isset($_FILES['photo']) and !empty($_FILES['photo']['name'])){
						
					$uploader = new Uploader;
					$uploader->DIR_TO = "../upload/audio/sections/";
					$uploader->FILE_VER = "photo";
					$uploader->FILES_SIZE = 1296287;
					$uploader->TYPES = array('image/jpeg' , 'image/png' , 'image/gif' , 'image/jpg');
					$uploader->FILE_EXT = array('.jpg' , '.png' , '.PNG' , '.JPG' , '.GIf' , '.gif' , '.jpeg' , '.JPEG');
					
					$uploader->getFileInformation();
							
					if(!$uploader->checkFileContent()){print 'error file content'; exit;}
					if(!$uploader->checkFileSize() ){ print 'error file size'; exit;}
					if(!$uploader->checkFileType() ){ print 'error file type'; exit;}
						
					if($uploader->upload() ){ $files->deleteFile("../upload/audio/sections/" . $edit['photo']); }else{print 'error In Upload ' . $uploader->file_error;}
					
					$photo_name= $uploader->file_hash . $uploader->file_name;
					
				}else{
				
					$photo_name = $edit['photo'];	
				}
				
					$update = $db->query("update audio_module_sections set title='$section' , photo='$photo_name' where id = $id");
			
					if($update){
						
							$adinc->greenAlert("تمت عملية التعديل بنجاح");
						
						}
				}
			
			$table = '<form method="post" enctype="multipart/form-data">
						<table width="100%" border="0" cellspacing="0" cellpadding="5">
						  <tr>
							<td width="20%">إسم التصنيف</td>
							<td><input type="text" name="section_name" value="'.$edit['title'].'" id="section_name" /></td>
						  </tr>
						   <tr>
							<td>صورة القسم :</td>
							<td><input type="file" name="photo" id="input_3" /></td>
						  </tr>
						  <tr>
							<td>&nbsp;</td>
							<td><input type="submit" value="حفظ" name="submit" onclick="if(empty($(\'#section_name\').val()) == \'\'){ alert(\'تحقق من الحقل\'); return false; }" /></td>
						  </tr>
						</table>
						</form>';
		
			$adinc->getMenu("برنامج المدونة - تعديل تصنيف '" . $edit['title'] . "'" , $table);
	
	
		}
	}
	
?>