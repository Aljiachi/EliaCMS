<?php

	include("modules/blog/admin/index.php");

	if(isset($_POST['submit']) and $_POST['submit'] !== "submited"){
		
			$section = strip_tags($_POST['section_name']);
			
			$insert = $db->query("insert into blog_module_sections values ('' , '$section')");
	
			if($insert){
				
					$adinc->greenAlert("تم إضافة التصنيف بنجاح");
				
				}
		}
	
	$table = '
<form method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="20%">إسم التصنيف</td>
    <td><input type="text" name="section_name" id="section_name" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="إضافة" name="submit" onclick="if(empty($(\'#section_name\').val()) == \'\'){ alert(\'تحقق من الحقل\'); return false; }" /></td>
  </tr>
</table>
</form>
';

	$adinc->getMenu('إضافة تصنيف' , $table);
	
?>