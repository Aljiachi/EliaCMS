<?php

	class objectsManager{
	
		public function getFilesObject(){
		
			include_once("../includes/class.files.php");
			
			return(new Files());
			
		}
		
		public function getPaginatorObject(){
		
			include_once("adinc/class.paginator.php");
			
			return(new Paginator());
				
		}
		
		public function getCoreObject(){
		
			include_once("../includes/class.setting.php");
			
			return(new setting());

		}
		
	}
?>