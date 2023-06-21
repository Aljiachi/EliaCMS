<?php

	class ControlAccounts{
	
		private $groups , $garray;
		
		public function __construct(){
		
			global $db , $adinc;
			
			$this->groups = $db->query("select * from " . PFX . "groups");	
			
			$this->garray = array();
			
			while($rgroup = $db->rows($this->groups)){
			
				$this->garray[] = array('id' => $rgroup['group_id'] , 'name' => $rgroup['group_name']);	
			}
			
			define("LANGUAGE_CODE" , interfaceLanguage());

		}
		
		public function addaccount(){
		
			global $db , $adinc , $config;
						
			// append Language to js Scripts
			$adinc->jsLanguage(array('ERROR_EMPTY' => $adinc->_lang("ERROR_EMPTY_INPUT")));
							
			$adinc->getJs("accounts");

			if(isset($_POST['addaccount'])){
				
				$nicknamePOST 		= _safeString($_POST['account_nickname']);
				$groupidPOST 		= _safeInt($_POST['account_groupid']);
				$genderPOST 		= _safeInt($_POST['account_gender']);
				$locationPOST 		= _safeString($_POST['account_location']);
				$emailPOST 			= _safeString($_POST['account_email']);
				$passwordPOST 		= $_POST['account_password'];
				
				$passwordSALT 		= substr(base64_encode(rand(11111,99999)) , 0 , 7);
				$passwordORGINAL 	= $config->enpassword($passwordPOST . md5($passwordSALT));
				
				if(!filter_var($emailPOST, FILTER_VALIDATE_EMAIL)){
				
					$adinc->redAlert(_lang("ERROR_EMAIL_VALIDATE" , LANGUAGE_CODE));
					$adinc->closePage();
					$adinc->stop();
				}
				
				if(strlen($passwordPOST) < 7){
				
					$adinc->redAlert(_lang("ERROR_PASSWORD_SHORT" , LANGUAGE_CODE));
					$adinc->closePage();
					$adinc->stop();
				}
				
				if(!empty($nicknamePOST) and !empty($emailPOST)){
					
					if($db->getfastTotal("select account_id from " . PFX . "accounts where account_email='$emailPOST'") == 0){
					
						$insert = $db->query("insert into " . PFX . "accounts (account_nickname   , 
																			   account_email 	  , 
																			   account_password   , 
																			   account_salt 	  , 
																			   account_groupid    , 
																			   account_gender 	  , 
																			   account_location
																			   ) values (
																			   '$nicknamePOST' 	  , 
																			   '$emailPOST' 	  , 
																			   '$passwordORGINAL' , 
																			   '$passwordSALT'	  , 
																			   '$groupidPOST'	  , 
																			   '$genderPOST' 	  , 
																			   '$locationPOST'	   
																			   )");		
					
						if($insert){
						
							$adinc->greenAlert($adinc->_lang("CONTROL_ACCOUNTS_ADD_DONE"));
							$adinc->location("accounts.php?action=profile&accountid=" . $db->insertId() );
						}
						
					}
				}
			
			}
			
			// start form
			$form .= "<form method=\"post\">\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
			// Accounts
			$form .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_NICKNAME") . "</td>\n
					  \t<td><input type=\"text\" id=\"account_nickname\" name=\"account_nickname\" /> <span id=\"alert_nickname\" style=\"display:none;\"></span></td>
		 			  \n</tr>\n";	
			// Accounts
			$form .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_EMAIL") . "</td>\n
					  \t<td><input type=\"text\" id=\"account_email\" name=\"account_email\" /> <span id=\"alert_email\" style=\"display:none;\"></span></td>
		 			  \n</tr>\n";	
			// Accounts
			$form .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_PASSWORD") . "</td>\n
					  \t<td><input type=\"text\" id=\"account_password\" name=\"account_password\" /> <span id=\"alert_password\" style=\"display:none;\"></span></td>
		 			  \n</tr>\n";	
					  
			// Accounts
			$form .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_GENDER") . "</td>\n
					  \t<td><select name='account_location'>\n
					  <option value='1'>"._lang("PROFILE_GENDER_MALE" 	,  LANGUAGE_CODE)."</option>
					  <option value='2'>"._lang("PROFILE_GENDER_FEMALE" ,  LANGUAGE_CODE)."</option>
					  </select>\n</td>
		 			  \n</tr>\n";	
					  
			// Accounts
			$form .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_GROUPID") . "</td>\n
					  \t<td><select name='account_groupid'>";
					  
			foreach($this->garray as $group){
				
				$form .= "<option value='$group[id]'";
					
				if($group['id'] == $core_setting['accounts_default_group']) $form .= " selected=\"selected\""; 
					
				$form .= ">$group[name]</option>\n";
								  
				}
				
			$form .= "</select></td>
		 			  \n</tr>\n";		
			
			// Accounts
			$form .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_LOCATION") . "</td>\n
					  \t<td><select name='account_location'>";
					  
			foreach(countryISO() as $country){
			
				$form .= "<option value='$country'>" . _lang("COUNTRY_" . $country , LANGUAGE_CODE) . "</option>\n";
							  
		 	}
					  
			$form .= "</select></td>
		 			  \n</tr>\n";		

			// end form
			$form .= "<tr>
			\r<td>&nbsp;</td>
			\t<td><input type=\"submit\" name=\"addaccount\" onclick=\"return addAccountAccess();\" value=\"". $adinc->_lang("SAVE") . "\" /></td>
		 	</tr>";
			$form .= "</table>\n</form>\n";
								  
		    $adinc->getMenu($adinc->_lang("CONTROL_ACCOUNTS_ADDACCOUNT") , $form);
																	
		}
		
		public function Management(){
		
			global $db , $adinc;
					
			$objManager = new objectsManager();
			
			$Paginator  = $objManager->getPaginatorObject();
			
			$Paginator->SetPerPage(25);		
			
			$accounts = $Paginator->CreatePaginator("select * from " . PFX . "accounts order by account_id desc");
						
			$html = "<form method=\"post\" action=\"accounts.php?action=multiaction\">\n";

			// Accounts Table			
			$html .= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$html .= "<tr>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_ACCOUNTS_NICKNAME") . "</td>\n
			<td class='td_title'>" . $adinc->_lang("CONTROL_ACCOUNTS_EMAIL") . "</td>\n
			<td class='td_title' width='120'>" . $adinc->_lang("CONTROL_ACCOUNTS_EDIT_PROFILE") . "</td>\n
			<td class='td_title' width='30'>" . $adinc->_lang("EDIT") . "</td>\n
			<td class='td_title' width='30'>" . $adinc->_lang("DELETE") . "</td>\n
			<td class='td_title' width=\"70\" align=\"center\"><input type=\"checkbox\" name=\"check_all\" onclick=\"checkAll(this.form)\" /></td>
			</tr>\n";				
			
			while($rows = $db->rows($accounts)){
			
				$html .= "<tr>\n
				<td class='td_row'>$rows[account_nickname]</td>\n
				<td class='td_row'>$rows[account_email]</td>\n
				<td class='td_row'><a href=\"accounts.php?action=profile&accountid=$rows[account_id]\"><img src=\"icons/edit_profile.png\" border=\"0\" /></a></td>\n
				<td class='td_row'><a href=\"accounts.php?action=edit&accountid=$rows[account_id]\"><img src=\"icons/edit.png\" border=\"0\" /></a></td>\n
				<td class='td_row'><a onclick=\"if(window.confirm('". $adinc->_lang("ALERT_CONFIRM") ."') == false){return false;}\" href=\"accounts.php?action=delete&accountid=$rows[account_id]\"><img src=\"icons/remove.png\" border=\"0\" /></a></td>\n
				<td class='td_row' align='center'><input type='checkbox' name='check[]' value='$rows[account_id]' /></td>
				</tr>\n";			
			
			}
			
			$html .= "</table>\n";
			
			$html .= '<div class="tools_bar">
					  <div style="float:left;">'.$Paginator->GetPagination().'</div>
					  <input type="submit" name="ban_selected" value="' . $adinc->_lang("CONTROL_ACCOUNTS_BAN_SELECTED") . '" />
					  <input type="submit" name="delete_selected" value="' . $adinc->_lang("CONTROL_ACCOUNTS_DELETE_SELECTED") . '" />
					  </div>';
			
			$html .= "</form>\n";
			
			$adinc->getMenu($adinc->_lang("CONTROL_ACCOUNTS_MANGE") , $html);
						
		}
		
		public function editProfile(){
			
			global $db , $adinc;
			
			$accountid = _safeInt($_REQUEST['accountid']);
			
			if($db->getfastTotal("select account_id from " . PFX . "accounts where account_id='$accountid' limit 1") !== 0){ 
				
				if(isset($_POST['editprofile'])){
					
					// let's start ؟(^_^)? 
					$genderPOST 		 = _safeInt($_POST['account_gender']);
					$locationPOST 		 = _safeString($_POST['account_location']);	

					// birthdate
					$birthdatePOST_DAY   = _safeString($_POST['account_birthdate_day']);
					$birthdatePOST_MONTH = _safeString($_POST['account_birthdate_month']);
					$birthdatePOST_YEAR  = _safeString($_POST['account_birthdate_year']);
					$birthdatePOST		 = $birthdatePOST_DAY . '/' . $birthdatePOST_MONTH . '/' . $birthdatePOST_YEAR;
					// signature 
					$signaturePOST = strip_tags($_POST['account_signature']);
					
					$editprofileQUERY = $db->query("update " . PFX . "accounts set 
					account_gender='$genderPOST' , 
					account_birthdate='$birthdatePOST' , 
					account_location='$locationPOST' , 
					account_signature='$signaturePOST'
					where account_id='$accountid' limit 1");
					
					if($editprofileQUERY){
					
						$adinc->greenAlert( $adinc->_lang('CONTROL_ACCOUNTS_PROFILE_DONE'));
						$adinc->location('accounts.php');
					}				
					
				}
				
				$rows = $db->getfastRows("select * from " . PFX . "accounts where account_id='$accountid' limit 1");
				
				
				// avatar Form
				$avatarSize = @getimagesize("../upload/accounts/" . $rows['account_avatar']);
				
				if(!empty($rows['account_avatar']) and $avatarSize[0] !== 0){
					
					$avatarPath = '../upload/accounts/' . $rows['account_avatar'];
				
				}else{
				
					$avatarPath = 'images/no-avatar.png';	
				}
								
				$AvatarForm  = '<div class="account-nickname">'. $rows['account_nickname'] .'</div>';
				$AvatarForm .= '<div class="account-avatar-box">';
				$AvatarForm .= '<div class="options"><img src="icons/change.png" id="pickfiles" class="accounts-edit-avatar-button" /></div>';
				$AvatarForm .= '<img src="'.$avatarPath.'" width="186" height="186" id="account-avatar" class="account-avatar" />';
				$AvatarForm .= '</div>';
				$AvatarForm .= '<div id="container"><div id="filelist"></div></div>';
				
				
				// birthdate Forum
				$BirthDateForm  = "<select name='account_birthdate_month'>";
				
				$birthdateROWS = explode("/" , $rows['account_birthdate']);
			  
				foreach(dateMonths() as $month){ 
				
					$month_number++;
					$month_number = $month_number+1;
					
					$BirthDateForm .= "<option value='$month_number'"; 
					
					if($month_number == $birthdateROWS[1]){
						
						$BirthDateForm .= " selected='selected'";
					}
					
					$BirthDateForm .= ">" . _lang("MONTH_" . $month , LANGUAGE_CODE) . "</option>\n";
								  
				}
						  
				$BirthDateForm .= "</select><select name='account_birthdate_year'>";
							
				for($i=date("Y" , time());$i>=1960;$i--){
					
					$BirthDateForm .= "<option value='$i'";

					if($i == $birthdateROWS[2]){
						
						$BirthDateForm .= " selected='selected'";
					}
					
					$BirthDateForm .= ">$i</option>";
				}
	
				$BirthDateForm .= "</select><input type=\"text\" id=\"account_birthdate_day\" name=\"account_birthdate_day\" value=\"$birthdateROWS[0]\" style='width:40px' />";
				
				
				// locations LIST
				$locationList = "<select name='account_location'>";
						  
				foreach(countryISO() as $country){
				
					$locationList .= "<option value='$country'";
					
					if($country == $rows['account_location']){

						$locationList .= " selected='selected'";

					}
					
					$locationList .= ">" . _lang("COUNTRY_" . $country , LANGUAGE_CODE) . "</option>\n";
								  
				}
						  
				$locationList .= "</select>";
				
				
				// gender LIST 
				$genderList  = "<select name='account_gender'>\n";
				$genderList .= "<option value='1'"; 
				// check if male selected
				if($rows['account_gender'] == 1)	$genderList .= " selected='selected'"; 
					
				$genderList	.= ">"._lang("PROFILE_GENDER_MALE" ,  LANGUAGE_CODE)."</option>";
				$genderList .= "<option value='2'";
				// check if female selected				
				if($rows['account_gender'] == 2) 	$genderList .= " selected='selected'"; 

				$genderList .= ">"._lang("PROFILE_GENDER_FEMALE" ,  LANGUAGE_CODE)."</option>\n</select>";
				
				$html  = '<form method="post">';  
				$html .= '<table width="100%" border="0" cellspacing="5" cellpadding="0" class="form-table">';	
				$html .= '<tr><td rowspan="3" width="186" valign="top">';
				$html .= $AvatarForm;
				$html .= '</td><td>'. $adinc->_lang("CONTROL_ACCOUNTS_BIRTHDATE") .'</td>';
				$html .= '<td>'. $BirthDateForm .'</td>';
				// Brithdate
				$html .= '</tr><tr>';
				$html .= '<td>'. $adinc->_lang("CONTROL_ACCOUNTS_LOCATION") .'</td>';
				$html .= '<td>'. $locationList . '</td>';
				// Gender
				$html .= '</tr><tr>';
				$html .= '<td>'. $adinc->_lang("CONTROL_ACCOUNTS_GENDER") . '</td>';
				$html .= '<td>'. $genderList .'</td>';
				// Signature
				$html .= '</tr><tr>';
				$html .= '<td colspan="3" class="main-row">'. $adinc->_lang("CONTROL_ACCOUNTS_SIGNATURE") .'</td>';			
				$html .= '</tr><tr>';
				$html .= '<td colspan="3"><textarea name="account_signature" id="account_signature" rows="6">'. $rows['account_signature'] .'</textarea></td>';
				// Submit
				$html .= '</tr><tr>';
				$html .= '<td>&nbsp;</td>';
				$html .= '<td colspan="2"><input type="submit" name="editprofile" value="'. $adinc->_lang("SAVE") . '" /></td>';		
				// Table end
				$html .= '</tr></table>';
				// append Account Id
				$html .= "\n<input type='hidden' id='account_accountid' value='$rows[account_id]' />";
				$html .= "\n</form>";

				// display Menu
				$adinc->getMenu($adinc->_lang("CONTROL_ACCOUNTS_MANGE") , $html);
				// include js scripts 
				$adinc->getJs("plupload");
				$adinc->getJs("plupload.html5");
				//$adinc->getJs("plupload.flash");
				$adinc->getJs("uploader/uploader.profile");
				echo '<script type="text/javascript">';
				echo '$(document).ready(function(){';
				echo '$("#account_signature").sceditor({
						plugins: \'bbcode\',
						style: "js/editor/jquery.sceditor.default.min.css"
					});';
				echo '});';
				echo '</script>';
			
			} # end if(exists)
			
		} #  :)*_*(: end function(editprofile)
		
	public function multiaction(){
	
		global $db , $adinc , $config;
					
			$check = $_POST['check'];
		
			if(count($check) == 0){

				$adinc->redAlert( $adinc->_lang("ERROR_NO_ITEMS_SELECTED"));	
			
				return false;
			}
			
			$impad = implode(",",$check);	
					
			if(isset($_POST['ban_selected'])){
									
				$MultiAction = $db->query("update " . PFX . "accounts set account_groupid='4' WHERE `account_id` in (".$impad.")");
	
				if($MultiAction){
					
					$adinc->greenAlert( $adinc->_lang("CONTROL_ACCOUNTS_BAN_DONE"));	
			
					$adinc->location('accounts.php');
				}
				
			} # end if(isset::ban_selected)
			
			if(isset($_POST['delete_selected'])){
			
				$MultiAction = $db->query("delete from " . PFX . "accounts WHERE `account_id` in (".$impad.")");
	
				if($MultiAction){
					
					$adinc->greenAlert( $adinc->_lang("CONTROL_ACCOUNTS_PROFILE_DONE"));	
			
					$adinc->location('accounts.php');
				}
				
			} # end if(isset::delete_selected)
				
		} # end function(multiaction)
		
		public function deleteAccount(){
		
			global $db , $adinc;
			
			$accountid = _safeInt($_REQUEST['accountid']);
			
			if($db->getfastTotal("select account_id from " . PFX . "accounts where account_id='$accountid' limit 1") !== 0){ 
			
				$accountInfo = $db->getfastRows("select account_id,account_avatar from " . PFX . "accounts where account_id='$accountid' limit 1");

				$objManager = new objectsManager;
	
				$fileManager = $objManager->getFilesObject();			
				
				// delete current Avatar
				$fileManager->deleteFile("../upload/accounts/" . $accountInfo['account_avatar']);
				
				// delete Account
				$deleteAccount = $db->query("delete from " . PFX . "accounts where account_id='$accountid' limit 1");
				
				if($deleteAccount){
					
					// display alert
					$adinc->greenAlert( $adinc->_lang('CONTROL_ACCOUNTS_DELETE_DONE'));	
					// go back
					$adinc->location('accounts.php');
					
				} # end if($deleteAccount)
			
			} # end if(check if user exists)
			
			else{
			
				$adinc->redAlert($adinc->_lang('CONTROL_ACCOUNTS_NOT_EXISTS'));
				$adinc->location(_back());
				
			} # ne else::if
			
		} # end function(deleteAccount)
		
		public function editAccount(){
		
			global $db , $adinc , $config;	
			
			$accountid = _safeInt($_REQUEST['accountid']);
			
			$core_setting = $db->getfastRows("select accounts_default_group from " . PFX . "core");

			if($db->getfastTotal("select account_id from " . PFX . "accounts where account_id='$accountid' limit 1") !== 0){ 
			
				$rows = $db->getfastRows("select account_id,
												  account_nickname,
												  account_email,
												  account_password,
												  account_salt,
												  account_groupid
												  from " . PFX . "accounts where account_id='$accountid' limit 1");
	
				// when submit	
				if(isset($_POST['editaccount'])){
					
					$nicknamePOST 		= _safeString($_POST['account_nickname']);
					$groupidPOST 		= _safeInt($_POST['account_groupid']);
					$genderPOST 		= _safeInt($_POST['account_gender']);
					$locationPOST 		= _safeString($_POST['account_location']);
					$emailPOST 			= _safeString($_POST['account_email']);
					$passwordPOST 		= $_POST['account_password'];
					
					if(!empty($passwordPOST)){ 
						
						$passwordSALT 		= substr(base64_encode(rand(11111,99999)) , 0 , 7);
						$passwordORGINAL 	= $config->enpassword($passwordPOST . md5($passwordSALT));
	
						if(strlen($passwordPOST) < 7){
						
							$adinc->redAlert(_lang("ERROR_PASSWORD_SHORT" , LANGUAGE_CODE));
							$adinc->closePage();
							$adinc->stop();
						}
					
					}else{
						
						$passwordORGINAL 	= $rows['account_password'];
						$passwordSALT		= $rows['account_salt'];
					}
					
					
					if(!filter_var($emailPOST, FILTER_VALIDATE_EMAIL)){
					
						$adinc->redAlert(_lang("ERROR_EMAIL_VALIDATE" , LANGUAGE_CODE));
						$adinc->closePage();
						$adinc->stop();
					}
					
					if(!empty($nicknamePOST) and !empty($emailPOST)){
						
						
						 $updateAccountInfo = $db->query("update " . PFX . "accounts set 
																			account_salt='$passwordSALT' , 
																			account_password='$passwordORGINAL' , 
																			account_nickname='$nicknamePOST' , 
																			account_email='$emailPOST',
																			account_groupid='$groupidPOST'
																			where account_id='$accountid' limit 1");
																			
						if($updateAccountInfo){
							
							$adinc->greenAlert($adinc->_lang('CONTROL_ACCOUNTS_EDIT_DONE'));
							$adinc->location('accounts.php');	
						}
						
					}
					
				}
				
					// start form
				$form .= "<form method=\"post\">\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
				// Accounts
				$form .= "<tr>\n
						  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_NICKNAME") . "</td>\n
						  \t<td><input type=\"text\" id=\"account_nickname\" name=\"account_nickname\" value=\"$rows[account_nickname]\" /> <span id=\"alert_nickname\" style=\"display:none;\"></span></td>
						  \n</tr>\n";	
				// Accounts
				$form .= "<tr>\n
						  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_EMAIL") . "</td>\n
						  \t<td><input type=\"text\" id=\"account_email\" name=\"account_email\" value=\"$rows[account_email]\" /> <span id=\"alert_email\" style=\"display:none;\"></span></td>
						  \n</tr>\n";	
				// Accounts
				$form .= "<tr>\n
						  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_PASSWORD") . "</td>\n
						  \t<td><input type=\"text\" id=\"account_password\" name=\"account_password\" /> <span id=\"alert_password\" style=\"display:none;\"></span></td>
						  \n</tr>\n";	
						  					  
				// Accounts
				$form .= "<tr>\n
						  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_GROUPID") . "</td>\n
						  \t<td><select name='account_groupid'>";
			
				foreach($this->garray as $group){
				
					$form .= "<option value='$group[id]'";
						
					if($group['id'] == $rows['account_groupid']) $form .= " selected=\"selected\""; 
						
					$form .= ">$group[name]</option>\n";
								  
				}
				
				$form .= "</select></td>
						  \n</tr>\n";		
	
				// end form
				$form .= "<tr>
				\r<td>&nbsp;</td>
				\t<td><input type=\"submit\" name=\"editaccount\" onclick=\"return addAccountAccess();\" value=\"". $adinc->_lang("SAVE") . "\" /></td>
				</tr>";
				$form .= "</table>\n</form>\n";
									  
				$adinc->getMenu($adinc->_lang("CONTROL_ACCOUNTS_EDIT") , $form);
			
			}
			
		}
		
		public function setting(){
		
			global $db , $adinc;
			
			$adinc->getJs("editaccounts.setting");
		
			$core_setting = $db->getfastRows("select accounts_default_group , activation_default_group , accounts_allow_signup , accounts_allow_activation , accounts_avatar_width , accounts_avatar_height from core");
		
			$html = '<div id="submit_alert" style="display:none;"></div>';		
  			$html.= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
 			$html.= '<tr>';
    		$html.= '<td width="20%">'.$adinc->_lang("SETTING_ACCOUNTS_AVATAR_WIDTH").'</td>';
   			$html.= '<td id="td_avatar_width"><input type="text" id="accounts_avatar_width" value="'.$core_setting['accounts_avatar_width'].'" /></td>';
  			$html.= '</tr>';
 			$html.= '<tr>';
    		$html.= '<td width="20%">'.$adinc->_lang("SETTING_ACCOUNTS_AVATAR_HEIGHT").'</td>';
   			$html.= '<td id="td_avatar_height"><input type="text" id="accounts_avatar_height" value="'.$core_setting['accounts_avatar_height'].'" /></td>';
  			$html.= '</tr>';
 			$html.= '<tr>';
    		$html.= '<td width="20%">'.$adinc->_lang("SETTING_ACCOUNTS_ALLOW_SIGNUP").'</td>';
   			$html.= '<td id="td_allow_signup">
				<select id="accounts_allow_signup">
				<option value="1"'; if($core_setting['accounts_allow_signup'] == 1){ $html.= ' selected="selected" ';} $html .= '>'.$adinc->_lang("YES").'</option>
				<option value="0"'; if($core_setting['accounts_allow_signup'] == 0){ $html.= ' selected="selected" ';} $html .= '>'.$adinc->_lang("NO").'</option>
				</select>
			</td>';
  			$html.= '</tr>';
 			$html.= '<tr>';
    		$html.= '<td width="20%">'.$adinc->_lang("SETTING_ACCOUNTS_ACTICATION").'</td>';
   			$html.= '<td id="td_activation">
				<select id="accounts_activation">
				<option value="1"'; if($core_setting['accounts_allow_activation'] == 1){ $html.= ' selected="selected" ';} $html .= '>'.$adinc->_lang("SETTING_ACCOUNTS_ACTICATION_EMAIL").'</option>
				<option value="2"'; if($core_setting['accounts_allow_activation'] == 2){ $html.= ' selected="selected" ';} $html .= '>'.$adinc->_lang("SETTING_ACCOUNTS_ACTICATION_ADMIN").'</option>
				</select>
			</td>';
  			$html.= '</tr>';
 			$html.= '<tr>';
    		$html.= '<td width="20%">'.$adinc->_lang("SETTING_ACCOUNTS_DEFAULT_GROUP").'</td>';
   			$html.= '<td id="td_allow_signup">
				<select id="accounts_default_group">';

			foreach($this->garray as $group){
				
				$html .= "<option value='$group[id]'";
					
				if($group['id'] == $core_setting['accounts_default_group']) $html .= " selected=\"selected\""; 
					
				$html .= ">$group[name]</option>\n";
								  
				}
				
			$html.='</select>
			</td>';
  			$html.= '</tr>';
			$html.= '<tr>';
    		$html.= '<td width="20%">'.$adinc->_lang("SETTING_ACCOUNTS_AFTER_ACTICATION_DEFAULT_GROUP").'</td>';
   			$html.= '<td id="td_allow_signup">
				<select id="activation_default_group">';

			foreach($this->garray as $group){
				
				$html .= "<option value='$group[id]'";
					
				if($group['id'] == $core_setting['activation_default_group']) $html .= " selected=\"selected\""; 
					
				$html .= ">$group[name]</option>\n";
								  
				}
			$html.='</select>
			</td>';
  			$html.= '</tr>';
			$html.= '<tr>';
    		$html.= '<td>&nbsp;</td>';
    		$html.= '<td><input type="button" id="submit_accounts_setting" value="'.$adinc->_lang("SAVE").'" /></td>';
  			$html.= '</tr>';
			$html.= '</table>';
			
			$adinc->getMenu($adinc->_lang("SETTING_ACCOUNTS") , $html);
											
		}
	
		public function search(){
		
			global $db , $adinc;
			
			$by 		= _safeString($_REQUEST['by']);
			$searchq 	= _safeString($_REQUEST['q']);
			$form		= _safeString($_REQUEST['form']);
			
			$adinc->getExtraLinks(array(	$adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_GROUP")  		=> 'accounts.php?action=search&form=group' , 
											$adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_EMAIL")  		=> 'accounts.php?action=search&form=email' , 
											$adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_LOCATION")  	=> 'accounts.php?action=search&form=location' , 
											$adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_GENDER")  	=> 'accounts.php?action=search&form=gender' , 
											$adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_NICKNAME")  	=> 'accounts.php?action=search&form=nickname'));
			
			$adinc->getJs("accounts");
			
			if($form == "email"){
			
				$HTMLform = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="20%">'.$adinc->_lang("CONTROL_ACCOUNTS_EMAIL").'</td>
					<td><input type="text" id="accounts_search_query"/> <span id="alert_name" style="display:none;"></span></td>
				  </tr>
				  <tr>
					<td></td>
					<td><input type="submit" name="addlang" onclick="return searchBy(\'email\')" value="'.$adinc->_lang("CONTROL_ACCOUNTS_SEARCH").'" /></td>
				  </tr>
				</table>';

				$adinc->getMenu( $adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_EMAIL") , $HTMLform);

			}else if($form == "nickname"){
			
				$HTMLform = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="20%">'.$adinc->_lang("CONTROL_ACCOUNTS_NICKNAME").'</td>
					<td><input type="text" id="accounts_search_query"/> <span id="alert_name" style="display:none;"></span></td>
				  </tr>
				  <tr>
					<td></td>
					<td><input type="submit" name="addlang" onclick="return searchBy(\'nickname\')" value="'.$adinc->_lang("CONTROL_ACCOUNTS_SEARCH").'" /></td>
				  </tr>
				</table>';

				$adinc->getMenu( $adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_NICKNAME") , $HTMLform);
	
			}else if($form == "group"){
			
				$HTMLform = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="20%">'.$adinc->_lang("CONTROL_ACCOUNTS_GROUP").'</td>
					<td><select id="accounts_search_query">';
						  
				while($group = $db->rows($this->groups)){
				
					$HTMLform .= "<option value='$group[group_id]'>$group[group_name]</option>\n";
								  
				}
						  
				$HTMLform .= '</select></td>
				  </tr>
				  <tr>
					<td></td>
					<td><input type="submit" name="addlang" onclick="return searchBy(\'location\')" value="'.$adinc->_lang("CONTROL_ACCOUNTS_SEARCH").'" /></td>
				  </tr>
				</table>';

				$adinc->getMenu( $adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_GROUP") , $HTMLform);
	
			}else if($form == "location"){
			
				$HTMLform = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="20%">'.$adinc->_lang("CONTROL_ACCOUNTS_LOCATION").'</td>
					<td><select id="accounts_search_query">';
						  
				foreach(countryISO() as $country){
				
					$HTMLform .= "<option value='$country'>" . _lang("COUNTRY_" . $country , LANGUAGE_CODE) . "</option>\n";
								  
				}
						  
				$HTMLform .= '</select></td>
				  </tr>
				  <tr>
					<td></td>
					<td><input type="submit" name="addlang" onclick="return searchBy(\'location\')" value="'.$adinc->_lang("CONTROL_ACCOUNTS_SEARCH").'" /></td>
				  </tr>
				</table>';

				$adinc->getMenu( $adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_LOCATION") , $HTMLform);
	
			}else if($form == "gender"){
			
				$HTMLform  = '<table width="100%" border="0" cellspacing="0" cellpadding="0">';

				$HTMLform .= "<tr>\n
					  \t<td width=\"20%\">". $adinc->_lang("CONTROL_ACCOUNTS_GENDER") . "</td>\n
					  \t<td><select id='accounts_search_query'>\n
					  <option value='1'>"._lang("PROFILE_GENDER_MALE" ,  LANGUAGE_CODE)."</option>
					  <option value='2'>"._lang("PROFILE_GENDER_FEMALE" ,  LANGUAGE_CODE)."</option>
					  </select>\n</td>
		 			  \n</tr>\n";
					  						  
				$HTMLform .= '<tr>
					<td></td>
					<td><input type="submit" name="addlang" onclick="return searchBy(\'gender\')" value="'.$adinc->_lang("CONTROL_ACCOUNTS_SEARCH").'" /></td>
				  </tr>
				</table>';

				$adinc->getMenu( $adinc->_lang("CONTROL_ACCOUNTS_SEARCH_BY_GENDER") , $HTMLform);
	
			}
				
						
			if(!empty($by) and !empty($searchq)){
					
				// Initialize objectsManager
				$objManager = emanager();
				// Initialize searchEnginer
				$enginer = $objManager->get('searchEnginer');
				// add Nickname Column
				$enginer->column( $adinc->_lang("CONTROL_ACCOUNTS_NICKNAME") 		 , 'account_nickname.30%');
				// add Email Column
				$enginer->column( $adinc->_lang("CONTROL_ACCOUNTS_EMAIL") 	 		 , 'account_email.30%');
				// add Editprofile Column
				$enginer->extra ( $adinc->_lang("CONTROL_ACCOUNTS_EDIT_PROFILE")  	 , "<a href=\"accounts.php?action=profile&accountid=[account_id]\"><img src=\"icons/edit_profile.png\" border=\"0\" /></a>");
				// add Edit Column
				$enginer->extra ( $adinc->_lang("CONTROL_ACCOUNTS_EDIT")  	 	 	 , "<a href=\"accounts.php?action=edit&accountid=[account_id]\"><img src=\"icons/edit.png\" border=\"0\" /></a>");
				// add Delete Column
				$enginer->extra ( $adinc->_lang("DELETE")  	 	 			 		 , "<a onclick=\"if(window.confirm('". $adinc->_lang("ALERT_CONFIRM") ."') == false){return false;}\" href=\"accounts.php?action=delete&accountid=[account_id]\"><img src=\"icons/remove.png\" border=\"0\" /></a>");
				
				if($by == "group"){
				
					$searchsql = "select * from " . PFX . "accounts where account_groupid='$searchq'";
							
				}else if($by == "gender"){
	
					$searchsql = "select * from " . PFX . "accounts where account_gender='$searchq'";
					
				}else if($by == "location"){
	
					$searchsql = "select * from " . PFX . "accounts where account_location='$searchq'";
					
				}else if($by == "email"){
					
					$searchsql = "select * from " . PFX . "accounts where account_email='$searchq'";
					
				}else if($by == "nickname"){
					
					$searchsql = "select * from " . PFX . "accounts where account_nickname LIKE '%$searchq%'";
					
				}else{
	
					$searchsql = "select * from " . PFX . "accounts limit 50 order by account_id desc";
					
				}
				
				// set query			
				$enginer->sql = $searchsql;
				
				// display results
				$enginer->result();

			} # end if
			
		} # end function(search)
		
	} # end object
	
?>