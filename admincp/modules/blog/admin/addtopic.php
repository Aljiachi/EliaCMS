<?php
	
	$ms = array('يناير' , 'فبراير' , 'مارس' , 'إبريل' , 'مايو' , 'يونيو' , 'يوليو' , 'أغسطس' , 'سبتمبر' , 'أكتوبر' , 'نوفمبر' , 'ديسمبر');

	$this->includeAdminFile("index");
	
	if(isset($_POST['submit'])){
			
			$title = strip_tags($_POST['title']);
		
			$more  = strip_tags($_POST['more']);
			
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
					
			if(!$uploader->checkFileContent()){print 'error file content'; exit;}
			if(!$uploader->checkFileSize() ){ print 'error file size'; exit;}
			if(!$uploader->checkFileType() ){ print 'error file type'; exit;}
				
			if($uploader->upload() ){}else{print 'error In Upload ' . $uploader->file_error;}
			
			$photo_name= $uploader->file_hash . $uploader->file_name;
			
			$all = $_POST['text'];
	
			$cat_id  = intval(abs($_POST['cat_id']));
			
			$insert = $db->query("insert into blog_module_topics values ('' , '$title' , '$more' , '$all' , '0' , '$cat_id' , '$date' , '$photo_name')");
			
			if($insert){
				
					$adinc->greenAlert('تم إضافة التدوينة بنجاح');
				}
		}
		
		
		
	$sections = $db->query("select * from blog_module_sections");
	
	$table = '<form method="post" enctype="multipart/form-data">
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>عنوان التدوينة : </td>
    <td><input type="text" name="title" id="input_1" /></td>
  </tr>
  <tr>
    <td>مختصر :</td>
    <td><input type="text" name="more" style="width:80%;" id="input_2" /></td>
  </tr>
 <tr>
    <td>صورة المقال :</td>
    <td><input type="file" name="photo" id="input_3" /></td>
  </tr>
  <tr>
    <td>تاريخ الإضافة :</td>
    <td>
	الشهر : <select name="date_m">
	';
	foreach($ms as $m_list){ $x++;
	
		$table .= '<option value="'.$x.'">' . $m_list . '</option>' . "\n";	
	}
	$table .= '</select>
	اليوم : <input type="text" name="date_d" style="width:30px;" />
	السنة : <input type="text" name="date_y" style="width:30px;" />
	الساعة : <input type="text" name="date_h" style="width:30px;" />
	الدقيقة : <input type="text" name="date_i" style="width:30px;" />
	</td>
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
    <td>التدوينة</td>
    <td><textarea name="text" id="text" cols="50" rows="5"></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="submit" value="إضافة" onclick="addtopic();" /></td>
  </tr>
</table>
</form>
';

	$adinc->getMenu("إضافة تدوينة" , $table);
	
?>