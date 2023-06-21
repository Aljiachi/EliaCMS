<?php

	$this->includeAdminFile("index");

	$id = intval(abs($_REQUEST['id']));
	
	$edit = $db->rows($db->query("select * from blog_module_sections where id = $id"));
	
	if(isset($_POST['submit']) and $_POST['submit'] !== "submited"){
		
			$section = strip_tags($_POST['section_name']);
			
			$update = $db->query("update blog_module_sections set title='$section' where id = $id");
	
			if($update){
				
					$adinc->greenAlert("تمت عملية التعديل بنجاح");
				
				}
		}
	
	$table = '
<form method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="20%">إسم التصنيف</td>
    <td><input type="text" name="section_name" value="'.$edit['title'].'" id="section_name" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="إضافة" name="submit" onclick="if(empty($(\'#section_name\').val()) == \'\'){ alert(\'تحقق من الحقل\'); return false; }" /></td>
  </tr>
</table>
</form>
';

	$adinc->getMenu("برنامج المدونة - تعديل تصنيف '" . $edit['title'] . "'" , $table);
	
?>