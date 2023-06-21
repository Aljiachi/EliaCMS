<?php

	$this->includeAdminFile("index");

	$EDIT_SETTING = mysql_query("select * from audio_module_setting");
	$ROWS_EDIT_SETTING = mysql_fetch_assoc($EDIT_SETTING);
	
	if(isset($_POST['submit'])){
	
		$add_comments = intval(abs($_POST['add_comments']));
		$player_type = intval(abs($_POST['player_type']));
		$left_menu = intval(abs($_POST['left_menu']));
		$right_menu = intval(abs($_POST['right_menu']));
	
		$Edit = mysql_query("update audio_module_setting set 
		right_menu='$right_menu' , 
		left_menu='$left_menu' ,
		add_comments='$add_comments' ,
		player_type='$player_type' 
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
    <td width="20%">تفعيل إضافة التعليقات</td>
    <td><select name="add_comments">
			<option value="1"'; if($ROWS_EDIT_SETTING['add_comments'] == 1){ $table .= ' selected="selected"'; } $table .= ' selected="selected">لا</option>
			<option value="0"'; if($ROWS_EDIT_SETTING['add_comments'] == 0){ $table .= ' selected="selected"'; } $table .= ' >نعم</option>
		</select>
	</td>
  </tr>
  <tr>
    <td width="20%">نوع المشغل</td>
    <td><select name="player_type">
			<option value="0"'; if($ROWS_EDIT_SETTING['player_type'] == 0){ $table .= ' selected="selected"'; } $table .= '>مشغل فلاش</option>
			<option value="1"'; if($ROWS_EDIT_SETTING['palyer_type'] == 1){ $table .= ' selected="selected"'; } $table .= '>مشغل HTML5</option>
		</select>
	</td>
  </tr>
  <tr>
    <td width="20%">عرض القائمة اليمين</td>
    <td><select name="right_menu">
		<option value="1"'; if($ROWS_EDIT_SETTING['right_menu'] == 1){ $table .= ' selected="selected"'; } $table .= '>لا</option>
		<option value="0"'; if($ROWS_EDIT_SETTING['right_menu'] == 0){ $table .= ' selected="selected"'; } $table .= '>نعم</option>
		</select>
	</td>
  </tr>
 <tr>
    <td width="20%">عرض القائمة اليسار</td>
    <td><select name="left_menu">
		<option value="1"'; if($ROWS_EDIT_SETTING['left_menu'] == 1){ $table .= ' selected="selected"'; } $table .= '>لا</option>
		<option value="0"'; if($ROWS_EDIT_SETTING['left_menu'] == 0){ $table .= ' selected="selected"'; } $table .= '>نعم</option>
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