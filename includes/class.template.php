<?php
	
	class Template{
		
		private $Temp,$File;
		
		public  $Dir = "StyleDir";

		public  $Cache = "includes/cache/";
		
		public function __construct(){

			_debug("Template Object Initialized" , "green");
	
		}
		
		public function get($template){
		
			$this->File = $template;	
											
			$Dot = ".html";
			
			if(file_exists($this->Dir . "/" . $this->File . $Dot)){
				
				$this->Temp = file_get_contents($this->Dir . "/" . $this->File . $Dot);
		
			}
		}
		
		public function setTemp($context){
		
			$this->Temp = $context;	
		}
	
		public function assign($var,$value){
			
			if(!is_array($var)){ 
			
				$GLOBALS[$var] = $value;
					
				$this->Temp = str_replace('{' . $var . '}',$value,$this->Temp);
				$this->Temp = str_replace('$' . $var , '$GLOBALS['."'$var'".']' ,$this->Temp);		
				
			}else{
			
				foreach($var as $vars){
				
					$GLOBALS[$vars] = $value;
				
					$this->Temp = str_replace('{' . $vars . '}',$value,$this->Temp);
					$this->Temp = str_replace('$' . $vars , '$GLOBALS['."'$var'".']' ,$this->Temp);		
				
				}
			}
			
		}
	
		public function includeInTemplate($var , $file){
			
			$GLOBALS[$var] = $value;
			
			ob_start();
            require($file);
            $content = ob_get_clean();
			
			$this->Temp = str_replace('{' . $var . '}',$content,$this->Temp);
			
			}
			
		public function replace($context=''){
		
			global $setting;
			
			if(!empty($context)) $this->Temp = $context;
			
			$this->assign("siteUrl" , $setting->rows['siteurl']);
			
			$this->Temp = str_replace("?>" , "PHP!-->" , $this->Temp);
			$this->Temp = str_replace("<?php" , "<!--PHP" , $this->Temp);
			
			$this->Temp = preg_replace('/\{if\s*name\=\"(.*)\"\s*}/iU','<? if($1){ ?>',$this->Temp);
			$this->Temp = preg_replace('/\{loop\s*name\=\"(.*)\"\s*sql\=\"(.*)\"\s*}/iU','<? while($1 = $db->rows($2)){ ?>',$this->Temp);	
			$this->Temp = str_replace("{else}","<? }else{ ?>",$this->Temp);	
			$this->Temp = str_replace("{/if}","<? } ?>",$this->Temp);
			$this->Temp = str_replace("{/loop}","<? } ?>",$this->Temp);
			$this->Temp = preg_replace('/\{time\.(.*)}/iU' , '<?php print getAgoTime($1); ?>',$this->Temp);
			$this->Temp = preg_replace('/\{lang\.(.*)}/iU' , '<?php print _lang($1); ?>',$this->Temp);
			$this->Temp = preg_replace('/\{date\.(.*)\.(.*)}/iU' , '<?php print date($2,$1); ?>',$this->Temp);
			$this->Temp = preg_replace('/\{substr\.(.*)\.(.*)}/iU' , '<?php print mb_substr($1,0 , $2 , "utf-8"); ?>',$this->Temp);
			$this->Temp = preg_replace('/\{msubstr\.(.*)}/iU' , '<?php print mb_substr($1,0 , 350 , "utf-8"); ?>',$this->Temp);
			$this->Temp = preg_replace('/\{triggerEvent\s*name\=\"(.*)\"}/iU' , '<?php $events->triggerEvent($1); ?>',$this->Temp);
			$this->Temp = preg_replace('/\{(.*)\[(.*)\]}/iU' , '<?php print $$1[\'$2\']; ?>',$this->Temp);
			$this->Temp = preg_replace('/\{(.*)\[(.*)\]\[(.*)\]}/iU' , '<?php print $$1[\'$2\'][\'$3\']; ?>',$this->Temp);
			$this->Temp = preg_replace('/\{std\s*valus\=\"(.*),(.*)\"\s*}/iU','<? if($1 == $2){ print " selected=\"selected\" "; } ?>',$this->Temp);

			return $this->Temp;
		}

		public function display(){
						
			global $events , $db;
			
			$Cache = $this->Cache;
			
			// Import Objects Manager
			$objectsManager = new objectsManager;
			// Import Files Manager From objects Manager
			$filesManager   = $objectsManager->getFilesObject();
			
			if(!file_exists($Cache . md5($this->File) . ".php")){
			
				$filesManager->writeFile($Cache . md5($this->File) . ".php", _encodeDocument($this->Temp) , false);
			
				}else{
				
					if($this->Temp !== file_get_contents($Cache . md5($this->File) . ".php")){

						$filesManager->writeFile($Cache . md5($this->File) . ".php", _encodeDocument($this->Temp) , false);
						
					}
				}

				if(file_exists($Cache . md5($this->File) . ".php")){
								
					unset($html,$content);
				   
					ob_start();
				   
					include($Cache . md5($this->File) . ".php");
				   
					$content = ob_get_clean();
					//$content = str_replace("href=\"" , "" , $content);
					$content = str_replace("PHP!-->" , "?>" , $content);
					$content = str_replace("<!--PHP" , "<?php"  , $content);
																			
					print eval("?>" . _decodeDocument($content) . "<?");
		
			}

		}

	}

?>