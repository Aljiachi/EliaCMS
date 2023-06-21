<?php

	function getAgoTime($time){
			
		   $periods = array("ثواني", "دقائق", "ساعات", "أيام", "إسابيع", "شهور", "سنوات", "عقود");
		   $periodsA= array("ثانية" , "دقيقة" , "ساعه", "يوم", "اسبوع", "شهر", "سنة", "عقد");
		   $periodsB= array("ثانيتين", "دقيقتين", "ساعتين", "يومين", "اسبوعين", "سنتين", "عقدين");
		   $lengths = array("60","60","24","7","4.35","12","10");
		
		   $now = time();
		
			   $difference     = $now - $time;
			   $tense         = "قبل";
		
		   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			   $difference /= $lengths[$j];
		   }
		
		   $difference = round($difference);
		
		   if($difference == 1) {
		   		return "قبل $periodsA[$j]";

		   }else if($difference == 2){
		   		return "قبل $periodsB[$j]";			   
		   }else{
				return "قبل $difference $periods[$j]";
		   }
		
		   
	}
	
	function addJs($path){
	
		global $GET_GLOBAL_HEADER_PARAMS;
		
		$GET_GLOBAL_HEADER_PARAMS .= "<script type=\"text/javascript\" src=\"$path\"></script>\n";

	}
	
	
	function addCss($path){
	
		global $GET_GLOBAL_HEADER_PARAMS;
		
		$GET_GLOBAL_HEADER_PARAMS .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$path\" />\n";

	}
	
	function addMeta($params){
	
		global $GET_GLOBAL_HEADER_PARAMS;
		
		$meta = "<meta ";
		
			foreach($params as $param => $value){
			
					$meta .= $param . "=\"" . $value . "\" ";
			}
			
		$meta .= '/>';
		
		$GET_GLOBAL_HEADER_PARAMS .= "$meta\n";

	}
	
  function sendmail($to,$subject,$msg,$from = false){

		$headers ="MIME-Version: 1.0 \r\n";
		$headers.="from: $from \r\n";
		$headers.="Content-type: text/html;charset=utf-8 \r\n";
		$headers.="X-Priority: 3\r\n";
		$headers.="X-Mailer: smail-PHP ".phpversion()."\r\n";
		
		if(mail($to,$subject,$msg,$headers)){
		
			return true;
		
		}else{
		
			return false;
		
		}

} 

	function addHtml($htmlContent , $tmplate , $in_top=true , $safe_tage='Dont Edit'){
	
		$objManager = new objectsManager;
		$objManager->inSite=true;
		
		$fileObject = $objManager->getFilesObject();
		
		$defultContent = $fileObject->readFile(StyleDir . '/' . $tmplate . '.html');

		$putNewContent = $fileObject->writeFile(StyleDir . '/' . $tmplate . '.html' , '<!--' . $safe_tage . '-->' . $htmlContent . '<!--' . $safe_tage . '-->' . $defultContent);	
		
		if($putNewContent)return true; else return false;
				
	}
	
	function addHtmlToModule($htmlContent , $tmplate , $in_top=true , $safe_tage='Dont Edit' , $moduleFolder){
	
		global $config , $db;
	
		if($db->getfastTotal("select module_id from " . PFX . "modules where module_path='$moduleFolder'") == 0){ print mysql_error(); return false;}
			
		$objManager = new objectsManager;

		$fileObject = $objManager->getFilesObject();
				
		$defultContent = $fileObject->readFile('../'.$config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html');

		$putNewContent = $fileObject->writeFile('../'.$config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html' , '<!--' . $safe_tage . '-->' . "\n" . $htmlContent . "\n" . '<!--' . $safe_tage . '-->' . $defultContent);	
		
		if($putNewContent)return true; else return false;
				
	}
	
	function deleteHtmlFromModule($htmlContent , $tmplate , $in_top=true , $safe_tage='Dont Edit' , $moduleFolder){
	
		global $config , $db;
	
		if($db->getfastTotal("select module_id from " . PFX . "modules where module_path='$moduleFolder'") == 0){ print mysql_error(); return false;}
			
		$objManager = new objectsManager;

		$fileObject = $objManager->getFilesObject();
				
		$defultContent = $fileObject->readFile('../'.$config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html');
		$defultContent = str_replace('<!--' . $safe_tage . '-->' . "\n" . $htmlContent . "\n" . '<!--' . $safe_tage . '-->' , "" , $defultContent);
		
		$putNewContent = $fileObject->writeFile('../'.$config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html' , $defultContent);	

		
		if($putNewContent)return true; else return false;
				
	}
	
	
	// Real Ip function
	function _ip(){
		
		// get Ip
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $_ENV['REMOTE_ADDR'];
			
		// return with ip name
		return($ip);
		
	}
	
	function _debug($message , $color , $request=''){
	
		global $db , $config;
		
		$time = time();
		$ip = _ip();
		if(empty($request)) $request = $_SERVER['REQUEST_URI'];
		
		if($db->getfastTotal("select id from " . PFX . "debug where time=$time and message='$message' and color='$color' and request='$request' and ip='$ip'") == 0){
			
			$insert = $db->query("insert into " . PFX . "debug values('' , '$time' , '$message' , '$color' , '$request' , '$ip' , '1')");	
	
		}
	}
	
	function _safeString($string){
		
		return(strip_tags(trim(addslashes($string))));
	}
	
	function _safeInt($int){
		
		return(intval(abs($int)));
	}
	
	function groupStatement($groupid , $option){
	
		global $db;
		
		$row = new $db->getfastRows("select $option from " . PFX . "groups where group_id=" . $groupid);	
	
		return($row[$option]);
	
	}
	
?>