<?php

	class eliaAccounts{
	
		public 		$accountID , $formLOCATIONS , $formBIRTHDATE;
		private 	$act;
		
		public function __construct(){

		}
		
		public function signup(){
		
			global $db , $adinc , $setting;

			addJs(_url("jscript/accounts/signup.js"));
						
			$this->formLOCATIONS = '';
			
			foreach(countryISO() as $country){
			
				$this->formLOCATIONS .= "<option value='$country'>" . _lang("COUNTRY_" . $country) . "</option>\n";
							  
		 	}
			
			// birthdate Forum
			$this->formBIRTHDATE  			= array();
			$this->formBIRTHDATE['months'] 	= '';
			$this->formBIRTHDATE['years']  	= '';
					  
			foreach(dateMonths() as $month){ 
				
				$month_number++;
				$month_number = $month_number+1;
					
				$this->formBIRTHDATE['months'] .= "<option value='$month_number'>" . _lang("MONTH_" . $month) . "</option>\n";
							  
			}
						  							
			for($i=date("Y" , time());$i>=1960;$i--){
					
				$this->formBIRTHDATE['years'] .= "<option value='$i'>$i</option>";

			}
	
			$assing = array('locations' 		=> $this->formLOCATIONS , 
							'birthdate_months' 	=> $this->formBIRTHDATE['months'] , 
							'birthdate_years' 	=> $this->formBIRTHDATE['years']
							);
							
		   $setting->getTemplate("accounts_signup" , $assing);

		}
		
		// signin Function
		public function signin(){
			
			global $setting , $adinc;
			
			addJs(_url("jscript/accounts/signin.js"));
			
		   	$setting->getTemplate("accounts_login" , $assing);
	
		} // end (signin) function
		
		
		private function signout(){
		
			$this->endSession();
			
			header("location:accounts?act=signin");		
		
		}
		
		// checkIfEmailExists function
		public function checkIfEmailExists($email){
		
			global $db;
			
			if($db->getfastTotal("select account_id from " . PFX . "accounts where account_email='$email' limit 1") == 0){
				
				return true;
			
			}else{
			
				return false;
					
			}
		
		} // end (checkIfEmailExists) function 
		
	
		// complateSignup Function 
		// param ajaxRequest : bool
		public function complateSignup($ajaxRequest=false){
		
			global $db , $adinc , $config;
			
			if(isset($_POST['accounts-signup-submit'])){
			
				$values 						=  array();
				$values['nickname'] 			= _safeString($_POST['input-accounts-nickname']);
				$values['email']				= _safeString($_POST['input-accounts-email']);	
				
				// optional values
				$values['gender']				= _safeString($_POST['input-accounts-gender']);			
				$values['location']				= _safeString($_POST['input-accounts-location']);
				
				// birthdate values
				$values['birthdate']['month']  	= _safeString($_POST['input-accounts-birthdate-months']);		
				$values['birthdate']['year']	= _safeString($_POST['input-accounts-birthdate-years']);	
				$values['birthdate']['day']		= _safeString($_POST['input-accounts-birthdate-day']);	
				
				// other values 
				$values['ip']		= _ip();
				$values['groupid'] 	= _setting("accounts_default_group");
				$values['signdate'] = date("d/m/Y" , time());
				
				// password values
				$values['salt'] 	= substr(base64_encode(rand(11111,99999)) , 0 , 7);
				$values['password']				= _safeString($_POST['input-accounts-password']);
				$values['password_confirm']		= _safeString($_POST['input-accounts-password-confirm']);
				$values['password_with_salt']	= $config->enpassword($values['password'] . md5($values['salt']));
				
				$ERRORS 	= array();
				$return		= array();
											
				if(!$this->checkIfEmailExists($values['email'])){
				
					if($ajaxRequest == false) _print(_lang('ACCOUNTS_ERROR_EMAIL_EXISTS'));
				
					$return['errors'] = "email_exists";
					$return['msg'] 	  = _lang("ACCOUNTS_ERROR_EMAIL_EXISTS");
					
					$ERRORS = 'error';	
				}

				if($values['password'] !== $values['password_confirm']){
					
					_print(_lang("ACCOUNTS_ERROR_PASSWORD_NOT_SAME"));
					
					$return['errors'] = _lang("ACCOUNTS_ERROR_PASSWORD_NOT_SAME");
					
					$ERRORS[] = 'error';
				}
			
				if(!filter_var($values['email'] , FILTER_VALIDATE_EMAIL)){
				
					_print(_lang("ACCOUNTS_ERROR_EMAIL"));
					
					$return['errors'] = "email_validate";
					$return['msg'] 	  = _lang("ACCOUNTS_ERROR_EMAIL");
					
					$ERRORS[] = 'error';

				}
				
				if(count($ERRORS) == 0){ 
				
					$addSQL = "insert into " . PFX . "accounts (account_nickname , 
																			account_email	 ,
																			account_password , 
																			account_salt	 ,
																			account_lastip	 ,
																			account_groupid	 ,
																			account_birthdate,
																			account_gender	 ,
																			account_location , 
																			account_signup_date) values ('$values[nickname]' , 
																									  '$values[email]'	  ,
																									  '$values[password_with_salt]' ,
																									  '$values[salt]'	  ,
																									  '$values[ip]'		  ,
																									  '$values[groupid]'  ,
																									  '$values[birthdate]',
																									  '$values[gender]'   ,
																									  '$values[location]' ,
																									  '$values[signdate]')";
					
					// ex Query
					$addQuery = $db->query($addSQL);	
					
					// get Account id
					$values['uid'] = $db->insertId();
					
					if($addQuery){
						
						if($values['groupid'] == 2){
								
							$return['result']			= 'complate';
							$return['msg'] 				= _lang("ACCOUNTS_REGISTRATION_SUCCESS");	

							
							if($ajaxRequest == false) _print(_lang("ACCOUNTS_REGISTRATION_SUCCESS"));				

						}else if($values['groupid'] == 5){
														
							$return['result']			= 'awaiting_activation';
							$return['msg'] 				= _lang("ACCOUNTS_ACTIVATION_MESSAGE");	
							
							if(_setting("accounts_allow_activation") == 1){
							
								$return['act']					= _lang("ACCOUNTS_CHECK_YOUR_INBOX");	
								
								$activationParams				= array();
								$activationParams['{siteurl}']	= _setting("siteurl");
								$activationParams['{sitename}']	= _setting("title");
								$activationParams['{code}']		= $this->createActivationCode();
								$activationParams['{username}'] = $values['nickname'];
								$activationParams['{email}']	= $values['email'];
								
								$activationMessage				= _lang("ACCOUNTS_ACTIVATION_EMAIL_MESSAGE");
								
								foreach($activationParams as $activationParamKey => $activationParamVar){
								
									$activationMessage 			= str_replace($activationParamKey , $activationParamVar , $activationMessage);
										
								}
								
								// send email to user..
								$obManager = emanager();
								// Initialize Mailer Object
								$Mailer = $obManager->get('mailer');
								// set Mailer options
								$Mailer->toEmail = $values['email'];
								$Mailer->Subject = 'Active Your Account';
								$Mailer->Message = $activationMessage;
								$Mailer->setTemplate(eliaMiler::DEFAULT_TEMPLATE);
								$Mailer->send();
															
								// يجب العودة 								
								if($Mailer->isSent()){
								
									$addCode	= $db->query("insert into " . PFX . "accounts_activation_codes values ('' , '".$activationParams['{code}']."' , '$values[uid]' , '". _ctime() ."')");									

								}else{
								
									$addCode	= $db->query("insert into " . PFX . "accounts_activation_codes values ('' , '".$activationParams['{code}']."' , '$values[uid]' , '". _ctime() ."')");									
	
								}

								$return['msg'] .= '<a href=' . _setting("siteurl") . '/accounts?act=active&cid=' . $activationParams['{code}'] . '>Active</a>';
								
							}else{

								$return['act']			= _lang("ACCOUNTS_AWAITING_ACTIVATION");	
								
							}
						}
						
						// base information
						$return['info']['id']  		= $values['uid'];
						$return['info']['nickname'] = $values['nickname'];
						$return['info']['email']	= $values['email'];
					}
					
				}else{
					
						$return['result']			= 'faild';

				}
			
			}
			
			return(json_encode($return));
				
		} // end (complateSignup) function
		
		
		// complateSignin function
		// param ajaxRequest : bool
		public function complateSignin($ajaxRequest=false){
		
			global $db , $adinc , $config;	
			
			$values = array();
			$return = array();
			$ERRORS = array();
			
			if(isset($_POST['accounts-signin-submit'])){ 
				
				// orginal Values			
				$values['email']				= _safeString($_POST['input-accounts-email']);	
				$values['password']				= _safeString($_POST['input-accounts-password']);
				$values['curtime']				= time();
				
				// check if Email Exists
				if($this->checkIfEmailExists($values['email'])){
					
					if($ajaxRequest == false) _print(_lang('ACCOUNTS_ERROR_EMAIL_NOT_EXISTS'));
					
					$return['errors'] = "email_not_exists";
					$return['msg'] 	  = _lang("ACCOUNTS_ERROR_EMAIL_NOT_EXISTS");
						
					$ERRORS = 'error.1';	
				
				}
				
				// check if Email Work
				if(!filter_var($values['email'] , FILTER_VALIDATE_EMAIL)){
				
					if($ajaxRequest == false) _print(_lang("ACCOUNTS_ERROR_EMAIL"));
					
					$return['errors'] = "email_validate";
					$return['msg'] 	  = _lang("ACCOUNTS_ERROR_EMAIL");
					
					$ERRORS[] = 'error.2';

				}			
				
				if(count($ERRORS) == 0){
					
					// get E-MAIL Information
					$userInfo = $db->getfastRows("select * from " . PFX . "accounts where account_email='$values[email]' limit 1");
					
					// user information values
					$values['info']['nickname'] 	  = $userInfo['account_nickname'];
					$values['info']['password'] 	  = $userInfo['account_password'];
					$values['info']['salt'] 		  = $userInfo['account_salt'];
			
					// access
					$values['access']['password']     = $config->enpassword($values['password'] . md5($values['info']['salt']));					

					if($values['info']['password'] == $values['access']['password']){
					
						if($ajaxRequest == false) _print(_lang("ACCOUNTS_SIGNIN_SUCCESS"));

						$return['msg']  			  = _lang("ACCOUNTS_SIGNIN_SUCCESS");	
						$return['result']			  = 'complate';
						
						$return['info']['nickname']	  = $userInfo['account_nickname'];
						$return['referer']			  = $this->getReferer();
												
						// register SESSIONS
						$_SESSION['elialog_id']		  = $userInfo['account_id'];
						$_SESSION['elialog_nickname'] = $userInfo['account_nickname'];
						$_SESSION['elialog_email']	  = $userInfo['account_email'];
						$_SESSION['elialog_gender']	  = $userInfo['account_gender'];
						$_SESSION['elialog_groupid']  = $userInfo['account_groupid'];
						$_SESSION['elialog_ip']		  = _ip();
						$_SESSION['elialog_logtime']  = $values['curtime'];
						$_SESSION['elialog_ssid']	  = base64_encode($userInfo['email'] . '%' . $userInfo['account_lastlog'] . '%' . $values['curtime']);
						$_SESSION['elialog_logged']   = "logged";
						
						// add userlog
						$this->addLog($userInfo['account_id'] , $values['curtime'] , $_SESSION['elialog_ssid']);
						
					}else{

						if($ajaxRequest == false) _print(_lang("ACCOUNTS_SIGNIN_FLASE_INFORMATION"));

						$return['result']			  = 'faild';
						$return['msg']  			  = _lang("ACCOUNTS_SIGNIN_FLASE_INFORMATION");	
						
					}
					
										
				}else{
					
					$return['result']			= 'faild';

				}
				
			}else{
			
					$return['result']			= 'faild';
	
			}
			
			return(json_encode($return));

		}
		
		private function complateChangePassword($ajaxRequest=false){
		
			global $db , $config;
			
			$values = array();
			$return = array();
			
			$accountid = _safeInt($_REQUEST['accountid']);
						
			if($db->getfastTotal("select account_id from " . PFX . "accounts where account_id='$accountid' limit 1") !== 0){ 
				
				if(isset($_POST['accounts-change-password-submit'])){
					
					$values['salt'] 				= substr(base64_encode(rand(11111,99999)) , 0 , 7);
					$values['password']				= _safeString($_POST['input-accounts-password']);
					$values['password_confirm']		= _safeString($_POST['input-accounts-password-confirm']);
					$values['password_with_salt']	= $config->enpassword($values['password'] . md5($values['salt']));
						
					if($values['password'] !== $values['password_confirm']){
						
						if($ajaxRequest == false) _print(_lang("ACCOUNTS_ERROR_PASSWORD_NOT_SAME"));
						
						$return['msg'] = _lang("ACCOUNTS_ERROR_PASSWORD_NOT_SAME");
						
						$ERRORS[] = 'error';
					
					}
					
					if(count($ERRORS) == 0){
						
						$values['query'] = $db->query("update " . PFX . "accounts set account_password='" . $values['password_with_salt'] . "' , account_salt='" . $values['salt'] . "' where account_id='$accountid' limit 1");
								
						if($values['query']){
							
							$return['result']			= 'complate';
							$return['msg'] 				= _lang("ACCOUNTS_MANGE_PROFILE_SUCCESS");	
								
							if($ajaxRequest == false) _print(_lang("ACCOUNTS_MANGE_PROFILE_SUCCESS"));	
	
						}else{
	
							$return['result']			= 'faild';
							$return['msg']				= mysql_error();
						}
					
					}else{
				
						$return['result']			= 'faild';
						$return['msg']				= $return['msg'];
		
					}
				
				}else{
				
					$return['result']			= 'faild';
					$return['msg']				= 'error.2';
				}
			
			}else{
				
				$return['result']			= 'faild';
				$return['msg']				= 'error.3';
		
			}
				
			return(json_encode($return));
			
		}
		
		public function changePassword(){
				
			global $db , $adinc , $setting;
		
			addJs(_url("jscript/accounts/change_password.js"));
					
			// get Logged Account Information
			$loggedAccount = $this->LoggedAccountInformation();
								
			// account Id 
			$accountId =  _safeInt($loggedAccount['id']);
								
			// check if Account Logged 
			if($loggedAccount['result'] == "faild"){
						
				// end Session
				$this->endSession();	
						
				// Go To Signin Page
				header("location: accounts?act=login");
						
			}
					
			// account id to Js Scripts 
			_jsVerbs("accountid" , $accountId);
			
			$setting->getTemplate("accounts_change_password");		
					
		}

		public function changeAvatar(){
		
			global $db , $adinc , $setting;

			addJs(_url("jscript/accounts/change_avatar.js"));
			
		}
		
		public function editProfile(){
		
			global $db , $adinc , $setting;

			addJs(_url("jscript/accounts/profile.js"));
			
			// get Logged Account Information
			$loggedAccount = $this->LoggedAccountInformation();
						
			// account Id 
			$accountId =  _safeInt($loggedAccount['id']);
						
			// check 
			if($loggedAccount['result'] == "faild"){
				
				// end Session
				$this->endSession();	
				
				// Go To Signin Page
				header("location: accounts?act=login");
				
			}
			
			// account id to Js Scripts 
			_jsVerbs("accountid" , $accountId);
			
			// check if Account Exists
			// يحب العودة
			if($db->getfastTotal("select account_id from " . PFX . "accounts where account_id='$accountId' limit 1") == 0) exit;		
			// get Account Information
			$accountInfo = $db->getfastRows("select account_id,account_nickname,account_location,account_gender,account_birthdate,account_facebook,account_twitter,account_youtube,account_googleplus from " . PFX . "accounts where account_id='$accountId' limit 1");
			
			// birthdate Values
			$accountBirthdate  = explode("/" , $accountInfo['account_birthdate']);

			// locations menu
			$this->formLOCATIONS = '';
			
			foreach(countryISO() as $country){
			
				$this->formLOCATIONS .= "<option value='$country'"; 
				
				if($country == $accountInfo['account_location']) $this->formLOCATIONS .= " selected=\"selected\"";
				
				$this->formLOCATIONS .= ">" . _lang("COUNTRY_" . $country) . "</option>\n";
							  
		 	}
			
			// birthdate Forum
			$this->formBIRTHDATE  			= array();
			$this->formBIRTHDATE['months'] 	= '';
			$this->formBIRTHDATE['years']  	= '';
					  
			foreach(dateMonths() as $month){ 
				
				$month_number++;
				$month_number = $month_number+1;
					
				$this->formBIRTHDATE['months'] .= "<option value='$month_number'";
				
				if($month_number == $accountBirthdate[1]){ $this->formBIRTHDATE['months'] .= " selected=\"selected\"";}
				
				$this->formBIRTHDATE['months'] .= ">" . _lang("MONTH_" . $month) . "</option>\n";
							  
			}
						  							
			for($i=date("Y" , time());$i>=1960;$i--){
					
				$this->formBIRTHDATE['years'] .= "<option value='$i'";

				if($i == $accountBirthdate[2]){ $this->formBIRTHDATE['years'] .= " selected=\"selected\"";}
				
				$this->formBIRTHDATE['years'] .= ">$i</option>";

			}
	
			$assing = array('locations' 		=> $this->formLOCATIONS , 
							'birthdate_months' 	=> $this->formBIRTHDATE['months'] , 
							'birthdate_years' 	=> $this->formBIRTHDATE['years']  , 
							'birthdate_day'		=> $accountBirthdate[0] ,
							'gender'			=> $accountInfo['account_gender'] ,
							'nickname' 			=> $accountInfo['account_nickname'] ,
							'social_facebook'   => $accountInfo['account_facebook'] , 
							'social_youtube'    => $accountInfo['account_youtube']  ,
							'social_googleplus' => $accountInfo['account_googleplus'],
							'social_twitter'    => $accountInfo['account_twitter']
							);
							
		   $setting->getTemplate("accounts_myprofile" , $assing);

		}
		
		public function complateEditProfile($ajaxRequest=false){
			
			global $db;
			
			$values = array();
			$return = array();
			
			$accountid = _safeInt($_REQUEST['accountid']);
						
			if($db->getfastTotal("select account_id from " . PFX . "accounts where account_id='$accountid' limit 1") !== 0){ 
				
				if(isset($_POST['accounts-profile-submit'])){
					
					// main values
					$values['gender'] 			  = _safeInt($_POST['input-accounts-gender']);
					$values['location']			  = _safeString($_POST['input-accounts-location']);	
					$values['nickname']			  = _safeString($_POST['input-accounts-nickname']);	

					// social Networks 
					$values['social']['facebook']   = _safeString($_POST['input-accounts-social-facebook']);
					$values['social']['youtube']    = _safeString($_POST['input-accounts-social-youtube']);
					$values['social']['twitter']    = _safeString($_POST['input-accounts-social-twitter']);
					$values['social']['googleplus'] = _safeString($_POST['input-accounts-social-googleplus']);
					
					// birthdate
					$values['birthdate']['day']   = _safeString($_POST['input-accounts-birthdate-day']);
					$values['birthdate']['month'] = _safeString($_POST['input-accounts-birthdate-months']);
					$values['birthdate']['year']  = _safeString($_POST['input-accounts-birthdate-years']);
					$values['birthdate']['mixed'] = $values['birthdate']['day'] . '/' . $values['birthdate']['month'] . '/' . $values['birthdate']['year'];

					$values['query'] = $db->query("update " . PFX . "accounts set 
					account_nickname   ='".$values['nickname']."' , 
					account_gender	   ='".$values['gender']."' , 
					account_birthdate  ='".$values['birthdate']['mixed']."' , 
					account_location   ='".$values['location']."' ,
					account_facebook   ='".$values['social']['facebook']."',
					account_youtube    ='".$values['social']['youtube']."',
					account_twitter    ='".$values['social']['twitter']."',
					account_googleplus ='".$values['social']['googleplus']."'
					where account_id   ='$accountid' limit 1");
					
					if($values['query']){
						
						$return['result']			= 'complate';
						$return['msg'] 				= _lang("ACCOUNTS_MANGE_PROFILE_SUCCESS");	
							
						if($ajaxRequest == false) _print(_lang("ACCOUNTS_MANGE_PROFILE_SUCCESS"));	

					}else{

						$return['result']			= 'faild';
						$return['msg']				= mysql_error();
					}
					
				}
			
			}else{
				
				$return['result']			= 'faild';
		
			}
				
			return(json_encode($return));

		}
		
		public function accountProfile(){
		
			global $db , $adinc , $setting;
			
			if(isset($_REQUEST['accountid'])){
			
				$accountId = _safeInt ($_REQUEST['accountid']);
					
			}else{
						
				// get Logged Account Information
				$loggedAccount = $this->LoggedAccountInformation();
													
				// account Id 
				$accountId =  _safeInt($loggedAccount['id']);
				
			}

			if($db->getfastTotal("select account_id from " . PFX . "accounts where account_id='$accountId' limit 1") !== 0){
				
				$accountInfo = $db->getfastRows("select account_nickname,account_gender,account_avatar,account_location,account_birthdate,account_youtube,account_facebook,account_twitter,account_googleplus from " . PFX . "accounts where account_id='$accountId' limit 1");
			
				$tempParams  = array(
					'nickname' 	 		=> $accountInfo['account_nickname'] , 
					'gender'	 		=> $this->getGender($accountInfo['account_gender']) , 
					'location' 	 		=> $this->getLocation($accountInfo['account_location']) , 
					'birthdate'  		=> $accountInfo['account_birthdate'] , 
					'social_youtube' 	=> $accountInfo['account_youtube'] , 
					'social_facebook' 	=> $accountInfo['account_facebook'] , 
					'social_twitter' 	=> $accountInfo['account_twitter'] , 
					'social_googleplus' => $accountInfo['account_googleplus'] ,
					'avatar'   		    => $accountInfo['account_avatar'] , 
					'id' 				=> $accountId ,
					'isLogged'			=> $loggedAccount['return']);
			
				$setting->getTemplate("accounts_profile" , $tempParams);	
					
			}else{
			
				header("location: index");	
			}
			
		}
		
		public function complateActive(){
		
			global $db , $adinc;
			
			$values = array();
			
			// orginal values
			$values['code'] 		= _safeGet($_REQUEST['cid']);	
			$values['accountId']	= _safeInt($_REQUEST['aid']);
			$values['groupid']		= _setting("activation_default_group");
			
			// check if Code Exists
			$values['check'] 	= $db->getfastTotal("select code_id from " . PFX . "accounts_activation_codes where code='$values[code]' and code_accountid='$values[accountId]' limit 1");			
			$values['info'] 	= $db->getfastRows("select code_id from " . PFX . "accounts_activation_codes where code='$values[code]' and code_accountid='$values[accountId]' limit 1");			
			$values['account'] 	= $db->getfastRows("select account_id,account_nikcname,account_email,account_groupid from " . PFX . "accounts where account_id='$values[accountId]' limit 1");			
			
			if($values['check'] == 0){

				 $values['msg'] = 'Error';
				 
			}else{

					// يجب العودة
					if($values['info']['code_time'] >= (time()-(360*7)) ){
					
						echo 'Error Timeout.';
							
					}
				
					// Active Account
					$activationCompletedParams					= array();
					$activationCompletedParams['{siteurl}']		= _setting("siteurl");
					$activationCompletedParams['{sitename}']	= _setting("title");
					$activationCompletedParams['{username}'] 	= $values['nickname'];
					$activationCompletedParams['{email}']		= $values['email'];
								
					$activationCompletedMessage				= _lang("ACCOUNTS_ACTIVATION_EMAIL_MESSAGE");
								
					foreach($activationCompletedParams as $activationCompletedParamKey => $activationCompletedParamVar){
								
						$activationCompletedMessage 			= str_replace($activationCompletedParamKey , $activationCompletedParamVar , $activationMessage);
										
					}
								
				// step(1): go to accounts group
				$moveAccountToAGroups = $db->query("update " . PFX . "accounts set account_groupid='$values[groupid]' where account_id='$values[accountId]'");
				// step(2): remove activation code
				$removeActivationCode = $db->query("delete from " . PFX . "accounts_activation_codes where code_accountid='$values[accountId]'");
				// step(3) : send e-mail
				$obManager = emanager();
				// Initialize Mailer Object
				$Mailer = $obManager->get('mailer');
				// set Mailer options
				$Mailer->toEmail = $values['account']['account_email'];
				$Mailer->Subject = 'Activation completed';
				$Mailer->Message = $activationCompletedMessage;
				$Mailer->setTemplate(eliaMiler::DEFAULT_TEMPLATE);
				// send
				$Mailer->send();
				
			}
			
		}
		
		public function sendRequest($requestType){
		
			global $adinc;
			
			$return = array();
			
			if(empty($requestType)){ 
			
				$return['return'] = 'false';	
				$return['error']  = 'NO_REQUEST_TYPE';
			}
			
			switch($requestType){
			
				case "signup" : 

					return($this->complateSignup(true));
					
				break;	
				
				case "editprofile" : 

					return($this->complateEditProfile(true));
					
				break;	
				
				case "changepassword" :
				
					return($this->complateChangePassword(true));
					
				break;
				
				case "signin" : 

					return($this->complateSignin(true));
				
				break;
			}
			
			return(json_encode($return));

		}
		
		public function display(){
			
			global $db , $adinc , $config;
			
			ob_start();
			
			$this->act = _safeString($_REQUEST['act']);
			
			switch($this->act){
			
				case "signup" : 
				
					// recevie Request
					$this->complateSignup();
					// display Form
					$this->signup();

					$output = ob_get_clean();

				break;	
				
				case "signout" :
				
					$this->signout();
					
					$output = ob_get_clean();
					
				break;
				case "myprofile" : 
				
					// recevie Request
					$this->complateEditProfile();
					// display Form
					$this->editProfile();

					$output = ob_get_clean();

				break;	
				
				case "change_password" : 
				
					// recevie Request
					//$this->complateEditProfile();
					// display Form
					$this->changePassword();

					$output = ob_get_clean();

				break;	
				
				case "login" : 
				
					// recevie Request
					$this->complateSignin();
					// display Form
					$this->signin();

					$output = ob_get_clean();
										
				break;
				
				case "active" : 
				
					$output = $this->complateActive();
					
				break;
				
				default :
					
					$this->accountProfile();
					
					$output = ob_get_clean();

				break;
								
			}
			
			return $output;
		}
		
		
		public function createActivationCode(){
			
			global $db;
			
			$salt = "1234567890";
			
			srand((double)microtime()*1000000);
			
			$i = 0;
			
			while ($i <= 15){
				
				$num 	= rand() % 10;
				$tmp 	= substr($salt, $num, 1);
				$code  .= $tmp;
				$i++;
			}
   
   			$code = base64_encode(sha1($active));
			return($code);
			if($db->getfastTotal("select code_id from " . PFX . "accounts_activation_codes where code='$code' limit 1")){
				
				return($code);
		
			}else{
			
				return($this->createActivationCode());	
			}
		
		}
		
		public function addLog($userId , $logTime , $logSSID){
		
			global $db;
			
			$userIp = _ip();
			
			if($db->getfastTotal("select aul_id from " . PFX . "accounts_userlogs where aul_time='$logTime' and aul_uid='$userId' and aul_ip='$userIp' and aul_ssid='$logSSID'") == 0){
			
				$db->query("insert into " . PFX . "accounts_userlogs values ('' , '$userId' , '$logTime' , '$userIp' , '$logSSID')");	
		
			}
		
		} # end function(addLog);
		
		private function getReferer(){
		
			if(isset($_REQUEST['referer'])){
				
				$request = base64_decode($_REQUEST['referer']);
				$request = _safeString($request);
				
				if(substr($request , 0 , 4) !== "http"){
					
					// return with safe request
					return($request);
				
				}else{
					
					return("index");	
				}
				
			}else if(!empty($_SERVER['HTTP_REFERER'])){
				
				if(end(explode("/" , $_SERVER['HTTP_REFERER'])) !== "accounts?act=login"){
					
					return($_SERVER['HTTP_REFERER']);
				
				}else{
				
					return("index");	
				}
				
			}else{
			
				return("index");	
			}
		}
	
		public function getSession($sessionName){
			
			$name = "elialog_" . $sessionName;
			
			return($_SESSION[$name]);
		}
		
		public function LoggedAccountInformation($ref=true){
		
			global $db;
			
			$return = array();
			
			if(isset($_SESSION) && isset($_SESSION['elialog_logged'])){

				$return['id']		= $this->getSession("id");					
				$return['nickname'] = $this->getSession("nickname");
				$return['email']	= $this->getSession("email");
				$return['groupid']  = $this->getSession("groupid");
				$return['ssid']		= $this->getSession("ssid");
				$return['gender']	= $this->getSession("gender");
				$return['ip']		= $this->getSession("ip");
				$return['logtime']  = $this->getSession("logtime");
				$return['result'] 	= "complate";
				$return['return']   = true;
				
				if($ref == true){
					
					// get SSID Information
					$SSIDQuery = $db->query("select * from " . PFX . "accounts_userlogs where aul_ssid='$return[ssid]' limit 1");
						
					if($db->total($SSIDQuery) == 0){
					
						$return['result'] = "faild";
						$return['ernum']  = 2;
						$return['msg']	  = "false ssid code";
						$return['return'] = false;
						
					}else{
					
						$SSIDRow 	 = $db->rows($SSIDQuery);
						
						if($SSIDRow['aul_ip'] !== $return['ip']){
						
							$return['result'] = "faild";
							$return['ernum']  = 3;
							$return['msg']    = "false ip address";	
							$return['return'] = false;
	
						}
							
					}
				}

			}else{
			
				$return['result'] = "faild";
				$return['ernum']  = 1;
				$return['msg']    = "account does not logged yet";	
				$return['return'] = false;
			
			}
			
			return($return);
		}
		
		public function getGender($gender){
		
			switch($gender){
			
				case 1:
				
					return _lang("PROFILE_GENDER_MALE");
					
				break;
				
				case 2 :
				
					return _lang("PROFILE_GENDER_FEMALE");
					
				break;	
			}
		}
		
		
		public function getLocation($country){
					
			// get Country Name			
			return _lang("COUNTRY_" . $country);
				
		}
		
		public function endSession(){
		
			global $db;
			
			// get Sessions
			$Sessions = $this->LoggedAccountInformation(false);
			
			// delete SSID if exists
			$db->query("delete from " . PFX . "accounts_userlogs where aul_ssid='$Sessions[ssid]'");
			
			// clean Sessions
			foreach($Sessions as $sessionName => $sessionVar){
				
				$_SESSION[$sessionName] = "";
			}
			
			// kill Sessions
			session_destroy();
			// Make it Alive
			session_start();
			
			unset($Sessions);			
		}
		
	}
	
?>