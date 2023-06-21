<?php

	class eliaAjaxEnginer{
		
		private $act ; 
		
		public function AccountsCheckEmailExists(){
		
			global $db , $adinc , $setting;
			
			if(isset($_POST['ajaxar_email']) && !empty($_POST['ajaxar_email'])){ 
			
				$request = array();
				$request['email'] = _safeString($_POST['ajaxar_email']);
				
				if(!filter_var($request['email'] , FILTER_VALIDATE_EMAIL)){
									
					$request['return']	= 'false';
					$request['msg']		= _lang("ACCOUNTS_ERROR_EMAIL");	

					return(json_encode($request));

				}
				
				if($db->getfastTotal("select account_id from " . PFX . "accounts where account_email='$request[email]' limit 1") == 0){
					
					$request['return'] 	= 'true';
					$request['msg']		= _lang('ACCOUNTS_ERROR_EMAIL_AVAILABLE');
				}else{
				
					$request['return']	= 'false';
					$request['msg']		= _lang('ACCOUNTS_ERROR_EMAIL_EXISTS');	
				}
			
				return(json_encode($request));

			}
		}
		
		
		public function accountsRequest($reqType){
		
			global $db , $adinc , $setting;
			
			if(!class_exists("eliaAccounts")){
			
				require 'class.accounts.php';	
			
			}
			
			$eliaAccounts = new eliaAccounts();
			$res = $eliaAccounts->sendRequest($reqType);

			_print($res);
			
		}
		
		public function run(){
		
			$this->act = _safeString($_REQUEST['act']);
						
			switch($this->act) {
			
				case "CHECK_EMAIL" : 
				
					_print($this->AccountsCheckEmailExists());
					
				break;	
				
				case "COMPLATE_REGISTRATION" : 
				
					_print($this->accountsRequest('signup'));
					
				break;
				
				case "COMPLATE_SIGNIN" : 
				
					_print($this->accountsRequest('signin'));
					
				break;
				
				case "COMPLATE_PROFILE" : 
				
					_print($this->accountsRequest('editprofile'));
					
				break;
				
				case "CHANGE_PASSWORD" : 
				
					_print($this->accountsRequest('changepassword'));
					
				break;
				
			}
			
		}
	}
?>