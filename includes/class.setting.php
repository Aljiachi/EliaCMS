
<?php

	class setting{
		
		private $table = "core";
		
		public $rows;
		
		public function __construct(){
		
				global $db;
				
				$query = $db->query("select * from " . PFX . $this->table);
				
				$this->rows = $db->rows($query);
								
				$timeToDelete = time()-(60*2);
				
				// Delete Cache Debug Logs 				
				$db->query("delete from debug where time<$timeToDelete");
					
			}
			
		public function Dor(){
			
			if($this->rows['close_do'] == 1){
				
				$this->getHeader();
						
				$close = new Template;

				$close->Dir = StyleDir;
	
				$close->get("closed");
	
				$close->assign("text" , $this->rows['close_text']);
	
				$close->replace();
	
				$close->display();
	
				$this->getFooter();	
			
				exit;	
			}
				
			}

		public function getHeader(){
			
			global $GET_GLOBAL_TEMPS_ASSIGN;
			
			$header = new Template;

			$header->Dir = StyleDir;
	
			$header->get("header");
	
			$header->assign(array("title" , "siteTitle" , "siteName" , "sitename" , "sitetitle") , $this->rows['title']);
	
			$header->assign("style" , $this->rows['style']);

			$header->assign(array("siteurl" , "URL" , "sitelink") , $this->rows['siteurl']);
	
			$header->assign("HeaderCommon" , "");

			if(is_array($GET_GLOBAL_TEMPS_ASSIGN) and count($GET_GLOBAL_TEMPS_ASSIGN) !== 0){
				
					foreach($GET_GLOBAL_TEMPS_ASSIGN as $global_key => $global_var){
						
						if(is_array($global_var)){
							
							if($template == $global_var[1]){
	
								$header->assign($global_key , $global_var[0]);
								
							}
	
						}else{
					
								$header->assign($global_key , $global_var);
			
						}
							
					}
			}
			
			$header->replace();
	
			$header->display();
	
			}

	public function basicHeader(){
	
		return "<!DOCTYPE html>
				<html>
				<head>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
				<title>".$this->rows['title']."</title>
				<style>
					body{ background:#F9F9F9; padding:0px; margin:0px; font-family:Tahoma, Geneva, sans-serif; font-size:12px;}
					.system_blocked_message{ width:500px; margin-right:auto; margin-left:auto; padding:15px; background:#F6F6F6; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px; margin-top:10%; text-align:center; color:red; border:1px solid #ccc;}
				</style>
				</head>
				<body>
				";
			
	}
	
	public function _sysLog($message){
		
		return "$message";
	}
	
	public function basicFooter(){
	
		return "</body>\n</html>";	
	}
	
	public function getsubHeader($params){
			
			global $GET_GLOBAL_TEMPS_ASSIGN;
			
			$header = new Template;

			$header->Dir = StyleDir;
	
			$header->get("header");
	
			$header->assign(array("title" , "siteTitle" , "siteName" , "sitename" , "sitetitle") , $this->rows['title']);
	
			$header->assign("style" , $this->rows['style']);

			$header->assign(array("siteurl" , "URL" , "sitelink") , $this->rows['siteurl']);

			if(is_array($GET_GLOBAL_TEMPS_ASSIGN) and count($GET_GLOBAL_TEMPS_ASSIGN) !== 0){
				
					foreach($GET_GLOBAL_TEMPS_ASSIGN as $global_key => $global_var){
						
						if(is_array($global_var)){
							
							if($template == $global_var[1]){
	
								$header->assign($global_key , $global_var[0]);
								
							}
	
						}else{
					
								$header->assign($global_key , $global_var);
			
						}
							
					}
			}

			foreach($params as $key => $var){
				
				$header->assign($key , $var);
		
			}
			
			$header->replace();
	
			$header->display();
	
			}
			
			
		public function getFooter(){
			
			global $GET_GLOBAL_TEMPS_ASSIGN;
	
			// new Template Engine		
			$footer = new Template;
	
			// Set Default Style Path
			$footer->Dir = StyleDir;
	
			// Load template file
			$footer->get("footer");
	
			// assign Global Params
			if(is_array($GET_GLOBAL_TEMPS_ASSIGN) and count($GET_GLOBAL_TEMPS_ASSIGN) !== 0){
				
					foreach($GET_GLOBAL_TEMPS_ASSIGN as $global_key => $global_var){
						
						if(is_array($global_var)){
							
							if($template == $global_var[1]){
	
								$footer->assign($global_key , $global_var[0]);
								
							}
	
						}else{
					
								$footer->assign($global_key , $global_var);
			
						}
							
					}
			}

			// Replace Matches
			$footer->replace();
			
			// Check Elia 
			_elia_copyright();
		
			// display footer
			$footer->display();
			
		}
			
		public function getTemplate($template_name , $params){
			
			global $GET_GLOBAL_TEMPS_ASSIGN;
			
			$template = new Template;

			$template->Dir = StyleDir;
	
			$template->get($template_name);
	
			$template->assign(array("title" , "siteTitle" , "siteName" , "sitename" , "sitetitle") , $this->rows['title']);
	
			$template->assign("style" , $this->rows['style']);

			$template->assign(array("siteurl" , "URL" , "sitelink") , $this->rows['siteurl']);
	
			if(is_array($GET_GLOBAL_TEMPS_ASSIGN) and count($GET_GLOBAL_TEMPS_ASSIGN) !== 0){
				
					foreach($GET_GLOBAL_TEMPS_ASSIGN as $global_key => $global_var){
						
						if(is_array($global_var)){
							
							if($template == $global_var[1]){
	
								$template->assign($global_key , $global_var[0]);
								
							}
	
						}else{
					
								$template->assign($global_key , $global_var);
			
						}
							
					}
			}
					
			if(count($params) !== 0){
							
				foreach($params as $key => $var){
					
					$template->assign($key , $var);
			
				}

			}
			
			$template->replace();
	
			$template->display();
			
			}
			
		}
		
	$setting = new setting;
		
	define("StyleDir" , "themes/" . $setting->rows['style'] . "/templates");

	$setting->Dor();
	
?>