<?php

	class eliaPlugins{
		
		private $rows , $plugins;
		
		public function __construct(){

			global $db;
			
			$this->plugins = array();
			
			$queryPlugins = $db->query("select * from " . PFX . "hooks where hook_state=1");
			
			while($rows = $db->rows($queryPlugins)){
			
				$this->plugins[] = array($rows['hook_file']);	
			}
						
			_debug("Plugins Object Initialized" , "green");
			
		}
		
		// import plugins function
		public function importPlugins(){
		
			global $db , $config , $adinc;
						
			foreach($this->plugins as $plugin){
			
				if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $config->public_base_directory() . '/' . $config->pluginsPath . "/" . $plugin[0])){
					
					include($_SERVER['DOCUMENT_ROOT'] . '/' . $config->public_base_directory() . '/' . $config->pluginsPath . "/" . $plugin[0]);	
		
					$ClassName = str_replace(".php" , "" , $plugin[0]);
					$hook = new $ClassName();
					$hook->main();
					_debug("$ClassName plugin has be loaded" , "green");
					
				} // end if
			
			} // end foreach
		
		} // end importPlugins function
		
		public function triggerEvent($event){
		
			global $db , $config , $setting , $adinc;
			
			foreach($this->plugins as $plugin){

				if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $config->public_base_directory() . '/' . $config->hooksPath . "/" . $plugin[0])){
							
					$ClassName = str_replace(".php" , "" , $plugin[0]);
					$hook = new $ClassName();
					$eventName = "on" . $event;
					if(method_exists($hook , $eventName)){
						// call Event
						$hook->$eventName();
						// send debug message
						_debug("$ClassName plugin has be called $event Event" , "green");

					} // end if
					
				} // end exists if
				
			} // end foreach
		
		} // end triggerEvent Function
	
	} // end elia plugins Object
	
?>