<?php

	ob_start();
	
	session_start();

	include("../includes/config.php");

	include("../includes/class.db.php");

	include("adinc/class.adinc.php");

	$adinc->getStyle();
			
	$action = strip_tags(trim($_REQUEST['action']));
	
	$area   = intval(abs($_REQUEST['area']));
	
	$setting = $db->getfastRows("select admin_text , version_name , version_code , last_update , new_updates , new_bugs from " . PFX . "core");
	
	$totalLogs = $db->getfastTotal("select id from " . PFX . "logs");
		
	$totalBlocked = $db->getfastTotal("select id from " . PFX . "blocks_ip");

	$totalAutoBlocked = $db->getfastTotal("select id from " . PFX . "blocks_ip where state=1");
	
	$totalReports = ($totalLogs+$totalAutoBlocked);
	
	$securityStata = ($totalLogs+$totalAutoBlocked)>0?ceil(($totalLogs+($totalBlocked-$totalAutoBlocked))/($totalReports)*100):0;
	
	// 	$securityStata = ($totalLogs+$totalAutoBlocked)>0?ceil((($totalBlocked+$totalAutoBlocked))/($totalReports)*100):0;

		if($securityStata < 20){
		
			$securityAlert = "<div style=\"color:red;font-weight:bold;font-size:14px;\">سئ جداً</div>";
	
		}else if($securityStata < 50 and $securityStata > 20){
		
			$securityAlert = "<div style=\"color:red;\">سئ</div>";
				
		}else if($securityStata < 70 and $securityStata > 50){
			
			$securityAlert = "<div style=\"color:gray;\">لا بأس به</div>";
			
		}else if($securityStata < 80 and $securityStata > 70){
		
			$securityAlert = "<div style=\"color:green;\">جيد .. لا تقلق</div>";
				
		}else if($securityStata < 90 and $securityStata > 80){
		
			$securityAlert = "<div style=\"color:green;\">جيد جداً</div>";
			
		}else{
			
			$securityAlert = "<div style=\"color:green;font-weight:bold;font-size:14px;\">الحالة الأمنية رائعة!!</div>";
			
		}
		
	$adinc->getRight('');
	
	if($setting['new_updates'] != 0) $updates = $setting['new_updates']; else $updates = "لا يوجد";

	if($setting['new_bugs'] != 0) $bugs = $setting['new_bugs']; else $bugs = "لا يوجد";
	
	$table = '<table width="100%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td width="20%">إشعارات الامان</td>
    <td><a href="security.php?action=logs" class="red_link">'.$totalLogs.'</a></td>
  </tr>
  <tr>
    <td width="20%">العناوين المحظورة</td>
    <td><a href="security.php?action=blocked&state=0" class="red_link">'.$totalBlocked.'</a></td>
  </tr>
 <tr>
    <td width="20%">العناوين المحظروة تلقائياً</td>
    <td><a href="security.php?action=blocked&state=1" class="red_link">'.$totalAutoBlocked.'</a></td>
  </tr>
  <tr>
    <td width="20%">مستوى الامان</td>
    <td>'.$securityAlert.'</td>
  </tr>
  <tr>
    <td width="20%">إصدار البرنامج</td>
    <td dir="ltr" align="right"><a href="http://www.elia.net">Elia-CMS ('.$setting['version_name'].')</a></td>
  </tr>
  <tr>
    <td>التحديثات</td>
    <td>'. $updates .'</td>
  </tr>
  <tr>
    <td>الترقيع و الأخطاء</td>
    <td>'. $bugs .'</td>
  </tr>
</table>';

	$adinc->getMenu('الرئيسية' , $table);
	
	$adinc->getJs("editatext");
	
	$form = '        <div align="center">
					 <textarea id="admin_text" rows="5" cols="80%">'.$setting['admin_text'].'</textarea>
					 <div><input type="button" id="submit_admintext" value="تعديل الرسالة" /><span id="submit_alert" style="display:none;"></span></div>
					 </div>
					 
					 ';

	$adinc->getMenu('رسالة المدير' , $form);
	
	print '<center><div style="color:#666; font-family:tahoma; font-size:12px; padding:15px;">powerd by : Elia CMS</div></center>';
		
	$adinc->closePage();
	
?>
