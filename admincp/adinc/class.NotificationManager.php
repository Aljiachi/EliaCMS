<?php

	class NotificationManager{
	
		public $Notifications , $NotifyModuleId , $NotifyModuleName , $NotifyModulePath;
		
		public function __construct(){
		
			global $pdo;
			
			$Query = $pdo->query("select * from " . PFX . "modules");
	
				while($rows = $Query->fetch() ) {

					if(file_exists("modules/" . $rows['module_path'] . '/control/notify.php')){
										
						ob_start();
						
						$this->NotifyModuleId = $rows['module_id'];
						$this->NotifyModuleName = $rows['module_name'];
						$this->NotifyModulePath = $rows['module_path'];
							
						include("modules/" . $rows['module_path'] . '/control/notify.php');
				
						$content = ob_get_clean();

						if(!empty($content)){ 
						
							$this->Notifications .= ''.$rows['module_name'] . '%end&' . $content . ',';
					
						}else{
							
							$this->NotifyModuleId = NULL;	
							$this->NotifyModuleName = NULL;
							$this->NotifyModulePath = NULL;
						}
					}
				}
								
		}		
		
		public function getNotify(){
		
			return($this->Notifications);	
		}
		
	}
?>