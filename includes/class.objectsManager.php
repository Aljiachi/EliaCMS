<?php

	class objectsManager{
	
		public $inSite	=	false;
		private $slash	=	'./';
		
		public function __construct(){
		
			_debug("ObjectsManager Object Initialized" , "green");
	
		}

		public function get($objectName){
		
			if(in_array($objectName , array('ModRewrite' , 'modRewrite'))){
			
				return($this->getModRewriteObject());	
			}
			
			if(in_array($objectName , array('files' , 'Files' , 'FilesObject' , 'filesObject' , 'FILES'))){
			
				return($this->getFilesObject());	
			}
			
			if(in_array($objectName , array('Paginator' , 'paginator' , 'PaginatorObject' , 'paginatorObject' , 'PAGINATOR'))){
			
				return($this->getPaginatorObject());	
			}
			
			
			if(in_array($objectName , array('core' , 'CORE' , 'CoreObject' , 'coreObject' , 'Core'))){
			
				return($this->getCoreObject());	
			}
			
			
			if(in_array($objectName , array('searchEnginer' , 'SEnginer' , 'SearchEnginer' , 'searchEnginerObject' , 'SEARCHENGINER'))){
			
				return($this->getSearchEnginer());	
			}
			
			
			if(in_array($objectName , array('mailer' , 'Mailer' , 'MAILER' , 'mailerObject'))){
			
				return($this->getMailer());	
			}
			
			return false;
			
		}
		
		private function checkLocation(){
		
			global $config;
			
			if(preg_match('/'. $config->admincpPath .'/' , $_SERVER['REQUEST_URI'])){
				
				$this->slash = '../';
			
			}else{
			
				$this->slash = './';
			}
			
		}
		
		public function getFilesObject(){
		
			_debug("Files object called by objectsManager" , "green");
			
			if(!class_exists("Files")){
				
				require 'class.files.php';
			
			}
			
			return(new Files());
			
		}
		
		public function getPaginatorObject(){
		
			_debug("Paginator object called by objectsManager" , "green");
			
			if(!class_exists("Paginator")){

				require 'class.paginator.php';
	
			}
			
			return(new Paginator());
				
		}
		
		public function getModRewriteObject(){

			_debug("modRewrite object called by objectsManager" , "green");

			if(!class_exists("eliaModRewrite")){

				require 'class.modRewrite.php';
	
			}
			
			return(new eliaModRewrite());
		}
	
		public function getCoreObject(){
		
			if(!class_exists("setting")){

				require 'class.setting.php';
	
			}
			
			return(new setting());

		}
		
		public function getSearchEnginer(){
		
			global $config;
			
			if(!class_exists("eliaSearchEnginer")){

				require '../' . $config->admincpPath . '/adinc/class.searchEnginer.php';
				
			}
			
			return(new eliaSearchEnginer());

		}
			
		public function getMailer(){
		
			global $config;
			
			if(!class_exists("eliaMiler")){

				require 'class.mailer.php';
				
			}
			
			return(new eliaMiler());

		}
			
	}
?>