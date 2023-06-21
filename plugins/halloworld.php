<?php

	class halloworld{
	
		public $plugin_name = "أهلاً بالعالم";
		public $plugin_version = "1.0";
		public $plugin_about = "تم برمجة هذا الهاك من قبل PHP4U";
		public $plugin_moduleID = "";
		
		public function main(){
			
			global $db , $config , $setting , $mod;
			
			if($mod->modInfo['module_path'] == "videos"){
											
				assign("test_var" , $setting->rows['title']);

			}

		}
		
		public function setting(){
		
			echo 'Hallo You Are In The Control Rom';	
		}

		public function admin(){
		
			echo 'Hello , You Are In The Admin Rom';
				
		}
		
		public function install(){
			
			addHtmlToModule("{test_var}" , "videos_watch" , true , $this->hook_name , 'videos');
		
		}
		
		public function unInstall(){
		
			deleteHtmlFromModule("{test_var}" , "videos_watch" , true , $this->hook_name , 'videos');
				
		}
		
		public function onBeforeConnectionClose(){

			// do something		
			setvar("HAHA" , "Fuck You");	
			
		}
		
		public function onAfterConnectionClose(){
		
			// do something
			//print getvar("HAHA");	
		}
		
		public function onBeforeMenusLoad(){

		}
		
		public function onBeforeHeaderDisplay(){
			
		}
		
		public function onAfterHeaderDisplay(){
			
		}
		
		public function onAfterContentDisplay(){
			
		}
		
		public function onAfterFooterDisplay(){
			
		}
		
		public function onBeforeInitializeAjaxEnginer(){
			
		}
	
	}
	
?>