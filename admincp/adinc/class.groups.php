<?php

	class ControlGroups{
	
		public $stapleGroups;
		
		public function __construct(){
		
			$this->stapleGroups = array(1 , 2 , 3 , 4 , 5);
		}
		
		public function addGroup(){
		
			global $db , $adinc;
			
			if(isset($_POST['addgroup'])){
			
				// values
				$group_name 			  = addslashes($_POST['group_name']);
				$group_blocked 	  		  = _safeInt($_POST['group_blocked']);
				$group_allow_signup 	  = _safeInt($_POST['group_allow_signup']);
				$group_allow_post 		  = _safeInt($_POST['group_allow_post']);
				$group_allow_adminpanel   = _safeInt($_POST['group_allow_adminpanel']);
				$group_allow_ap_themes    = _safeInt($_POST['group_allow_ap_themes']);
				$group_allow_ap_setting   = _safeInt($_POST['group_allow_ap_setting']);
				$group_allow_ap_plugins   = _safeInt($_POST['group_allow_ap_plugins']);
				$group_allow_ap_modules   = _safeInt($_POST['group_allow_ap_modules']);
				$group_allow_ap_languages = _safeInt($_POST['group_allow_ap_languages']);
				$group_allow_ap_accounts  = _safeInt($_POST['group_allow_ap_accounts']);
				$group_allow_ap_menus 	  = _safeInt($_POST['group_allow_ap_menus']);
				$group_allow_ap_pages 	  = _safeInt($_POST['group_allow_ap_pages']);
				$group_allow_ap_security  = _safeInt($_POST['group_allow_ap_security']);
				$group_allow_ap_groups    = _safeInt($_POST['group_allow_ap_groups']);

				// check If Empty
				if(empty($group_name)) {
					
					$adinc->redAlert($adinc->_lang("ERROR_EMPTY_INPUT")); 
					
				}else{
				
					$insert = $db->query("insert into " . PFX . "groups values ('' ,    '$group_name' , 
																						'$group_blocked' ,
																						'$group_allow_signup' , 
																						'$group_allow_post' , 
																						'$group_allow_adminpanel' ,
																						'$group_allow_ap_themes' ,
																						'$group_allow_ap_setting' , 
																						'$group_allow_ap_plugins' , 
																						'$group_allow_ap_modules' , 
																						'$group_allow_ap_languages' , 
																						'$group_allow_ap_accounts' , 
																						'$group_allow_ap_menus' , 
																						'$group_allow_ap_pages' , 
																						'$group_allow_ap_secutriy' ,
																						'$group_allow_ap_groups')");	
				
					if($insert){
					
						$adinc->greenAlert($adinc->_lang("SAVE_DONE"));
						$adinc->location("groups.php");
							
					}else{

						$adinc->redAlert($adinc->_lang("ERROR_ACTION"));
						
					}

				}			

			} // end isset (if)
			
			$form  = "<script type=\"text/javascript\">\nfunction access(){
						var name = $(\"#group_name\").val();
							name.replace(/ /gi , \"\");
						
						if(name == \"\"){
							$(\"#alert_name\").html('<span class=\"red_alert\">". $adinc->_lang("ERROR_EMPTY_INPUT") . "</span>').fadeIn(\"slow\");
							return false;
						}	
						}\n</script>";
			// start form
			$form .= "<form method=\"post\">\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
			// Group Name
			$form .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_GROUPS_NAME") . "</td>\n
					  \t<td><input type=\"text\" id=\"group_name\" name=\"group_name\" /> <span id=\"alert_name\" style=\"display:none;\"></span></td>
		 			  \n</tr>\n";	
					  
			// Group Blocked
			$form .= $this->selectRow("group_blocked" 			, $adinc->_lang("CONTROL_GROUPS_BLOCKED"));
			// Allow Sign up
			$form .= $this->selectRow("group_allow_signup" 		, $adinc->_lang("CONTROL_GROUPS_ALLOW_SIGNUP"));
			// Allow Post & Share
			$form .= $this->selectRow("group_allow_post" 		, $adinc->_lang("CONTROL_GROUPS_ALLOW_POST"));
			// Allow Adminpanel
			$form .= $this->selectRow("group_allow_adminpanel" 	, $adinc->_lang("CONTROL_GROUPS_ALLOW_ADMINPANEL") , '0');
			// Can Mange Templates
			$form .= $this->selectRow("group_allow_ap_themes" 	, $adinc->_lang("CONTROL_GROUPS_MANGE_TEMPLATES") , '0');
			// Can Mange Setting
			$form .= $this->selectRow("group_allow_ap_setting" 	, $adinc->_lang("CONTROL_GROUPS_MANGE_SETTING") , '0');
			// Can Mange Plugins
			$form .= $this->selectRow("group_allow_ap_plugins" 	, $adinc->_lang("CONTROL_GROUPS_MANGE_PLUGINS") , '0');
			// Can Mange Modules
			$form .= $this->selectRow("group_allow_ap_modules" 	, $adinc->_lang("CONTROL_GROUPS_MANGE_MODULES") , '0');
			// Can Mange Languages
			$form .= $this->selectRow("group_allow_ap_languages", $adinc->_lang("CONTROL_GROUPS_MANGE_LANGUAGES") , '0');
			// Can Mange Accounts
			$form .= $this->selectRow("group_allow_ap_accounts" , $adinc->_lang("CONTROL_GROUPS_MANGE_ACCOUNTS") , '0');
			// Can Mange Menus
			$form .= $this->selectRow("group_allow_ap_menus"	, $adinc->_lang("CONTROL_GROUPS_MANGE_MENUS") , '0');
			// Can Mange Pages
			$form .= $this->selectRow("group_allow_ap_pages" 	, $adinc->_lang("CONTROL_GROUPS_MANGE_PAGES") , '0');
			// Can Mange Security
			$form .= $this->selectRow("group_allow_ap_security" , $adinc->_lang("CONTROL_GROUPS_MANGE_SECUTRIY") , '0');
			// Can Mange Groups
			$form .= $this->selectRow("group_allow_ap_groups" 	, $adinc->_lang("CONTROL_GROUPS_MANGE_GROUPS") , '0');
			// end form
			$form .= "<tr>
			\r<td>&nbsp;</td>
			\t<td><input type=\"submit\" name=\"addgroup\" onclick=\"return access();\" value=\"". $adinc->_lang("SAVE") . "\" /></td>
		 	</tr>";
			$form .= "</table>\n</form>\n";
			// display Form	  
		    $adinc->getMenu($adinc->_lang("CONTROL_GROUPS_ADD") , $form);
		
		} # end addGroup function

		public function Management(){
		
			global $adinc , $db;
			
			$groups = $db->query("select * from " . PFX . "groups");
			
			$html = "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$html .= "<tr>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_GROUPS_NAME") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_GROUPS_TOTAL_MEMBERS") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_GROUPS_CONVERT_TO") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("EDIT") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("DELETE") . "</td>\n
			</tr>\n
			";				
			
			while($group = $db->rows($groups)){
			
				$html .= "<tr>\n
				<td class='td_row'>$group[group_name]</td>\n
				<td class='td_row'>" . $db->getfastTotal("select account_id from " . PFX . "accounts where account_groupid=" . $group['group_id']) . "</td>\n
				<td class='td_row'><a href=\"groups.php?action=merge&groupid=$group[group_id]\">" . $adinc->_lang("CONTROL_GROUPS_CONVERT_TO") . "</a></td>\n
				<td class='td_row'><a href=\"groups.php?action=editgroup&groupid=$group[group_id]\"><img src=\"icons/edit.png\" border=\"0\" /></a></td>\n
				<td class='td_row'><a onclick=\"if(window.confirm('". $adinc->_lang("ALERT_CONFIRM") ."') == false){return false;}\" href=\"groups.php?action=deletegroup&groupid=$group[group_id]\"><img src=\"icons/remove.png\" border=\"0\" /></a></td>\n
				</tr>\n
				";			
			
			}
			
			$html .= "</table>\n";
			$adinc->getMenu($adinc->_lang("CONTROL_GROUPS_MANGE") , $html);
		
		} # end Management function

		public function editGroup(){
		
			global $db , $adinc;
			
			$group_id = _safeInt($_REQUEST['groupid']);				
		
			if($group_id == 0){
			
				$adinc->redAlert($adinc->_lang("CONTROL_GROUPS_NO_GROUP_EXISTS"));
				$adinc->closePage();
				$adinc->stop();	
			}
			
			$query = $db->query("select * from " . PFX . "groups where group_id=" . $group_id);
			
			if(!$db->total($query)){
			
				$adinc->redAlert($adinc->_lang("CONTROL_GROUPS_NO_GROUP_EXISTS"));
				$adinc->closePage();
				$adinc->stop();		
			}
			
			$rows  = $db->rows($query);

			if(isset($_POST['editgroup'])){
				
				// values
				$group_name 			  = addslashes($_POST['group_name']);
				$group_blocked 	  		  = _safeInt($_POST['group_blocked']);
				$group_allow_signup 	  = _safeInt($_POST['group_allow_signup']);
				$group_allow_post 		  = _safeInt($_POST['group_allow_post']);
				$group_allow_adminpanel   = _safeInt($_POST['group_allow_adminpanel']);
				$group_allow_ap_themes    = _safeInt($_POST['group_allow_ap_themes']);
				$group_allow_ap_setting   = _safeInt($_POST['group_allow_ap_setting']);
				$group_allow_ap_plugins   = _safeInt($_POST['group_allow_ap_plugins']);
				$group_allow_ap_modules   = _safeInt($_POST['group_allow_ap_modules']);
				$group_allow_ap_languages = _safeInt($_POST['group_allow_ap_languages']);
				$group_allow_ap_accounts  = _safeInt($_POST['group_allow_ap_accounts']);
				$group_allow_ap_menus 	  = _safeInt($_POST['group_allow_ap_menus']);
				$group_allow_ap_pages 	  = _safeInt($_POST['group_allow_ap_pages']);
				$group_allow_ap_security  = _safeInt($_POST['group_allow_ap_security']);
				$group_allow_ap_groups    = _safeInt($_POST['group_allow_ap_groups']);

				// check If Empty
				if(empty($group_name)) {
					
					$adinc->redAlert($adinc->_lang("ERROR_EMPTY_INPUT")); 
					
				}else{
					
					$edit = $db->query("update " . PFX . "groups set 
											group_name='$group_name' , 
											group_blocked='$group_blocked' ,
											group_allow_signup='$group_allow_signup' ,
											group_allow_post='$group_allow_post' , 
											group_allow_adminpanel='$group_allow_adminpanel' , 
											group_ap_themes='$group_allow_ap_themes' , 
											group_ap_setting='$group_allow_ap_setting' , 
											group_ap_plugins='$group_allow_ap_plugins' , 
											group_ap_modules='$group_allow_ap_modules' ,
											group_ap_languages='$group_allow_ap_languages' , 
											group_ap_accounts='$group_allow_ap_accounts' , 
											group_ap_menus='$group_allow_ap_menus' , 
											group_ap_pages='$group_allow_ap_pages' , 
											group_ap_security='$group_allow_ap_security' 
											where group_id='$group_id'");	
											
					if($edit){
					
						$adinc->greenAlert($adinc->_lang("EDIT_DONE"));
						$adinc->location("groups.php");					
					}else{
					
						$adinc->redAlert($adinc->_lang("ERROR_ACTION"));	
					}
				}
				
			}
			
			$form  = "<script type=\"text/javascript\">\nfunction access(){
						var name = $(\"#group_name\").val();
							name.replace(/ /gi , \"\");
						
						if(name == \"\"){
							$(\"#alert_name\").html('<span class=\"red_alert\">". $adinc->_lang("ERROR_EMPTY_INPUT") . "</span>').fadeIn(\"slow\");
							return false;
						}	
						}\n</script>";
			// start form
			$form .= "<form method=\"post\">\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
			// Group Name
			$form .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_GROUPS_NAME") . "</td>\n
					  \t<td><input type=\"text\" id=\"group_name\" name=\"group_name\" value=\"$rows[group_name]\" /> <span id=\"alert_name\" style=\"display:none;\"></span></td>
		 			  \n</tr>\n";	
					 					  
			// Group Blocked
			$form .= $this->selectRow("group_blocked" 			 , $adinc->_lang("CONTROL_GROUPS_BLOCKED") 			, $rows['group_blocked']);
			// Allow Sign up
			$form .= $this->selectRow("group_allow_signup"  	 , $adinc->_lang("CONTROL_GROUPS_ALLOW_SIGNUP") 	, $rows['group_allow_signup']);
			// Allow Post & Share
			$form .= $this->selectRow("group_allow_post" 		 , $adinc->_lang("CONTROL_GROUPS_ALLOW_POST")   	, $rows['group_allow_post']);
			// Allow Adminpanel
			$form .= $this->selectRow("group_allow_adminpanel"   , $adinc->_lang("CONTROL_GROUPS_ALLOW_ADMINPANEL") , $rows['group_allow_adminpanel']);
			// Can Mange Templates
			$form .= $this->selectRow("group_allow_ap_themes"    , $adinc->_lang("CONTROL_GROUPS_MANGE_TEMPLATES") 	, $rows['group_ap_themes']);
			// Can Mange Setting
			$form .= $this->selectRow("group_allow_ap_setting"   , $adinc->_lang("CONTROL_GROUPS_MANGE_SETTING") 	, $rows['group_ap_setting']);
			// Can Mange Plugins
			$form .= $this->selectRow("group_allow_ap_plugins"   , $adinc->_lang("CONTROL_GROUPS_MANGE_PLUGINS") 	, $rows['group_ap_plugins']);
			// Can Mange Modules
			$form .= $this->selectRow("group_allow_ap_modules"   , $adinc->_lang("CONTROL_GROUPS_MANGE_MODULES") 	, $rows['group_ap_modules']);
			// Can Mange Languages
			$form .= $this->selectRow("group_allow_ap_languages" , $adinc->_lang("CONTROL_GROUPS_MANGE_LANGUAGES")  , $rows['group_ap_languages']);
			// Can Mange Accounts
			$form .= $this->selectRow("group_allow_ap_accounts"  , $adinc->_lang("CONTROL_GROUPS_MANGE_ACCOUNTS")   , $rows['group_ap_accounts']);
			// Can Mange Menus
			$form .= $this->selectRow("group_allow_ap_menus" 	 , $adinc->_lang("CONTROL_GROUPS_MANGE_MENUS") 		, $rows['group_ap_menus']);
			// Can Mange Pages
			$form .= $this->selectRow("group_allow_ap_pages" 	 , $adinc->_lang("CONTROL_GROUPS_MANGE_PAGES") 		, $rows['group_ap_pages']);
			// Can Mange Security
			$form .= $this->selectRow("group_allow_ap_security"  , $adinc->_lang("CONTROL_GROUPS_MANGE_SECURITY")   , $rows['group_ap_security']);
			// Can Mange Groups
			$form .= $this->selectRow("group_allow_ap_groups" 	 , $adinc->_lang("CONTROL_GROUPS_MANGE_GROUPS") 	, $rows['group_ap_groups']);
			// end form
			$form .= "<tr>
			\r<td>&nbsp;</td>
			\t<td><input type=\"submit\" name=\"editgroup\" onclick=\"return access();\" value=\"". $adinc->_lang("SAVE") . "\" /></td>
		 	</tr>";
			$form .= "</table>\n</form>\n";
			// display Form	  
		    $adinc->getMenu($adinc->_lang("CONTROL_GROUPS_EDIT") , $form);
			
		}
		
		public function deleteGroup(){
		
			global $db , $adinc;
			
			$group_id = _safeInt($_REQUEST['groupid']);				
		
			if($group_id == 0){
			
				$adinc->redAlert($adinc->_lang("CONTROL_GROUPS_NO_GROUP_EXISTS"));
				$adinc->closePage();
				$adinc->stop();	
			}
						
			if($db->getfastTotal("select group_id from " . PFX . "groups where group_id=" . $group_id) == 0){
			
				$adinc->redAlert($adinc->_lang("CONTROL_GROUPS_NO_GROUP_EXISTS"));
				$adinc->closePage();
				$adinc->stop();		
			}
						
			if(in_array($group_id , $this->stapleGroups)){
			
				$adinc->redAlert($adinc->_lang("CONTROL_GROUPS_CANT_STAPLE"));
				$adinc->closePage();
				$adinc->stop();						
			}
			
			$delete = $db->query("delete from " . PFX . "groups where group_id=" . $group_id);
			
			if($delete){
			
				$adinc->greenAlert($adinc->_lang("DELETE_DONE"));	
				$adinc->location("groups.php");
				
			}else{
			
				$adinc->redAlert($adinc->_lang("ERROR_ACTION"));	
				$adinc->location("groups.php");
			}
		
		} # end deleteGroup function.
	
		public function mergeGroup(){
		
			global $db , $adinc;
			
			$group_id = _safeInt($_REQUEST['groupid']);				
		
			if($group_id == 0){
			
				$adinc->redAlert($adinc->_lang("CONTROL_GROUPS_NO_GROUP_EXISTS"));
				$adinc->closePage();
				$adinc->stop();	
			}
						
			if($db->getfastTotal("select group_id from " . PFX . "groups where group_id=" . $group_id) == 0){
			
				$adinc->redAlert($adinc->_lang("CONTROL_GROUPS_NO_GROUP_EXISTS"));
				$adinc->closePage();
				$adinc->stop();		
			}
			
			if(isset($_POST['merge_submit'])){
				
				$merge_to = _safeInt($_POST['merge_to']);
				
				$merge    = $db->query("update " . PFX . "accounts set account_groupid='$merge_to' where account_groupid='$group_id'");
				
				if($merge){
				
					$adinc->greenAlert($adinc->_lang("CONTROL_GROUPS_MERGE_DONE"));	
					$adinc->location("groups.php");
				
				}else{
			
					$adinc->redAlert($adinc->_lang("ERROR_ACTION"));	
					$adinc->location("groups.php");
				}
				
			} # end isset (if)
			
			$groups = $db->query("select group_id , group_name from " . PFX . "groups where group_id!=" . $group_id);
			
			// start form
			$form  = "<form method=\"post\">\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";								
			$form .= "<tr>
					  <td width=\"20%\">".$adinc->_lang("CONTROL_GROUPS_SELECT_GROUP")."</td>
					  <td><select name=\"merge_to\">
					  <option value=\"\"></option>";
					  
			while($group = $db->rows($groups)){
			
				$form .= "<option value=\"$group[group_id]\">" . $group['group_name'] . "</option>";	
			
			}
			
			$form .= "</select>\n";
			$form .= "<tr>
			\r<td>&nbsp;</td>
			\t<td><input type=\"submit\" name=\"merge_submit\" onclick=\"return access();\" value=\"". $adinc->_lang("SAVE") . "\" /></td>
		 	</tr>";
			$form .= "<td>\n</tr>\n</table>\n</form>";
					  
			$adinc->getMenu($adinc->_lang("CONTROL_GROUPS_CONVERT_TO") , $form);
		}
		
		public function selectRow($select_name , $option_name , $option_value=''){
			
			global $adinc;
						
			$form = "<tr>\n
				\t<td>$option_name</td>
				\t<td>
				\t<select name=\"$select_name\" id=\"$select_name\">
				\t\t<option value=\"1\""; if($option_value == '1'){ $form .= " selected=\"selected\""; }$form .= ">". $adinc->_lang("YES") . "</option>
				\t\t<option value=\"0\""; if($option_value == '0'){ $form .= " selected=\"selected\""; }$form .= ">". $adinc->_lang("NO")  . "</option>
				\t</select>
				\t</td>
	  			\t</tr>";
		
			return($form);
		
		} # end selectRow function.
		
	} # end Class
?>