<?php

	class Logs{
	
		public $browser_name , $user_ip , $log_time , $curip_logs , $curip_firstlog , $block_time;
		
		private $safe_errors;
		
		public function __construct(){
		
			global $db;
												
			$set_error_handler = set_error_handler(array($this, 'global_error_handler'));

			_debug("Logs Object Initialized" , "green");

			if(isset($_SERVER['HTTP_USER_AGENT']) and !empty($_SERVER['HTTP_USER_AGENT'])){
					
				$browser = @explode(" " , $_SERVER['HTTP_USER_AGENT']);
				
				$this->browser_name = @end($browser) . ' - ' . str_replace("(" , "" , $browser[1]);

			}else{
				
				$this->browser_name = "unkown";
				
				_debug( _ip() . " has no USER_AGENT name" , "red");

			}
			
			$this->user_ip = _ip();
			
			$this->log_time = time();
			
			$this->safe_errors = array(2048 , 8 , 4096);
			
			$this->block_time = ($this->log_time-(60*_setting("auto_block_time")));
			
			$this->curip_logs = $db->getfastTotal("select id from " . PFX . "logs where ip='$this->user_ip' and time>$this->block_time");
			
			$this->curip_firstlog = $db->getfastRows("select id,time from " . PFX . "logs where ip='$this->user_ip' and time>$this->block_time order by id asc limit 50");

			// Check [PHP Log | GET | POST]
			$this->checkInputs();
	
			// Check if User Blocked
			$this->checkIfUserBlocked();									
	
			if(_setting("auto_block") == true){
				
				if($this->curip_logs >= 15){
						
					$this->setBlock(1);
									
					exit;
	
				}

			}
	
		}	
		
		public function global_error_handler($errno, $errstr, $errfile, $errline){  
			
			if(!in_array($errno , $this->safe_errors)){

				$add = $this->addLog(5 , "PHP_ERROR{,number:$errno,message: ".htmlspecialchars(addslashes($errstr)).",line: $errline ,page: ".htmlspecialchars(addslashes($errfile)).",}");

			}
		 
		  return true;
		  
		}
 
		public function checkInputs(){
				
			global $db;
			
			$getSettingInfo = $db->getfastRows("select request_safe , post_safe , unsafe_functions , auto_block_time  , auto_block from " . PFX . "core limit 1");
			
			foreach($_REQUEST as $key => $var){
			
				if(preg_match("#" . $getSettingInfo['request_safe'] . "#i" , $var)){
				
					$this->addLog(1);
				}
			}
			
			foreach($_POST as $key => $var){
			
				if(preg_match("#" . $getSettingInfo['post_safe'] . "#i" , $var)){
				
					$this->addLog(4 , "POST{".$key." : ". htmlspecialchars($var) ."} in {". htmlspecialchars(addslashes(urldecode($_SERVER['REQUEST_URI']))) ."}");
				}
			}
				
		}
		
		public function addLog($action  , $request=NULL){
		
			global $db;
			
			if(empty($request)){
			
				$request = htmlspecialchars(addslashes(urldecode($_SERVER['REQUEST_URI'])));
	
			}

			if($db->getfastTotal("select `id` from `" . PFX . "logs` where `request`='$request' and `ip`='$this->user_ip' and `time`=$this->log_time") == 0){
				
				$addLog = $db->query("insert into `" . PFX . "logs` (`ip`,`action`,`request`,`time`,`brow`) values ('$this->user_ip' , '$action' , '$request' , '$this->log_time' , '$this->browser_name')");
			
				_debug($request , "red");

			}
			
			if($addLog) return true; else return false;
		}
		
		public function setBlock($state){
		
			global $db;
			
			if($db->getfastTotal("select id from " . PFX . "blocks_ip where ip='$this->user_ip'") == 0){
				
				$set = $db->query("insert into " . PFX . "blocks_ip values('' , '$this->user_ip' , '$this->log_time' , '$state')");	
		
				_debug("$this->user_ip has been Blocked" , "red");

			}
		}
	
		public function checkIfUserBlocked(){
		
			global $db , $setting;
			
			if($db->getfastTotal("select id from " . PFX . "blocks_ip where ip='$this->user_ip'") !== 0){

				print $setting->basicHeader();	
				
				print $setting->_sysLog("<div class=\"system_blocked_message\">". _lang("ERROR_BLOCKED") ."</div>");
				
				print $setting->basicFooter();
				
				_end();	
				
			}
		}
	}
	
?>