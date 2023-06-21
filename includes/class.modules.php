<?php

	class eliaModules{
		
		public  $path   , 
				$page   , 
				$action , 
				$mod	,
				$templates,
				$menus , 
				$modInfo;
				
		public $default_mod = "news";

		private $not_exists;
		
		public function __construct(){
			
				global $logs;

				$this->mod     = $this->safe_get(strip_tags(trim($_GET['mod'])));
						
				$this->path    = $this->safe_get(strip_tags(trim($_GET['dir'])));
				
				$this->page    = $this->safe_get(strip_tags(trim($_GET['page'])));
				
				$this->action  = strip_tags(trim(addslashes($_GET['action'])));
				
				$this->not_exists = _lang("MODULE_NOT_EXISTS");

				_debug("- Modules Object Initialized" , "green");
				
				if(preg_match('/..\//', $this->mod) || 
						preg_match('/.\//', $this->mod) || 
						preg_match('/\//', $this->mod)						
						){
							
							_debug("-- Module $_GET Name Error" , "red");

							$logs->addLog(2);
							die($this->not_exists);
						}
				if(empty($this->mod)){ 
					
					$this->mod = $this->default_mod; 
					
				}
				
		}
			
		private function safe_get($value){
		
			$un_safed = array('./' , '../' , '*' , '{' , '}' , '\/' , '|' , '%' , '*' , '&' , '#' , '@' , ')' , '(');
			
			$value = str_replace($un_safed , "" , $value);	
			
			return $value;
		}
		

		public function run(){

				global $logs , $events;
				
				$getInfo 	   = mysql_query("select * from " . PFX . "modules where module_path = '$this->mod'");
				
				if(mysql_num_rows($getInfo) == false){ 
						
					_debug("-- Module ($this->mod) Does Not Exists" , "red");
					
					$logs->addLog(2); 
					die($this->not_exists); 
					
				}
				
				$this->modInfo = mysql_fetch_assoc($getInfo);
				
				if($this->modInfo['module_active'] == 1){ 
				
					$logs->addLog(2);
					die(_lang("MODULE_DISABLED"));
					
				}
				
				_debug("-- Module ($this->mod) Initialized" , "green");

		}
		
		public function page(){
				
			global $menu , $db , $setting , $logs , $events;
						
			if(!$this->page){
			
				$this->page = "index";
			}
			
			if(isset($_GET['dir'])){
				
				if(file_exists(modulesPath . "/" . $this->mod . "/" . $this->path . "/" . $this->page . ".php") ){
				
					include_once(modulesPath . "/" . $this->mod . "/" . $this->path . "/" . $this->page . ".php");
				
				}else{
	
					_debug("-- No ($this->path) Folder in ($this->mod) Module" , "red");

					$logs->addLog(2);
					die($this->not_exists);
				
				}
	
			}else{
					
				if(file_exists(modulesPath . "/" . $this->mod . "/" . $this->page . ".php") ){
					
					include_once(modulesPath . "/" . $this->mod . "/" . $this->page . ".php");
					
					_debug("--- ($this->page) Page in ($this->mod) Module Loaded" , "green");

				}else{

					_debug("-- ($this->page) Does Not Exists in ($this->mod) Module" , "red");
					
					$logs->addLog(2);
					die($this->not_exists);
				}
	
			}
			
			if(defined('templatesShown')){
				
				$this->templates = false;
			
			}else{
			
				$this->templates = true;	
			}
		
			if(defined('menusShown')){
				
				$this->menus = false;
			
			}else{
			
				$this->menus = true;	
			}
			
			$output = ob_get_clean();
			
			return $output;
			
		}
		
		public function moduleSetting(){
		
			if(file_exists(modulesPath . "/" . $this->mod . "/module.setting.php") ){
			
				include_once(modulesPath . "/" . $this->mod . "/module.setting.php");
				
				return(new moduleSetting());
				
			}else{
			
				die($this->not_exists);
			}
					
		}
		
		public function getModuleID(){
		
			if(!empty($this->modInfo['module_sid'])) return $this->modInfo['module_sid']; else  return $this->modInfo['module_id'];
			
		}
		
		public function getTemplate($template , $params){
			
			global $GET_GLOBAL_TEMPS_ASSIGN;
			
			// new Template Engine
			$temp = new Template;

			$temp->Dir = modulesPath . "/" . $this->mod . "/templates";
	
			$temp->get($template);
			
			// defined - Module path params
			$temp->assign( array("module" , "Module" , "MODULE" , "modulePath") , $this->mod);
			
			// check if you have global params
			if(is_array($GET_GLOBAL_TEMPS_ASSIGN) and count($GET_GLOBAL_TEMPS_ASSIGN) !== 0){
			
				// assign global params
				foreach($GET_GLOBAL_TEMPS_ASSIGN as $global_key => $global_var){
					
					if(is_array($global_var)){
						
						if($template == $global_var[1]){

							$temp->assign($global_key , $global_var[0]);
							
						}

					}else{
				
							$temp->assign($global_key , $global_var);
		
					}
						
				}
		
			}
			
			// assign params
			foreach($params as $key => $var){
				
				$temp->assign($key , $var);
		
			}
			
			$temp->replace();
	
			$temp->display();
			
			_debug("-- display ($template) Template in ($this->mod) Module" , "green");

			unset($temp);
	
			}
					
	}
	
?>