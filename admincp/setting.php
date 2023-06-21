<?php

	session_start();

	include("../includes/config.php");

	include("adinc/class.adinc.php");
	
	include("../includes/class.db.php");
	
	$action = strip_tags(trim($_REQUEST['action']));

	$adinc->getStyle();
		
	$adinc->getRight('setting');
	
	$adinc->getNavbar(array(
	
		' معلومات الموقع ' => 'setting.php?action=core' ,
		' معلومات المدير ' => 'setting.php?action=login' ,
		' إغلاق و فتح الموقع ' => 'setting.php?action=dor' ,
		' التحكم بصيغة التاريخ و الوقت ' => 'setting.php?action=date' ,

	));
	
	if($action == "core"){
	
		$adinc->getJs("editcore");
		
		$core_setting = $db->getfastRows("select title , keywords , meta , style , siteurl , report_bymail , site_language , admin_language from core");
		
		$languagesSite = $db->query("select * from languages");
		$languagesAdmin = $db->query("select * from languages");
		
		$table = '
  <div id="submit_alert" style="display:none;"></div>		
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">'.$adinc->_lang("SETTING_SITE_TITLE").'</td>
    <td id="td_title"><input type="text" id="title_core" value="'.$core_setting['title'].'" /></td>
  </tr>
 <tr>
    <td width="20%">'.$adinc->_lang("SETTING_SITE_URL").'</td>
    <td id="td_siteurl"><input type="text" id="siteurl_core" dir="ltr" value="'.$core_setting['siteurl'].'" /></td>
  </tr>
  <tr>
    <td>'.$adinc->_lang("SETTING_SITE_META").'</td>
    <td id="td_meta"><input type="text" id="meta_core" value="'.$core_setting['meta'].'" /></td>
  </tr>
  <tr>
    <td>'.$adinc->_lang("SETTING_KEYWORDS").'</td>
    <td id="td_keywords"><input type="text" id="keywords_core" value="'.$core_setting['keywords'].'" /></td>
  </tr>
 <tr>
    <td width="20%">'.$adinc->_lang("SETTING_REPORT_BY_EMAIL").'</td>
    <td id="td_report">
	<select id="report_core">
	<option value="1"'; if($core_setting['report_bymail'] == 1){ $table.= ' selected="selected" ';} $table .= '>'.$adinc->_lang("YES").'</option>
	<option value="0"'; if($core_setting['report_bymail'] == 0){ $table.= ' selected="selected" ';} $table .= '>'.$adinc->_lang("NO").'</option>
	</select>
	</td>
  </tr>
<tr>
    <td width="20%">'.$adinc->_lang("SETTING_SITE_LANGUAGE").'</td>
    <td id="td_site_language">
	<select id="site_language_core">';
		while($rlang = $db->rows($languagesSite)){ 
			
			$table .= '<option value="'.$rlang['id'].'"'; if($rlang['id'] == $core_setting['site_language']){ $table.= ' selected="selected" ';} $table .= '>'.$rlang['language_name'].'</option>';
		
		}
	$table .= '</select>
	</td>
  </tr>
<tr>
    <td width="20%">'.$adinc->_lang("SETTING_ADMIN_LANGUAGE").'</td>
    <td id="td_admin_language">
	<select id="admin_language_core">';
		while($rlang = $db->rows($languagesAdmin)){ 
			
			$table .= '<option value="'.$rlang['id'].'"'; if($core_setting['admin_language'] == $rlang['id']){ $table.= ' selected="selected" ';} $table .= '>'.$rlang['language_name'].'</option>';
		
		}
	$table .= '</select>
	</td>
  </tr>';  $table .= '<tr>
    <td>&nbsp;</td>
    <td><input type="button" id="submit_core" value="'.$adinc->_lang("SAVE").'" /></td>
  </tr>
</table>
';	
		$adinc->getMenu($adinc->_lang("SETTING_CORE") , $table);
		
	}
	
	if($action == "login"){
	
		$adinc->getJs("editlogin");
		
		$core_setting = $db->getfastRows("select username , password , admin_email from core");
		
		$table = '
  <div id="submit_alert" style="display:none;"></div>		
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">'.$adinc->_lang("SETTING_ADMIN_EMAIL").'</td>
    <td id="td_email"><input type="text" id="user_email" value="'.$core_setting['admin_email'].'" />('.$adinc->_lang("SETTING_ADMIN_EMAIL_ALERT").')</td>
  </tr>
  <tr>
    <td width="20%">'.$adinc->_lang("SETTING_USERNAME").'</td>
    <td id="td_name"><input type="text" id="user_login" value="'.$core_setting['username'].'" /></td>
  </tr>
  <tr>
    <td>'.$adinc->_lang("SETTING_PASSWORD").'</td>
    <td><input type="text" id="pass_login" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" id="submit_login" value="'.$adinc->_lang("SAVE").'" /></td>
  </tr>
</table>
';	
		$adinc->getMenu($adinc->_lang("SETTING_LOGIN") , $table);
		
	}

	if($action == "dor"){
	
		$adinc->getJs("editdor");
		
		$dor_setting = $db->getfastRows("select close_do , close_text from core");
		
		$table = '
  <div id="submit_alert" style="display:none;"></div>		
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">'.$adinc->_lang("SETTING_IS_CLOSED").'</td>
    <td>
	<select id="close_do">
	<option value="0"'; if($dor_setting['close_do'] == 0){ $table.= ' selected="selected" ';} $table .= '>'.$adinc->_lang("OPENED").'</option>
	<option value="1"'; if($dor_setting['close_do'] == 1){ $table.= ' selected="selected" ';} $table .= '>'.$adinc->_lang("CLOSED").'</option>
	</select>
	</td>
  </tr>
  <tr>
    <td>'.$adinc->_lang("SETTING_CLOSED_MESSAGE").'</td>
    <td id="td_closetext"><textarea id="close_text">'.$dor_setting['close_text'].'</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" id="submit_dor" value="'.$adinc->_lang("SAVE").'" /></td>
  </tr>
</table>
';	
		$adinc->getMenu($adinc->_lang("SETTING_CLOSED") , $table);
		
	}
	
	
	if($action == "date"){
	
		$adinc->getJs("editdate");
		
		$core_setting = $db->getfastRows("select type_date , type_time from core");
		
		$table = '
  <div id="submit_alert" style="display:none;"></div>		
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">'.$adinc->_lang("SETTING_DATE_TYPE").'</td>
    <td id="td_date"><input type="text" id="date_date" value="'.$core_setting['type_date'].'" /></td>
  </tr>
  <tr>
    <td>'.$adinc->_lang("SETTING_TIME_TYPE").'</td>
    <td id="td_time"><input type="text" id="time_date" value="'.$core_setting['type_time'].'" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" id="submit_date" value="'.$adinc->_lang("SAVE").'" /></td>
  </tr>
</table>
';	
		$adinc->getMenu($adinc->_lang("SETTING_DATE") , $table);
		
	}
	
	$adinc->closePage();
		
?>
