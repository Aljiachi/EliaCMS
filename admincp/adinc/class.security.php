<?php

	class securityManager{
		
		public function getSetting(){
		
			global $db , $adinc;
			
			$adinc->getJs("security");
		
			$core_setting = $db->getfastRows("select request_safe , post_safe , unsafe_functions , auto_block , auto_block_time , links_in_comments from " . PFX . "core");
		
			$table = '
			  <div id="submit_alert" style="display:none;"></div>		
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="20%">عبارات \'GET\' الممنوعة</td>
				<td id="td_request_safe"><textarea id="request_safe" rows="5">'.str_replace("|" , "\n" , $core_setting['request_safe']).'</textarea></td>
			  </tr>
			 <tr>
				<td width="20%">عبارات \'POST\' الممنوعة</td>
				<td id="td_post_safe"><textarea id="post_safe" rows="5">'.str_replace("|" , "\n" , $core_setting['post_safe']).'</textarea></td>
			  </tr>
			  <tr>
				<td width="20%">الدوال الممنوعة</td>
				<td id="td_unsafe_functions"><textarea id="unsafe_functions" rows="5">'.str_replace("|" , "\n" , $core_setting['unsafe_functions']).'</textarea></td>
			  </tr>
			  	 <tr>
				<td width="20%">تفعيل الحظر التلقائي</td>
				<td id="td_auto_block">
				<select id="auto_block">
				<option value="1"'; if($core_setting['auto_block'] == 1){ $table.= ' selected="selected" ';} $table .= '>نعم</option>
				<option value="0"'; if($core_setting['auto_block'] == 0){ $table.= ' selected="selected" ';} $table .= '>لا</option>
				</select>
				</td>
			  </tr>
			  <tr>
				<td>المدة الزمنية للحظر التلقائي (بالدقائق)</td>
				<td id="td_auto_block_time"><input type="text" id="auto_block_time" value="'.$core_setting['auto_block_time'].'" /></td>
			  </tr>
			  	 <tr>
				<td width="20%">تفعيل الروابط في التعليقات</td>
				<td id="td_links_in_comments">
				<select id="links_in_comments">
				<option value="1"'; if($core_setting['links_in_comments'] == 1){ $table.= ' selected="selected" ';} $table .= '>نعم</option>
				<option value="0"'; if($core_setting['links_in_comments'] == 0){ $table.= ' selected="selected" ';} $table .= '>لا</option>
				</select>
				</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input type="button" id="submit_core" value="تعديل" /></td>
			  </tr>
			</table>
			';	
		
			$adinc->getMenu('إعدادات الحماية' , $table);		
			
		}
	
		public function getLogs(){
		
			global $db , $config , $adinc;
			
			$objManager = new objectsManager();
			
			$Paginator  = $objManager->getPaginatorObject();
			
			$Paginator->SetPerPage(25);	
			
			$Paginator->setPageUrl("security.php?action=logs");
			
			$Logs = $Paginator->CreatePaginator("select * from " . PFX . "logs order by id desc");
					
			$t = "<form method=\"post\" action=\"security.php?action=multiaction_logs\">\n";
			
			$t.= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$t .= "<tr>\n
			<td class='td_title' width=\"200\">IP</td>\n
			<td class='td_title'>الرسالة</td>\n
			<td class='td_title' align='center' width=\"70\">حذف</td>\n
			<td class='td_title' align='center'><input type=\"checkbox\" name=\"check_all\" onclick=\"checkAll(this.form)\" /></td>
			</tr>\n
			";

		while($rows = $db->rows($Logs) ) {
			
		 $t .= "<tr id=\"log-$rows[id]\">\n
				<td class='td_row'><div>$rows[ip]</div>
				<div style=\"color:#666;\">نوع المحاولة : ".$this->getActionType($rows['action'])." <br /> ".date("Y/m/d - h:i" , $rows['time'])."</div>
				</td>\n
				"; 

				$t .= "<td class='td_row report_request' dir=\"ltr\"><a href='hooks.php?action=admin&hook=$rows[id]'>".str_replace("," , "<br />" , $rows['request'])."</a></td>\n";

				$t .= "<td class='td_row' align=\"center\"><a href='#delete' onclick='deleteLog($rows[id])'><img src='icons/remove.png' border='0' /></a></td>\n"; 

				$t .= '<td class=\'td_row\' align="center"><input type="checkbox" name="check[]" value="'.$rows['id'].'" /></td>';
				
				$t .= "</tr>\n";	
			}
						
			$t .= "</table>\n";
			
			$t .= '<div class="tools_bar"><div style="float:left;">'. $Paginator->GetPagination().'</div><input type="submit" name="delete_selcted" value="حذف المحدد" /></div>';
						
			$t .= "</form>\n";
			
			$adinc->getMenu('إشعارات الأمان' , $t);

		}
		
	public function getIPS(){
		
			global $db , $config , $adinc;
			
			$objManager = new objectsManager();
			
			$Paginator  = $objManager->getPaginatorObject();
			
			$Paginator->SetPerPage(25);	
			
			$Paginator->setPageUrl("security.php?action=ips");
			
			$Logs = $Paginator->CreatePaginator("select * from " . PFX . "blocks_ip order by id desc");
					
			$t = "<form method=\"post\" action=\"security.php?action=multiaction_ips\">\n";
			
			$t.= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$t .= "<tr>\n
			<td class='td_title'>IP</td>\n
			<td class='td_title' width=\"90\" align=\"center\">رفع الحظر</td>\n
			<td class='td_title' width=\"70\" align=\"center\"><input type=\"checkbox\" name=\"check_all\" onclick=\"checkAll(this.form)\" /></td>
			</tr>\n
			";

		while($rows = $db->rows($Logs) ) {
			
		 $t .= "<tr id=\"ip-$rows[id]\">\n
				<td class='td_row'><div>$rows[ip]</div>
				<div style=\"color:#666;\">".date("Y/m/d - h:i" , $rows['time'])."</div>
				</td>\n
				"; 

				$t .= "<td class='td_row' align=\"center\"><a onclick='deleteIP($rows[id])'><img src='icons/remove.png' border='0' /></a></td>\n"; 

				$t .= '<td class=\'td_row\' align="center"><input type="checkbox" name="check[]" value="'.$rows['id'].'" /></td>';
				
				$t .= "</tr>\n";	
			}
						
			$t .= "</table>\n";
			
			$t .= '<div class="tools_bar"><div style="float:left;">'.$Paginator->GetPagination().'</div><input type="submit" name="delete_selcted" value="رفع الحظر" /></div>';
						
			$t .= "</form>\n";
			
			$adinc->getMenu('العناوين المحظورة' , $t);

		}
		
		public function multiActionLogs(){

			global $db , $adinc , $config;
					
			$check = $_POST['check'];
		
			if(count($check) == 0){

				$adinc->redAlert('لم تحدد أي إشعار');	
			
				return false;
			}
			
			$impad = implode(",",$check);	
					
			if(isset($_POST['delete_selcted'])){
									
				$MultiAction = $db->query("DELETE FROM " . PFX . "logs WHERE `id` in (".$impad.")");
	
				if($MultiAction){
					
					$adinc->greenAlert('تم الحفظ بنجاح');
			
					$adinc->location('security.php?action=logs');
				}
				
			}
			
		}
		
	
		public function multiActionIPS(){

			global $db , $adinc , $config;
					
			$check = $_POST['check'];
		
			if(count($check) == 0){

				$adinc->redAlert('تحقق من العملية');	
			
				return false;
			}
			
			$impad = implode(",",$check);	
					
			if(isset($_POST['delete_selcted'])){
									
				$MultiAction = $db->query("DELETE FROM " . PFX . "blocks_ip WHERE `id` in (".$impad.")");
	
				if($MultiAction){
					
					$adinc->greenAlert('تم الحفظ بنجاح');
			
					$adinc->location('security.php?action=ips');
				}
				
			}
			
		}
		
		public function debugCenter($ppop=false){
		
			global $adinc;
			
			$type = trim($_REQUEST['type']);
			$display = trim($_REQUEST['display']);

			if($ppop == false){
			
				$adinc->getExtraLinks(array(
					' الحالة العامة ' => 'security.php?action=debug' , 
					' اوامر النظام ' => 'security.php?action=debug&type=green' , 
					' إشعارات التنبيه ' => 'security.php?action=debug&type=alert' ,
					' الأخطاء ' => 'security.php?action=debug&type=red'
				));
				
				if($display == "mutliwindow"){

					$adinc->getMenu('مركز المتابعة <div style="float:left;"><a onclick="window.open(\'security.php?action=ppop_debug\',\'\',\'width=700,height=400,scrollbars=yes\')"><img src="icons/window.png" /></a></div>' ,
					 '<div style="float:left;width:40%;"><div id="debugLogsAlerts" style="background:#000;max-height:320px;overflow-y:scroll;">Loading...</div>
					  <div id="debugLogsErrors" style="background:#000;max-height:320px;overflow-y:scroll;">Loading...</div></div>
					  <div style="margin-left:40%;"><div id="debugLogsEvents" style="background:#000;max-height:600px;overflow-y:scroll;">Loading...</div></div>
					  ');
					
				}else{
						
					$adinc->getMenu('مركز المتابعة <div style="float:left;"><a onclick="window.open(\'security.php?action=ppop_debug&type='.$type.'\',\'\',\'width=700,height=400,scrollbars=yes\')"><img src="icons/window.png" /></a></div>' , '<div id="debugLogs" style="background:#000;" dir="ltr">Loading...</div>');

				}
				
			}else{
				
				print '<div id="debugLogs" style="background:#000;">Loading...</div>';
			}

			if($display == "mutliwindow"){
	
				print '<script type="text/javascript">
				
					$(document).ready(function(){
						
							setInterval(function(){
								
									$("#debugLogsEvents").load("ajax/security.php?action=debug&type=green");
									$("#debugLogsAlerts").load("ajax/security.php?action=debug&type=alert");
									$("#debugLogsErrors").load("ajax/security.php?action=debug&type=red");
									
								} , 200);
						});
				</script>';
				
			}else{
	
				print '<script type="text/javascript">
				
					$(document).ready(function(){
						
							setInterval(function(){
								
									$("#debugLogs").load("ajax/security.php?action=debug&type='.$type.'");
									
								} , 200);
						});
				</script>';
				
			}
			
		}
		
		private function getActionType($action){
		
			switch($action){
			
				default:$type = "غير معروف"; break;
				case 1: $type = "GET Method"; break;
				case 2: $type = "module path Error"; break;
				case 3: $type = "mysql Error"; break;	
				case 4: $type = "POST Method"; break;
				case 5: $type = "PHP Log"; break;
				case 6: $type = "User Error"; break;
				case 7: $type = "DOS Log"; break;
				
			}
			
			return $type;
		}	
	}
?>