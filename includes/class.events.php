<?php

	class eliaEvents{
	
		private $eventsList = array();
		private $errors		= array();
		private $context;
		private $plugins;
		private $modules;
		
		public function __construct($pluginsObject=NULL , $modulesObject=NULL){
			
			// define System Objects
			if($pluginsObject!=NULL) $this->plugins = $pluginsObject; else $this->errors[] = "plugins Object does not exists in eliaEvents";
									 $this->modules = $modulesObject; 
			
		}
		
		public function triggerEvent($event){

			global $db , $setting;
			
			// add events to Events List
			$this->eventsList[] = $event;
							
			// trigger Event 2 Plugins 
			$this->plugins->triggerEvent($event);
			
		}
		
		public function getEvents(){
		
			return($this->eventsList);	
		}
		
		public function getErrors(){
		
			return($this->errors);	
		}
		
	}
?>