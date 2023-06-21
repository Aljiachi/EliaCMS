<?php

	$id = intval(abs($_REQUEST['id']));
	
	$rows = $db->rows($db->query("select * from blog_module_comments where id = $id"));
	
	$table = '
			
<table class="editor" dir="rtl" width="500" border="0" cellspacing="0" cellpadding="0">
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
	
		</div>
		
	';
	
	print $table;
?>
