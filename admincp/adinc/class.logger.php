<?php

	class eliaLogger{
	
		private $username , $password , $userID , $userIP , $userMAIL , $userSALT , $userNAME , $userPASSWORD;
		
		public function getTop(){
		
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Elia-CMS - Login</title>
				<link type="text/css" rel="stylesheet" href="style.css" />
				<script type="text/javascript" src="js/jquery.js"></script>
				<script type="text/javascript" src="js/functions.js"></script>
				<script type="text/javascript" src="js/signin.js"></script>
				</head>
				<body>';	
		}
		
		public function getButtom(){
		
			echo '</body>
					</html>';
	
		}
		
		public function loginForm(){
		
			global $db , $adinc;

			$form  = '<div class="login_box">';
   			$form .= '<div id="submit_alert"></div>';
    		$form .= '<div id="submit_loading" style="display:none;"><center><img src="images/loading.gif" /></center></div>';
    		$form .= '<form id="FormLogin" method="post">';
    		$form .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
  			$form .= '<tr>';
    		$form .= '<td>'. $adinc->_lang("LOGIN_USERNAME") .'</td>';
			$form .= '<td id="td_name"><input type="text" id="username" /></td>';
  			$form .= '</tr><tr>';
    		$form .= '<td>'. $adinc->_lang("LOGIN_PASSWORD") .'</td>';
    		$form .= '<td id="td_pass"><input type="password" id="password" /></td>';
  			$form .= '</tr><tr>';
    		$form .= '<td>&nbsp;</td>';
    		$form .= '<td><input type="submit" id="login_submit" value="'. $adinc->_lang("SUBMIT") .'" onclick="return Login();" /></td>';
		  	$form .= '</tr></table></form></div>';
			
			echo $form;
    	
		}
	
		public function reopenForm(){

			global $db , $adinc;
						
			$form = '<div class="login_box">';
		
			if(isset($_POST['reopen_send'])){
		
				$this->username = strip_tags($_POST['username']);
 				$this->password = md5($_POST['password']);
		
				$access = $db->getfastRows("select username , password , ip , siteurl , admin_email from " . PFX . "core");
				
				$get_y    = strip_tags(base64_decode($_REQUEST['y']));
				$get_y    = explode("#2F%" , $get_y);
				$get_aid  = $_REQUEST['aid'];
				$get_in   = $_REQUEST['in'];
				
				$accountsAccess 	= $db->query("select account_id,account_email,account_password,account_groupid from " . PFX . "accounts where 
											  account_email='$this->username' and account_password='$this->password' limit 1");
				
				$rowsAccountsAccess = $db->rows($accountsAccess); 
					
				if($db->total($accountsAccess) !== 0){
					
					$this->userIP   = $rowsAccountsAccess['account_lastip'];
					$this->userID   = $rowsAccountsAccess['account_id'];
					$this->userMAIL = $rowsAccountsAccess['account_email'];
					 
				}else{
				
					$this->userIP = $access['ip'];
					$this->userID = '0';
					$this->userMAIL = strip_tags($_POST['mail']);

				}
				
				if($db->total($accountsAccess) !==  0){
					
					$loggedUsername = $rowsAccountsAccess['account_email'];
					$loggedPassword = $rowsAccountsAccess['account_password'];
					$loggedIp 		= $rowsAccountsAccess['account_lastip'];
					$loggedMail		= $loggedUsername;
					
				}else{
					
					$loggedUsername = $access['username'];
					$loggedPassword = $access['password'];
					$loggedIp		= $access['ip'];
					$loggedMail		= $access['admin_email'];
					
				}
				
 				if($loggedUsername == $this->username 
				&& $loggedPassword == $this->password 
				&& $get_y[0] == md5($loggedUsername)
				){
	
						$_SESSION['admin_alkafeelCms_username'] = md5($loggedUsername);
						
						$_SESSION['admin_alkafeelCms_password'] = md5($loggedPassword); 
						
						$_SESSION['admin_alkafeelCms_logtime']  = md5(time());
						
						if($db->total($accountsAccess) !== 0){
		
							$update_ip = $db->query("update " . PFX . "accounts set account_lastip='" . $_SERVER['REMOTE_ADDR'] . "' where account_id='$this->userID'");
							$this->leftBan($this->userID);		
												
						}else{

							$update_ip = $db->query("update " . PFX . "core set ip='" . $_SERVER['REMOTE_ADDR'] . "'");
							$this->leftBan(0);		
						}
						
						$adinc->_debug("Admin User logged to Admin Panel" , "alert" , "admincp/login.php");
						$adinc->_debug("The ban was lifted by the Admin" , "alert" , "admincp/login.php");
	
						$adinc->greenAlert( $adinc->_lang("LOGIN_DONE"));
						$adinc->location("index.php");
						
					}else{
				
						$adinc->_debug($_SERVER['REMOTE_ADDR'] . " type false information in login form" , "alert" , "admincp/login.php");
	
						$adinc->redAlert( $adinc->_lang("ERROR_LOGIN_INFO"));
								
					}	
			}
	
    		$form .= '<form id="FormLogin" method="post">';
    		$form .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
  			$form .= '<tr>';
    		$form .= '<td>'. $adinc->_lang("LOGIN_USERNAME") .'</td>';
			$form .= '<td id="td_name"><input type="text" name="username" /></td>';
  			$form .= '</tr><tr>';
    		$form .= '<td>'. $adinc->_lang("LOGIN_PASSWORD") .'</td>';
    		$form .= '<td id="td_pass"><input type="password" name="password" /></td>';
  			$form .= '</tr><tr>';
			
			if(!isset($_REQUEST['in']) and $_REQUEST['in'] !== "accounts"){
					
				$form .= '<td>'. $adinc->_lang("LOGIN_EMAIL") .'</td>';
				$form .= '<td id="td_pass"><input type="text" name="mail" /></td>';
				$form .= '</tr><tr>';
			
			}
			
			$form .= '<td>&nbsp;</td>';
    		$form .= '<td><input type="submit" name="reopen_send" value="'. $adinc->_lang("SUBMIT") .'" /></td>';
		  	$form .= '</tr></table></form></div>';

			echo $form;
		}
		
		public function blockedMessage(){
		
			global $adinc;
			
			$html  = '<div class="login_box">';
			$html .= '<red>'. $adinc->_lang("BLOCKE_MESSAGE") .'</red>';
       		$html .= '</div>';	
			
			echo $html;
		}
		
		public function ajaxLoginRequest(){
	
			global $db , $adinc , $config;
						
			if(isset($_POST['sender']) and $_POST['sender'] == 1){
		
				$this->username = strip_tags($_POST['username']);
				
				$this->password = $_POST['password'];
		
				$access = $db->getfastRows("select username , password , ip , siteurl , admin_email , salt from " . PFX . "core");
				
				$accountsAccess = $db->query("select account_id,account_email,account_password,account_groupid,account_lastip,account_salt from " . PFX . "accounts where 
											  account_email='$this->username' limit 1");
												
				if($db->total($accountsAccess) !== 0){
					
					// row Account Information
					$rowAccountsAccess = $db->rows($accountsAccess);

					$this->userIP   		= $rowAccountsAccess['account_lastip'];
					$this->userID   		= $rowAccountsAccess['account_id'];
					$this->userMAIL 		= $rowAccountsAccess['account_email'];
					$this->userSALT 		= $rowAccountsAccess['account_salt'];
					$this->userNAME			= $rowAccountsAccess['account_email'];
					$this->userPASSWORD		= $rowAccountsAccess['account_password'];
					
				}else{
				
					$this->userIP 			= $access['ip'];	
					$this->userID 			= '0';
					$this->userMAIL 		= $access['admin_email'];
					$this->userSALT 		= $access['salt'];
					$this->userNAME			= $access['username'];
					$this->userPASSWORD		= $access['password'];
					
				}

				
				$ORGINALPASSWORD = $config->enpassword($this->password . md5($this->userSALT));

				if($this->userNAME == $this->username && $this->userPASSWORD == $ORGINALPASSWORD){
				
					if($db->total($accountsAccess) !== 0){
											
							if(groupStatement($rowAccountsAccess['account_groupid'] , ALLOW_ADMINPANEL) == 0){
			
								$_SESSION['admin_elia_errorlogs'] = $_SESSION['admin_elia_errorlogs']+1;
								
								$adinc->_debug($_SERVER['REMOTE_ADDR'] . " type false information in login form" , "alert" , "admincp/login.php");
								
								$adinc->redAlert( $adinc->_lang("ERROR_LOGIN_INFO"));
							
								$adinc->stop();
							}

						}

						if($config->ipBlock == 1){
							
							if($_SESSION['admin_elia_errorlogs'] == 4){
			
								$adinc->_debug($this->userNAME . " User hass been Blocked" , "red");
								
								$y = base64_encode(md5($this->username) . '#2F%' . sha1($this->username) . sha1(md5($this->username)));
								$x = md5(time()) . sha1(time()) . sha1(md5(time()));
								
								if($db->total($accountsAccess) !== 0){
	
									$link = $access['siteurl'] . '/'.$config->admincpPath.'/login.php?action=reopen&in=accounts&aid='.$rowAccountAccess['account_id'].'&y=' . $y . '&x=' . $x . 
																					   '&lastip=' . base64_encode($this->userIP) . 
																					   '&time=' . time();
								}else{

									$link = $access['siteurl'] . '/'.$config->admincpPath.'/login.php?action=reopen&y=' . $y . '&x=' . $x . 
																					   '&lastip=' . base64_encode($this->userIP) . 
																					   '&adminlog=1&time=' . time();									
								}
								
								$adinc->redAlert( $adinc->_lang("BLOCKE_MESSAGE"));
								print '<script type="text/javascript">setTimeout(function(){ location = "index.php"; } , 3500);</script>';
											
								$sendMail = @sendmail($rowAccountAccess['account_email'], "Elia-CMS - Information Message" , $link , $access['admin_email']);
								
								$this->addBan($this->userID);
								
								exit;
							}
							
						}
					
						$this->sessionOption('admin_elia_username' , base64_encode($this->username));

						$this->sessionOption('admin_elia_password' ,  base64_encode($ORGINALPASSWORD)); 
						
						$this->sessionOption('admin_elia_logtime' , base64_encode(time()));
		
						$this->sessionOption('admin_elia_lastaction' ,  time());
						
						if($db->total($accountsAccess) !== 0) { 
						
							$this->sessionOption('admin_elia_notAdmin' ,  "yes"); 
							$this->sessionOption('admin_elia_groupID' ,  $rowAccountAccess['account_groupid']); 
						
						}
						
						$this->sessionOption('admin_elia_errorlogs' , 0);
						
						if($db->total($accountsAccess) == 0) { 
						
							$update_ip = $db->query("update " . PFX . "core set ip='" . $_SERVER['REMOTE_ADDR'] . "'");
							
						}

						$adinc->_debug($username . " User Logged to Admin panel" , "alert");				
		
						$adinc->greenAlert( $adinc->_lang("LOGIN_DONE"));
						
						print '<script type="text/javascript">setTimeout(function(){ location = "index.php"; } , 3500); </script>';
						
					}else{
				
						 if($_SESSION['admin_elia_errorlogs'] == 4){
							 
							$adinc->_debug("Admin User hass been Blocked" , "red");
																			   
							$adinc->redAlert( $adinc->_lang("BLOCKE_MESSAGE"));
		
							print '<script type="text/javascript">setTimeout(function(){ location = "index.php"; } , 1500); </script>';
							$this->addBan($this->userID);
		
							exit;
						 }
							$_SESSION['admin_elia_errorlogs'] = $_SESSION['admin_elia_errorlogs']+1;
							
							$adinc->_debug($_SERVER['REMOTE_ADDR'] . " type false information in login form" , "alert" , "admincp/login.php");
							
							$adinc->redAlert( $adinc->_lang("ERROR_LOGIN_INFO"));
								
						}
					
			}	
		}
	
		public function addBan($account_id='0'){
		
			global $db;

			$TIME = time();
			$IP   = _ip();
	
			if($db->getfastTotal("select adl_id from " . PFX . "accounts_adminlogs where adl_ip='$IP' and adl_accountid='$account_id'") == 0){
			
				$addBan = $db->query("insert into " . PFX . "accounts_adminlogs values ('' , '$account_id' , '$TIME' , '$IP')");	
				
				if(!$addBan) print mysql_error();
			}	
		}
		
		public function leftBan($account_id='0'){
		
			global $db;

			$IP   = _ip();
	
			if($db->getfastTotal("select adl_id from " . PFX . "accounts_adminlogs where adl_ip='$IP' and adl_accountid='$account_id'") !== 0){
			
				$leftBan = $db->query("delete from " . PFX . "accounts_adminlogs where adl_ip='$IP' and adl_accountid='$account_id'");	
			}	
		}
		
		public function sessionOption($full , $value=''){
		
			return($_SESSION[$full] = $value);

		}
		
		public function isBlocked(){
		
			global $adinc , $db;
			
			$IP = _ip();
			
			if($db->getfastTotal("select adl_id from " . PFX . "accounts_adminlogs where adl_ip='$IP' limit 1") == 0){
				
				return false;
				
			}else{
			
				$rows = $db->getfastRows("select adl_id,adl_time from " . PFX . "accounts_adminlogs where adl_ip='$IP' limit 1");
								
				if($rows['adl_time'] >= (time()-1500)){
					
					return true;
				
				}else{
					
					$this->leftBan(0);	
				}
				
				return false;	
				
				}
		}
	
	}
?>