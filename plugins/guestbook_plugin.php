<?php

	class guestbook_plugin{
	
		public $plugin_name = "هاك سجل الزوار";
		public $plugin_version = "1.0";
		public $plugin_about = "تم برمجة هذا الهاك من قبل PHP4U";
		public $plugin_moduleID = "";
		
		public function main(){
			
			global $db , $config , $setting , $mod;
			
			// do Something
		}
		
		public function setting(){
		
			echo 'Hallo You Are In The Control Rom';	
		}

		public function admin(){
		
			echo 'Hello , You Are In The Admin Rom';
				
		}
		
		public function onAddGroupLoad(){
		
			echo 'You will add new Group';
				
		}
		
		public function onBeforeHeaderDisplay(){

			// do something		
			setvar("IU" , "GUEST_BOOK");	
			
		}
		
		public function onAfterHeaderDisplay(){
		
			// do something
			print getvar("IU");	
		}

		
	}
	
?>