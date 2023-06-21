<?php

	print '<center><a href="modules.php?action=admin&module='.ModuleID.'&page=index" class="fastgo_button">التحكم بالإضافة</a></center>';
	
	$EDIT_SETTING = mysql_query("select * from module_guestbook_setting");
	$ROWS_EDIT_SETTING = mysql_fetch_assoc($EDIT_SETTING);
	
	if(isset($_POST['submit'])){
	
		$add_comments = intval(abs($_POST['add_comments']));
		$state = intval(abs($_POST['state']));
	
		$Edit = mysql_query("update module_guestbook_setting set 
		gb_state='$add_comments' , 
		gb_poststate='$state'
		");
		
		if($Edit){ 					
		
			$adinc->greenAlert("تم الحفظ بنجاح");

		 }else{
			
			$adinc->redAlert("حدثت مشكلة ... حاول مرى أخرى"); 
		 }
		
	}
	
$table = '
<form method="post" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="20%">إضافة المشاركات</td>
    <td><select name="add_comments">
			<option value="1"'; if($ROWS_EDIT_SETTING['add_comments'] == 1){ $table .= ' selected="selected"'; } $table .= ' selected="selected">مفعلة</option>
			<option value="0"'; if($ROWS_EDIT_SETTING['add_comments'] == 0){ $table .= ' selected="selected"'; } $table .= ' >معطلة</option>
		</select>
	</td>
  </tr>
  <tr>
    <td width="20%">نشر المشاركات مباشرة</td>
    <td><select name="state">
			<option value="0"'; if($ROWS_EDIT_SETTING['player_type'] == 0){ $table .= ' selected="selected"'; } $table .= '>نعم</option>
			<option value="1"'; if($ROWS_EDIT_SETTING['palyer_type'] == 1){ $table .= ' selected="selected"'; } $table .= '>لا</option>
		</select>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="حفظ" name="submit" /></td>
  </tr>
</table>
</form>
';

	$adinc->getMenu('إعدادت برنامج المكتبة الصوتية' , $table);
?>