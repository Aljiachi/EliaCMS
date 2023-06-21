<?php

	class eliaModRewrite{
	
		public $danyRules;
		
		public function addRule($From , $To){
		
			global $adinc;
				
			$objManager = new objectsManager;
	
			$fileObject = $objManager->getFilesObject();
					
			$currentFolder = $_SERVER['SCRIPT_NAME'];
			$currentFolder = explode("/" , $currentFolder);
						
			$htaccessPath = $fileObject->readFile($_SERVER["DOCUMENT_ROOT"] . '/' . $currentFolder[1] . '/.htaccess');
			
			$newRuleString = 'RewriteRule ' . $this->checkInputs($From) . "$ \t\t $To";
			
			$newHtaccessPath  = $htaccessPath;
			$newHtaccessPath .= "\n# Rule\n";
			$newHtaccessPath .= $newRuleString;
			
			if($fileObject->writeFile($_SERVER["DOCUMENT_ROOT"] . '/' . $currentFolder[1] . '/.htaccess' , $newHtaccessPath)){
			
				return true;	
			}
		}
		
		public function removeRule($From , $To){
		
			global $adinc;
				
			$objManager = new objectsManager;
	
			$fileObject = $objManager->getFilesObject();
					
			$currentFolder = $_SERVER['SCRIPT_NAME'];
			$currentFolder = explode("/" , $currentFolder);
						
			$htaccessPath = $fileObject->readFile($_SERVER["DOCUMENT_ROOT"] . '/' . $currentFolder[1] . '/.htaccess');
			
			$newRuleString = 'RewriteRule ' . $this->checkInputs($From) . "$ \t\t $To";
			
			$newHtaccessPath  = "\n# Rule\n";
			$newHtaccessPath .= $newRuleString;
			
			if($fileObject->writeFile($_SERVER["DOCUMENT_ROOT"] . '/' . $currentFolder[1] . '/.htaccess' , str_replace($newHtaccessPath , "" , $htaccessPath))){
			
				return true;	
			}
		}
		
		public function checkInputs($string){
		
			$replace_from = array('[int]' , '[string]' , '[*]' , '[anything]');
			
			$replace_to	  = array('([0-9]+)' , '([a-zA-Z0-9_]+)' , '(.+)' , '(.+)');
				
			$string = str_replace($replace_from , $replace_to , $string);
			
			return $string;
		}

	}
?>