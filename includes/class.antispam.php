<?php

	class eliaAntispam{

		private $curSec;
		private $refreshTimes = 50;
		private $timeoutStart = 40; // per ms
		private $timeoutEnd = 180; // per ms
		private $autoBlock = true;
		private $timeoutSessionValue;
		private $refreshTimesSessionValue;
		private $attempts;
		private $message;
		
		public function run(){

			global $config , $logs , $setting;
		
			// 
			$getRequest = $_SERVER['HTTP_HOST'];
						
			$locRequest = explode("/" , _setting("siteurl"));
			
			if($getRequest !== $locRequest[2]){exit;}
						
			$this->curSec =  microtime();
			$this->timeoutSessionValue = number_format($_SESSION['antispam_timeout'],1);
			$this->refreshTimesSessionValue = $_SESSION['antispam_refreshTimes'];
			$this->attempts = 0;
				
				if($this->refreshTimesSessionValue <= (time()+1200) && $this->refreshTimesSessionValue > $this->refreshTimes){
					
					$this->_restValues();
				}
				
				if($_SESSION['antispam_restlog'] >= time()){

					if($_SESSION['antispam_witingMod'] == false){
						
						_debug( _ip() . " logged in Waiting Mod" , "alert");
					
					}
					
					// into Witting Mod
					$_SESSION['antispam_witingMod'] = true;
					
					print $setting->basicHeader();	
				
					print $setting->_sysLog("<div class=\"system_blocked_message\">". _lang("ERROR_WAITING_MOD") ."</div>");
				
					print $setting->basicFooter();
					
					_end();
					
				}
				
				if($this->refreshTimesSessionValue >= $this->refreshTimes && ($this->timeoutSessionValue <= $this->timeoutStart || $this->timeoutSessionValue >= $this->timeoutEnd)){
												
						$logs->addLog(7 , "DOS_LOGS{refresh Times : ".$this->refreshTimesSessionValue." in ".$this->timeoutSessionValue." sec.}");
						
						if($this->autoBlock && $this->attempts >= 3){
						
							$logs->setBlock(1);		
						}
	
						$this->_restValues();
	
						$_SESSION['antispam_restlog'] = time()+(60*1);
						$_SESSION['antispam_attempts'] = $_SESSION['antispam_attempts']+1;
						
				}
				

			$_SESSION['antispam_timeout'] 	  = (microtime()+$_SESSION['antispam_timeout']);
			$_SESSION['antispam_refreshTimes'] = ($_SESSION['antispam_refreshTimes']+1);
						 			
		}
				
		 private function _checkIp($ip){
			 
			 $pattern = "/^([1]?\d{1,2}|2[0-4]{1}\d{1}|25[0-5]{1})(\.([1]?\d{1,2}|2[0-4]{1}\d{1}|25[0-5]{1})){3}$/";
			 return (preg_match($pattern, $ip) > 0) ? true : false;
		 
		 }
	 
	 	private function _restValues(){
			
			_debug("anti-spam Values has has been reset" , "alert");

			$_SESSION['antispam_timeout'] 	  = "";
			$_SESSION['antispam_refreshTimes'] = "";
		 	$_SESSION['antispam_restlog'] = "";
			$_SESSION['antispam_witingMod'] = false;
			
		 	return true;
		}

	}
	
?>
