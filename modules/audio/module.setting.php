<?php

	class moduleSetting{
	
		private $settingRows;
		
		public function __construct(){
		
			global $db;
			
			$query = $db->query("select * from audio_module_setting");		
		
			$this->settingRows = $db->rows($query);
		}

		public function getPlayerType(){
		
			return($this->settingRows['player_type']);
			
		}
	
		public function getLeftMenu(){
		
			return($this->settingRows['left_menu']);
			
		}
		
		public function getRightMenu(){
		
			return($this->settingRows['right_menu']);
			
		}
		
		public function getAddcomments(){
		
			return($this->settingRows['add_comments']);
			
		}
	}
?>