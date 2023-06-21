<?php

	class Uploader{
	
		public $FILE_VER = "file";
		
		public $TYPES = array();
		
		public $FILE_EXT = array();	
		
		public $DIR_TO ,
			   $FILES_SIZE , 
			   $file_tmp , 
			   $file_name , 
			   $file_size , 
			   $file_type , 
			   $file_error,
			   $file_hash , 
			   $file_ext;
					
	   	public $FAKE_UPLOAD = false;
		
	    private function check_content($file){
		   
			$txt = @file_get_contents($file);
			$Safe_File = true;
			
			if (preg_match('#&(quot|lt|gt|nbsp|<?php);#i', $txt))
			{
				$Safe_File = false;
			}
			elseif (preg_match("#&\#x([0-9a-f]+);#i", $txt))
			{
				$Safe_File = false;
			}
			elseif (preg_match('#&\#([0-9]+);#i', $txt))
			{
				$Safe_File = false;
			}
			elseif (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt))
			{
				$Safe_File = false;
			}
			elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt))
			{
				$Safe_File = false;
			}
			elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt))
			{
				$Safe_File = false;
			}
			elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt))
			{
				$Safe_File = false;
			}
			elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt))
			{
				$Safe_File = false;
			}
			elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt))
			{
			$Safe_File = false;
			}
			
			return $Safe_File;
		}
				
		public function getFileInformation(){
		
			$this->file_name = $_FILES[$this->FILE_VER]['name'];
			$this->file_name = str_replace(" " , "_" , $this->file_name);
			
			$this->file_size = $_FILES[$this->FILE_VER]['size'];
			$this->file_tmp  = $_FILES[$this->FILE_VER]['tmp_name'];
			$this->file_type = $_FILES[$this->FILE_VER]['type'];
			$this->file_error= $_FILES[$this->FILE_VER]['error'];
			$this->file_hash = substr(md5(time()) , 0 , 10);
			$this->file_ext  = strrchr($this->file_name ,'.');
			
			if($this->FAKE_UPLOAD == false){
					
				if(!is_dir($this->DIR_TO)){
					
					mkdir($this->DIR_TO , 0777);
					
				}

			}
		}
		
		public function checkFileContent(){
			
			if($this->check_content($this->file_tmp) == false){ return false; }else{return true;}
		}
		
		public function checkFileType(){
		
			if(!in_array($this->file_type,$this->TYPES)){ return false;}else{return true;}
			if(!in_array($this->file_ext,$this->FILE_EXT)){ return false;}else{return true;}
				
		}
		
		public function checkFileSize(){
			
			if($this->file_size < $this->FILES_SIZE){
			
				return true;	
			
			}else{
			
				return false;	
			}
		}
		
		public function upload(){
		
			if(move_uploaded_file($this->file_tmp , $this->DIR_TO . $this->file_hash . $this->file_name)){
				
				return true;
			
			}else{
			
				return false;	
			}
			
		}
		
	}
?>