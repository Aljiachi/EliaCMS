<?php

	class eliaModuleAdmin{
	
		public $extraLink;

		public function __construct(){
			
			$this->extraLink = array(
				' إضافة فيديو ' 		=> 'modules.php?action=admin&module='.ModuleID.'&method=upload' , 
				' التحكم بالتصانيف ' 	=> 'modules.php?action=admin&module='.ModuleID.'&method=mangeSections' , 
				' إضافة تصنيف ' 		=> 'modules.php?action=admin&module='.ModuleID.'&method=addSection' , 
				' التحكم بالفيديوهات ' 	=> 'modules.php?action=admin&module='.ModuleID.'&method=videos' , 
				' التحكم بالتعليقات  ' 	=> 'modules.php?action=admin&module='.ModuleID.'&method=mangeComments');
			
		}
		
		public function main(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
				
		}
		
		public function upload(){
		
			global $db , $adinc;
			
			// include JS scripts
			$adinc->getJs("plupload");
			$adinc->getJs("plupload.html5");	
			
			// video key
			$Videokey = 
				substr(md5(sha1(time() + rand(111111,999999) ) ) , 0 , 3). 
				substr(md5(sha1(time() + rand(111111,888888) ) ) , 0 , 3). 
				substr(md5(sha1(time() + rand(111111,777777) ) ) , 0 , 3);
				
			// display Links
			$adinc->getExtraLinks($this->extraLink);
					
			$adinc->jsLanguage(array(
			'ModuleID' => ModuleID , 
			'Videokey' => $Videokey
			));
			
			if(isset($_POST['submit'])){
					
					$title = strip_tags($_POST['title']);
				
					$more  = strip_tags($_POST['more']);
					
					$postKey = strip_tags(trim($_POST['key']));
		
					$keywords = strip_tags(trim($_POST['keywords']));
				
					$keyVars = array('-' , '_' , ' @' , ' @');
					
					if(empty($keywords)){
					
						$keywords = str_replace(" " , "," , str_replace($keyVars , "" , $title) );	
					}
					
				if(isset($_FILES['photo']) and !empty($_FILES['photo']['name'])){ 
					
					$uploader = new Uploader;
					$uploader->DIR_TO = "../upload/videos/photos/";
					$uploader->FILE_VER = "photo";
					$uploader->FILES_SIZE = 1296287*60;
					$uploader->TYPES = array('image/jpeg' , 'image/png' , 'image/gif' , 'image/jpg');
					$uploader->FILE_EXT = array('.jpg' , '.png' , '.PNG' , '.JPG' , '.GIf' , '.gif' , '.jpeg' , '.JPEG');
					
					$uploader->getFileInformation();
		
					if(!$uploader->checkFileContent()){ 
						
						$adinc->redAlert('تحقق من الصورة');
						return false;
						
					}
							
					if(!$uploader->upload() ){ print 'error In Upload ' . $uploader->file_error;}
					
					$file_name = $uploader->file_hash . $uploader->file_name;
					
				}
					
				$all = $_POST['text'];
			
				$cat_id  = intval(abs($_POST['cat_id']));
		
				$length  = strip_tags(trim($_POST['length']));
					
				$date = time();
					
				if(isset($_FILES['photo']) and !empty($_FILES['photo']['name'])){ 
		
					$insert = $db->query("update `videos_module_files` set
					`title`='$title' , 
					`caption`='$more' , 
					`catid`='$cat_id' , 
					`photo`='$file_name' , 
					`keywords`='$keywords' ,
					`length`='$length' , 
					`time`='$date'
					where `key`='$postKey'");
					
				}else{
		
					$insert = $db->query("update `videos_module_files` set
					`title`='$title' , 
					`caption`='$more' , 
					`catid`='$cat_id' , 
					`length`='$length' ,
					`keywords`='$keywords' , 
					`time`='$date'
					where `key`='$postKey'");
				}
					
				if($insert){
						
					$adinc->greenAlert('تم حفظ الفيديو بنجاح');

				}

			}	
				
			$sections = $db->query("select * from videos_module_sections");
			
			$table = '
					<div id="uploadTypes">
					<a class="uploadType" id="uploadFile" onclick="changeHandler(1)">رفع ملف</a>
					<a class="uploadType" id="uploadUrl" onclick="changeHandler(2)">رفع من رابط</a>			
					<a class="uploadType" id="uploadYoutube" onclick="changeHandler(3)">رفع من اليوتيوب</a>			
				</div>
		
			<div id="boxUrl" class="bbox" style="display:none;">
			<input type="text" id="file_url" style="width:350px;" dir="ltr" /><input type="submit" id="getFile" value="رفع الفيديو" />
				<div id="UrlAlerts"></div>
			</div>
			
			<div id="boxYoutube" class="bbox" style="display:none;">
			<input type="text" id="youtube_url" style="width:350px;" dir="ltr" /><input type="submit" id="getYoutube" value="جلب من اليوتيوب" />
				<div id="YoutubeAlerts"></div>
			</div>
		
			<div id="boxFile" class="bbox">
				<div id="container">
				<div id="filelist"></div>
					<a id="pickfiles" href="javascript:;">إضغط هنا لتحديد الفيديو</a> 
					<center><input type="submit" id="uploadfiles" style="display:none;" value="رفع الفيديو" /></center>
				</div>
			</div>
			
			<div id="info_area" style="display:none;"><form method="post" enctype="multipart/form-data">
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
		  <tr>
			<td>عنوان الفيديو : </td>
			<td><input type="text" name="title" id="input_1" /></td>
		  </tr>
			<tr>
			<td>مدة الفيديو : </td>
			<td><input type="text" name="length" id="input_4" /></td>
		  </tr>
			<tr id="photoInput">
			<td>صورة الفيديو :</td>
			<td><input type="file" name="photo" id="input_3" /></td>
		  </tr>
		  <tr>
			<td>وصف الفيديو :</td>
			<td><textarea type="text" name="more" style="width:80%;" rows="5" id="input_2"></textarea></td>
		  </tr>
			<tr>
			<td>الكلمات المفتاحية : </td>
			<td><input type="text" name="keywords" id="input_5" /></td>
		  </tr>
		  <tr>
			<td>التصنيف :</td>
			<td>
			<select name="cat_id">
			';
			
			while($rows = $db->rows($sections)){
			
				$table .= '<option value="'.$rows['id'].'"> . '.$rows['title'].'</option>';	
			}
			
			$table .='
			</select>
			</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" id="submit" value="حفظ" disabled="disabled" class="disabled" onclick="addtopic();" /></td>
			  </tr>
			</table>
			<input type="hidden" name="key" value="'.$Videokey.'" />
			</form></div>';
		
			$adinc->getMenu("إضافة فيديو" , $table);
	
			$adinc->getJs("uploader/uploader.videos.new");		
		}
		
		public function mangeSections(){
		
			global $db , $adinc;
			
			echo '<script type="text/javascript">
					function deleteSection(id){
					
						$("#topic-" + id).fadeOut();
						$("#loadArea").load("fast-modules.php?action=admin&module='.ModuleID.'&method=deleteSection&id=" + id);	
					}
				</script>';	
						
			print '<div id="loadArea" style="display:none;"></div>';
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
						
			$objectsManager = new objectsManager;
				
			$page = $objectsManager->getPaginatorObject();
			
			$page->SetPerPage(20);
			
			$Topics = $page->CreatePaginator("select * from videos_module_sections order by id desc");
			
			$table = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
				
				$table .= '
				<tr>
				<td class="td_title" width="120">#</td>
				<td class="td_title">عنوان التصنيف</td>
				<td class="td_title" width="90" align="center">عدد الملفات</td>
				<td class="td_title" width="30" align="center">تعديل</td>
				<td class="td_title" width="30" align="center">حذف</td>
				</tr>
				';		
		
		
			while($rows = $db->rows($Topics)){
			
				$qfiles = $db->query("select id from videos_module_files where catid =" . $rows['id']);
				
				$files = $db->total($qfiles);
				
				$table .= '
				<tr id="topic-'.$rows['id'].'">
				<td class="td_row"><img src="../upload/videos/sections/'.$rows['photo'] .'" width="120" height="80" /></td>
				<td class="td_row section_name">'.$rows['title'].'</td>
				<td class="td_row" align="center">'.$files.'</td>
				<td class="td_row" align="center"><a href="modules.php?action=admin&module='.ModuleID.'&method=editSection&id='.$rows['id'].'"><img src="icons/edit.png" /></a></td>
				<td class="td_row" align="center"><a onclick="if(window.confirm(\'هل أنت متأكد العملية\') == false){return false;}" href="javascript:deleteSection('.$rows['id'].')"><img src="icons/remove.png" /></a></td>
				</tr>
				';
			}
			
			$table .= '</table>';
			
			$adinc->getMenu('المكتبة المرئية - التحكم بالتصنيفات' , $table);

		}
	
		public function addSection(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
						
			if(isset($_POST['submit']) and $_POST['submit'] !== "submited"){
					
						$section = strip_tags($_POST['section_name']);
						
						$uploader = new Uploader;
						$uploader->DIR_TO = "../upload/videos/sections/";
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
						
						$insert = $db->query("insert into videos_module_sections values ('' , '$section' , '$photo_name' , '0')");
				
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
	
		public function videos(){
		
			global $db , $adinc;
			
			echo '<script type="text/javascript">
						function deleteTopic(id){
							$("#topic-" + id).fadeOut();
							$("#loadArea").load("fast-modules.php?action=admin&module=' . ModuleID . '&method=deleteVideo&id=" + id);	
						}
					</script>';	
					
			print '<div id="loadArea" style="display:none;"></div>';
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
			
			$objectsManager = new objectsManager;
				
			$page = $objectsManager->getPaginatorObject();
			
			$page->SetPerPage(20);
			
			$Files = $page->CreatePaginator("select * from videos_module_files order by id desc");
			
			$table = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
				
				$table .= '
				<tr>
				<td class="td_title" width="130">#</td>
				<td class="td_title">عنوان الملف</td>
				<td class="td_title">التعليقات</td>
				<td class="td_title">تعديل</td>
				<td class="td_title">حذف</td>
				</tr>
				';		
		
			while($rows = $db->rows($Files)){
			
				$posts = $db->query("select id from videos_module_comments where topic_id =" . $rows['id']);
				
				$posts = $db->total($posts);
				
				$table .= '
				<tr id="topic-'.$rows['id'].'">
				<td class="td_row"><img src="../upload/videos/photos/'.$rows['photo'] .'" width="120" height="80" /></td>
				<td class="td_row">'.$rows['title'].'</td>
				<td class="td_row">'.$posts.'</td>
				<td class="td_row"><a href="modules.php?action=admin&module='.ModuleID.'&method=editVideo&id='.$rows['id'].'"><img src="icons/edit.png" /></a></td>
				<td class="td_row"><a onclick="if(window.confirm(\'هل أنت متأكد العملية\') == false){return false;}" href="javascript:deleteTopic('.$rows['id'].')"><img src="icons/remove.png" /></a></td>
				</tr>
				';
			}
			
			$table .= '</table>';
			
			print $page->GetPagination();
		
			$adinc->getMenu('المكتبة المرئية - التحكم بالملفات' , $table . $page->GetPagination());
			
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
			
			$Topics = $page->CreatePaginator("select * from videos_module_comments where active = $active order by id desc");
			
			$total_actived = $db->total($db->query("select * from videos_module_comments where active = 1"));
			$total_unactived = $db->total($db->query("select * from videos_module_comments where active = 0"));
		
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
			
			$rows = $db->rows($db->query("select * from videos_module_comments where id = $id"));
			
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
			
			$update = $db->query("update videos_module_comments set name='$name' , comment='$post' where id = $id");
		}
		
		public function deleteComment(){
		
			global $db , $adinc;
			
			$id 	 = intval(abs($_REQUEST['id']));
			$ids 	 = strip_tags(trim($_REQUEST['ids']));
		
			if(!empty($ids)){
					
				$action = $db->query("delete from videos_module_comments where `id` in (".$ids.")"); 
			
			}else if(!empty($id)){
		
				$delete = $db->query("delete from videos_module_comments where id = $id"); 
				
			}
				
			return true;			
		}
		
		public function actComment(){
		
			global $db , $adinc;
					
			$id 	 = _safeInt($_REQUEST['id']);
			$ids 	 = _safeString($_REQUEST['ids']);
			$active  = _safeInt($_REQUEST['active']);
		
			if(!empty($ids)){
					
				$action = $db->query("update videos_module_comments set active = $active where `id` in (".$ids.")"); 
			
			}else if(!empty($id)){
		
				$action = $db->query("update videos_module_comments set active = $active where id = $id"); 
				
			}
			
			return true;
		}
	
		public function deleteSection(){
			
			global $db , $adinc;
		
			$id 	 = intval(abs($_REQUEST['id']));
		
			$delete = $db->query("delete from videos_module_sections where id = $id"); 	
			
			return true;
		}
	
		public function editSection(){
		
			global $db , $adinc;

			$objectsManager = new objectsManager;
		
			$files = $objectsManager->getFilesObject();
			
			$id = intval(abs($_REQUEST['id']));
			
			$edit = $db->rows($db->query("select * from videos_module_sections where id = $id"));
			
			if(isset($_POST['submit']) and $_POST['submit'] !== "submited"){
				
				$section = strip_tags($_POST['section_name']);
					
				if(isset($_FILES['photo']) and !empty($_FILES['photo']['name'])){
						
					$uploader = new Uploader;
					$uploader->DIR_TO = "../upload/videos/sections/";
					$uploader->FILE_VER = "photo";
					$uploader->FILES_SIZE = 1296287;
					$uploader->TYPES = array('image/jpeg' , 'image/png' , 'image/gif' , 'image/jpg');
					$uploader->FILE_EXT = array('.jpg' , '.png' , '.PNG' , '.JPG' , '.GIf' , '.gif' , '.jpeg' , '.JPEG');
					
					$uploader->getFileInformation();
							
					if(!$uploader->checkFileContent()){print 'error file content'; exit;}
					if(!$uploader->checkFileSize() ){ print 'error file size'; exit;}
					if(!$uploader->checkFileType() ){ print 'error file type'; exit;}
						
					if($uploader->upload() ){ $files->deleteFile("../upload/videos/sections/" . $edit['photo']); }else{print 'error In Upload ' . $uploader->file_error;}
					
					$photo_name= $uploader->file_hash . $uploader->file_name;
					
				}else{
				
					$photo_name = $edit['photo'];	
				}
				
				$update = $db->query("update videos_module_sections set title='$section' , photo='$photo_name' where id = $id");
			
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
		
			// display Links
			$adinc->getExtraLinks($this->extraLink);
			
			$adinc->getMenu("برنامج المدونة - تعديل تصنيف '" . $edit['title'] . "'" , $table);
		
		}
		
		
		public function editVideo(){
		
			global $db , $adinc;
					
			$id = intval(abs($_REQUEST['id']));
		
			if(!is_numeric($id) || $id == 0){exit;}
					
			$query = $db->query("select * from videos_module_files where id = $id");
			
			$edit  = $db->rows($query);
			
			if($db->total($query) == 0){exit;}
				
			$objectsManager = new objectsManager;
		
			$files = $objectsManager->getFilesObject();
			
			if(isset($_POST['submit'])){
					
					$title = strip_tags($_POST['title']);
				
					$more  = strip_tags($_POST['more']);
					
					$keywords = strip_tags(trim($_POST['keywords']));
				
					$keyVars = array(' -' , '- ' , '-' , '_' , '@ ' , ' @' , ')' , '(' , '|' , ' ');
					
					if(empty($keywords)){
					
						$keywords = str_replace(" " , "," , str_replace($keyVars , "" , $title) );	
					}
					
					if(isset($_FILES['photo']) and !empty($_FILES['photo']['name'])){
							
						$uploader = new Uploader;
						$uploader->DIR_TO = "../upload/videos/photos/";
						$uploader->FILE_VER = "photo";
						$uploader->FILES_SIZE = 1296287*60;
						$uploader->TYPES = array('image/jpeg' , 'image/png' , 'image/gif' , 'image/jpg');
						$uploader->FILE_EXT = array('.jpg' , '.png' , '.PNG' , '.JPG' , '.GIf' , '.gif' , '.jpeg' , '.JPEG');
						
						$uploader->getFileInformation();
								
						if(!$uploader->checkFileContent()){print 'error file content'; exit;}
						if(!$uploader->checkFileSize() ){ $adinc->redAlert('حجم الملف كبير جداً'); $adinc->closePage();  exit;}
						if(!$uploader->checkFileType() ){ $adinc->redAlert('نوع الملف غير صحيح'); $adinc->closePage(); exit;}
							
						if($uploader->upload() ){}else{print 'error In Upload ' . $uploader->file_error;}
						
						$DeleteAudioFile = $files->deleteFile("../upload/videos/photos/" . $edit['photo']);
		
						$file_name = $uploader->file_hash . $uploader->file_name;
						
					}else{
					
						$file_name = $edit['photo'];
							
					}
						
					$cat_id  = intval(abs($_POST['cat_id']));
								
					$insert = $db->query("update videos_module_files set 
												title='$title' ,
												caption='$more' , 
												photo='$file_name' ,
												keywords='$keywords' , 
												catid='$cat_id' where id=$id");
					
					if($insert){
						
							$adinc->greenAlert('تم التعديل بنجاح');
							$adinc->location("modules.php?action=admin&module=".ModuleID."&page=files");
						}
				}
				
				
				
			$sections = $db->query("select * from videos_module_sections");
			
			$table = '<form method="post" enctype="multipart/form-data">
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
		  <tr>
			<td>عنوان الفيديو : </td>
			<td><input type="text" name="title" value="'.$edit['title'].'" id="input_1" /></td>
		  </tr>
		  <tr>
			<td>وصف الفيديو :</td>
			<td><textarea name="more" id="input_2" rows="5">'.$edit['caption'].'</textarea></td>
		  </tr>
			<tr>
			<td>مدة الفيديو : </td>
			<td><input type="text" name="length" id="input_4" value="'.$edit['length'].'" /></td>
		  </tr>
			<tr>
			<td>صورة الفيديو :</td>
			<td><input type="file" name="photo" id="input_3" /></td>
		  </tr>
			<tr>
			<td>الكلمات المفتاحية : </td>
			<td><input type="text" name="keywords" id="input_5" value="'.$edit['keywords'].'" /></td>
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
					<td><input type="submit" name="submit" value="حفظ" onclick="addtopic();" /></td>
				  </tr>
				</table>
				</form>';
		
			// display Links
			$adinc->getExtraLinks($this->extraLink);
			
			$adinc->getMenu("تعديل الفيديو '".$edit['title']."'" , $table);
		
		}
		
		public function deleteVideo(){
		
			global $db , $adinc;
					
			$id = intval(abs($_REQUEST['id']));
		
			if(!is_numeric($id) || $id == 0){exit;}
					
			$query = $db->query("select * from videos_module_files where id = $id");
			
			$edit  = $db->rows($query);
			
			if($db->total($query) == 0){exit;}
				
			$objectsManager = new objectsManager;
		
			$files = $objectsManager->getFilesObject();
			
			$DeleteFile = $files->deleteFile("../upload/videos/files/" . $edit['file']);
		
			$DeleteFile = $files->deleteFile("../upload/videos/photos/" . $edit['photo']);
			
			$deleteComments = $db->query("delete from videos_module_comments where topic_id = $id"); 
		
			$deleteVideo = $db->query("delete from videos_module_files where id = $id"); 
				
			return true;				
		}
		
		public function uploader(){
		
			global $db , $adinc;
						
			if($_REQUEST['type'] == "file"){
					
				// HTTP headers for no cache etc
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
				
				$siteInside = str_replace("/admincp/fast-modules.php" , "" , $_SERVER['SCRIPT_NAME']);
				$siteInside = str_replace("/" , "" , $siteInside);
				// Settings
				
				if($siteInside !== ""){
					
					$targetDir = $_SERVER["DOCUMENT_ROOT"]  . '/' . $siteInside . "/upload/videos/files";
				
				}else{
			
					$targetDir = $_SERVER["DOCUMENT_ROOT"]  . "/upload/videos/files";
					
				}
				
				
				// 5 minutes execution time
				@set_time_limit(5 * 60 * 60);
				
				// Uncomment this one to fake upload time
				// usleep(5000);
				
				// Get parameters
				$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
				$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
				$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
				
				$key = $_REQUEST['key'];
				$end = end(explode("." , $fileName));
				$fileNew = $_REQUEST['key'] . "." . $end;
				
				// Clean the fileName for security reasons
				$fileName = preg_replace('/[^\w\._]+/', '_', $_REQUEST['key'] . "." . $end);
			
				print_r($_FILES['file']);
				
				// Make sure the fileName is unique but only if chunking is disabled
				if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
					$ext = strrpos($fileName, '.');
					$fileName_a = substr($fileName, 0, $ext);
					$fileName_b = substr($fileName, $ext);
				
					$count = 1;
					while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
						$count++;
				
					$fileName = $fileName_a . '_' . $count . $fileName_b;
				}
				
				$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
				
				// Create target dir
				if (!file_exists($targetDir))
					@mkdir($targetDir);
				
				// Remove old temp files	
				if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
					while (($file = readdir($dir)) !== false) {
						$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
				
						// Remove temp file if it is older than the max age and is not the current file
						if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
							@unlink($tmpfilePath);
						}
					}
				
					closedir($dir);
				} else
				//	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
					
				
				// Look for the content type header
				if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
					$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
				
				if (isset($_SERVER["CONTENT_TYPE"]))
					$contentType = $_SERVER["CONTENT_TYPE"];
				
				// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
				if (strpos($contentType, "multipart") !== false) {
					if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
						// Open temp file
						$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
						if ($out) {
							// Read binary input stream and append it to temp file
							$in = fopen($_FILES['file']['tmp_name'], "rb");
				
							if ($in) {
								while ($buff = fread($in, 4096))
									fwrite($out, $buff);
							} else
								die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
							fclose($in);
							fclose($out);
							@unlink($_FILES['file']['tmp_name']);
						} else
							die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
				} else {
					// Open temp file
					$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
					if ($out) {
						// Read binary input stream and append it to temp file
						$in = fopen("php://input", "rb");
				
						if ($in) {
							while ($buff = fread($in, 4096))
								fwrite($out, $buff);
						} else
							die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				
						fclose($in);
						fclose($out);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
				}
				
				// Check if file has been uploaded
				if (!$chunks || $chunk == $chunks - 1) {
					// Strip the temp .part suffix off 
					rename("{$filePath}.part", $filePath);
				}

				if (!$chunks || $chunk == $chunks - 1) {
						
					$insert = $db->query("insert into `videos_module_files` (`file` , `key`) values ('$fileNew' , '$key')");
										
				}
					
			}else if($_REQUEST['type'] == "remote"){
			
				@set_time_limit(5 * 60 * 60);
				
				$fileName = strip_tags(trim($_POST['url']));
				$key = $_POST['key'];
				$end = end(explode("." , $fileName));
				$fileNew = $_POST['key'] . "." . $end;
				
				$ch = curl_init($fileName);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
				
				$data = curl_exec($ch);
				
				curl_close($ch);
				
				$up = file_put_contents("../upload/videos/files/" . $fileNew , $data);
				if($up){
					
					$insert = $db->query("insert into `videos_module_files` (`file` , `key`) values ('$fileNew' , '$key')");
				
					print mysql_error();
					
				}else{
				
					print 'error';	
				}
			}else if($_REQUEST['type'] == "youtube"){
			
				@set_time_limit(5 * 60 * 60); 
				
				$fileName = strip_tags(trim($_POST['url']));
			
				$thumb = strip_tags(trim($_POST['thumb']));
				$thumb = str_replace("default.jpg" , "mqdefault.jpg" , $thumb);
				
				$key = $_POST['key'];
				
				$newThumb = $key . ".jpg";
				
				$mtype = strip_tags(trim($_POST['mtype']));
				
				switch($mtype){
				
					case "video/webm" : $end = "webm"; break;
					case "video/mp4": $end = "mp4"; break;	
					case "video/x-flv": $end = "flv"; break;	
					case "video/3gpp": $end = "3gp"; break;	
				}
				
				$fileNew = $_POST['key'] . "." . $end;
			
				$chThumb = curl_init($thumb);
				curl_setopt($chThumb, CURLOPT_RETURNTRANSFER, true);
				
				$dataThumb = curl_exec($chThumb);
				
				curl_close($chThumb);
				
				$upThumb = file_put_contents("../upload/videos/photos/" . $newThumb , $dataThumb);
					
				$ch = curl_init($fileName);
				curl_setopt($ch , CURLOPT_CONNECTTIMEOUT , 360*60 );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
			
				$data = curl_exec($ch);
				
				curl_close($ch);
				
				$up = file_put_contents("../upload/videos/files/" . $fileNew , $data);
				
				if($up){
					
					$insert = $db->query("insert into `videos_module_files` (`file` , `key` , `photo` ) values ('$fileNew' , '$key' , '$newThumb')");
									
				}
				
			}
				
		} # end function(uploader).
		
		public function youtubeDownloader(){
		
			global $db , $adinc;
			
			// YouTube Downloader PHP
			// based on youtube-dl in Python http://rg3.github.com/youtube-dl/
			// by Ricardo Garcia Gonzalez and others (details at url above)
			//
			// Takes a VideoID and outputs a list of formats in which the video can be
			// downloaded
			
			
			function curlGet($URL) {
				$ch = curl_init();
				$timeout = 3;
				curl_setopt( $ch , CURLOPT_URL , $URL );
				curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
				curl_setopt( $ch , CURLOPT_CONNECTTIMEOUT , $timeout );
				$tmp = curl_exec( $ch );
				curl_close( $ch );
				return $tmp;
			}
			
			/*
			* function to use cUrl to get the headers of the file
			*/
			function get_location($url) {
			$my_ch = curl_init();
			curl_setopt($my_ch, CURLOPT_URL,$url);
			curl_setopt($my_ch, CURLOPT_HEADER, true);
			curl_setopt($my_ch, CURLOPT_NOBODY, true);
			curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($my_ch, CURLOPT_TIMEOUT, 10);
			$r = curl_exec($my_ch);
			foreach(explode("\n", $r) as $header) {
			if(strpos($header, 'Location: ') === 0) {
			return trim(substr($header,10));
			}
			}
			return '';
			}
			
			function get_size($url) {
			$my_ch = curl_init();
			curl_setopt($my_ch, CURLOPT_URL,$url);
			curl_setopt($my_ch, CURLOPT_HEADER, true);
			curl_setopt($my_ch, CURLOPT_NOBODY, true);
			curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($my_ch, CURLOPT_TIMEOUT, 10);
			$r = curl_exec($my_ch);
			foreach(explode("\n", $r) as $header) {
			if(strpos($header, 'Content-Length:') === 0) {
			return trim(substr($header,16));
			}
			}
			return '';
			}
			
			function get_description($url) {
			$fullpage = curlGet($url);
			$dom = new DOMDocument();
			@$dom->loadHTML($fullpage);
			$xpath = new DOMXPath($dom);
			$tags = $xpath->query('//div[@class="info-description-body"]');
			foreach ($tags as $tag) {
			$my_description .= (trim($tag->nodeValue));
			}
			
			return utf8_decode($my_description);
			}
			
			if(isset($_POST['videoid'])) {
			$my_id = $_POST['videoid'];
			} else {
			echo 'error.1';
			exit;
			}
			
			$stt = array('feature=player_embedded' , 'watch' , 'v=' ,'?' , '&' , '/');
			$my_id = str_replace("http://www.youtube.com/" , "" , $my_id);
			$my_id = str_replace("https:/youtube.com/" , "" , $my_id);
			$my_id = str_replace("http:/youtube.com/" , "" , $my_id);
			$my_id = str_replace("youtube.com/" , "" , $my_id);
			$my_id = str_replace("youtu.be" , "" , $my_id);
			$my_id = str_replace("youtu.be/" , "" , $my_id);
			$my_id = str_replace($stt , "" , $my_id);
			
			if(isset($_REQUEST['type'])) {
			$my_type = $_REQUEST['type'];
			} else {
			$my_type = 'redirect';
			}
			
			if(isset($_REQUEST['debug'])) {
			$debug = TRUE;
			} else {
			$debug = FALSE;
			}
			
			/* First get the video info page for this video id */
			$my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
			$my_video_info = curlGet($my_video_info);
			
			/* TODO: Check return from curl for status code */
			
			parse_str($my_video_info);
			echo '<p><img src="'. $thumbnail_url .'" style="float:right;" border="0" hspace="2" vspace="2"></p>';
			
			/* Now get the url_encoded_fmt_stream_map, and explode on comma */
			$my_formats_array = explode(',',$url_encoded_fmt_stream_map);
			
			if (count($my_formats_array) == 0) {
			echo '<p>No format stream map found - was the video id correct?</p>';
			exit;
			}
			
			/* create an array of available download formats */
			$avail_formats[] = '';
			$i = 0;
			
			foreach($my_formats_array as $format) {
			parse_str($format);
			$avail_formats[$i]['itag'] = $itag;
			$avail_formats[$i]['quality'] = $quality;
			$type = explode(';',$type);
			$avail_formats[$i]['type'] = $type[0];
			$avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig . '&client=youtube-download';
			parse_str(urldecode($url));
			$avail_formats[$i]['expires'] = date("G:i:s T", $expire);
			$avail_formats[$i]['ipbits'] = $ipbits;
			$avail_formats[$i]['ip'] = $ip;
			$i++;
			}
			
			if(!empty($avail_formats[0]['type'])){
				
				echo '<ul class="youTubeList">';
				/* now that we have the array, print the options */
				for ($i = 0; $i< count($avail_formats); $i++) {
				echo '<li><a onClick="downloadFromYoutube(\''. $avail_formats[$i]['url'] .'\',\''.$thumbnail_url.'\' , \''. $avail_formats[$i]['type'].'\')">'. $avail_formats[$i]['type'] .' ('. $avail_formats[$i]['quality'] .') </a> </li>';
				}
				echo '</ul>';
			
			}else{
				
				print '<red>تحقق من صحة الرابط</red>';
			}	
		
		} # end function(youtubeDownloader)
		
		
	}
	
?>