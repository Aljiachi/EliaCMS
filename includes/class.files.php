<?php

	class Files{
	
		public function deleteFile($filePath){
		
			if(file_exists($filePath)){
				
				// delete File
				unlink($filePath);
				// send debug Alert
				_debug("$filePath has been deleted" , "alert");
				// return
				return true;			
			}
				
			return false;	
			
			# End Delete File Function
		}
		
		public function readFile($filePath){

			if(file_exists($filePath)){
				
				// get File Content	
				$content = file_get_contents($filePath);
				// return by content
				return $content;			
			}
			
			return false;	
			
			# End Read File Function
		}
	
		public function writeFile($filePath , $content , $checkExists=true){

			if($checkExists == true && !file_exists($filePath)){
				
				 // send debug Alert
				 _debug("$filePath you want to Write Does Not Exists" , "red"); 
				 // Stop
				 return false;
					
			}
			
			// Put Contents Into file
			$content = file_put_contents($filePath , $content);
			// send debug Alert
			_debug("$filePath file Writed" , "green");
			// return
			return true;			
							
			# End Read File Function
		}
		
		public function incfile($filePath,$once=true){

			if(file_exists($filePath)){
				
				// include File
				if($once == true) include_once($filePath); else include($filePath);
				// return by true
				return true;			
			}
			
			return false;	
			
			# End include File Function
		}
		
	}
?>