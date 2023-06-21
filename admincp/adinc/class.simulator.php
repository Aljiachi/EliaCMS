<?php

	class eliaSimulator{
	
		public $events;
		
		public function __construct(){
		
			global $adinc , $config;
			
			include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $config->public_base_directory() . '/includes/class.plugins.php';
			include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $config->public_base_directory() . '/includes/class.events.php';
						
			// Plugins Object
			$adinc->plugins = new eliaPlugins();
			$adinc->plugins->importPlugins();
							
			// Events Object
			$adinc->events = new eliaEvents($adinc->plugins);
		
		}
		
		
	}
	
?>