<?php

	// Get Time Like [14 hour's ago] Format
	// return [string]
	function getAgoTime($time){
			
		   $periods = array(
		   		_lang("TIME_SECONDS") , 
				_lang("TIME_MINUTES") , 
				_lang("TIME_HOURS") , 
				_lang("TIME_DAYS") ,
				_lang("TIME_WEEKS") , 
				_lang("TIME_MONTHS") ,
				_lang("TIME_YEARS") , 
				_lang("TIME_ARGS")
			);
				
		   $periodsA= array(
		   		_lang("TIME_SECOND") , 
				_lang("TIME_MINUTE") , 
				_lang("TIME_HOUR") ,
				_lang("TIME_DAY") ,
				_lang("TIME_WEEK") , 
				_LANG("TIME_MONTH") , 
				_lang("TIME_YEAR") ,
				_lang("TIME_ARG")
			);
					
		   $periodsB= array(
		   		_lang("TIME_2SECONDS") , 
				_lang("TIME_2MINUTES") ,
				_lang("TIME_2HOURS") , 
				_lang("TIME_2DAYS") ,
   				_lang("TIME_2WEEKS") , 
				_lang("TIME_2MONTHS") , 
				_lang("TIME_2YEARS") , 
				_lang("TIME_2ARGS")
			);
			
		   $lengths = array("60","60","24","7","4.35","12","10");
		
		   $now = time();
		
			   $difference     = $now - $time;
			   $tense         = _lang("TIME_AGO");
		
		   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			   $difference /= $lengths[$j];
		   }
		
		   $difference = round($difference);
		
		   if($difference == 1) {
		   		return _lang("TIME_AGO") . " $periodsA[$j]";

		   }else if($difference == 2){
		   		return _lang("TIME_AGO") . " $periodsB[$j]";			   
		   }else{
				return _lang("TIME_AGO") . " $difference $periods[$j]";
		   }
		
		   
	}
	
	// Add Js Tag in Head
	// (1) param [ type : string , value : path of js file ]
	// return [true]
	function addJs($path){
	
		global $GET_GLOBAL_HEADER_PARAMS;
		
		$GET_GLOBAL_HEADER_PARAMS .= "<script type=\"text/javascript\" src=\"$path\"></script>\n";

	}
	
	// Add Link stylesheet Tag in Head
	// (1) param [ type : string , value : path of css file ]
	// return [true]
	function addCss($path){
	
		global $GET_GLOBAL_HEADER_PARAMS;
		
		$GET_GLOBAL_HEADER_PARAMS .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$path\" />\n";

	}

	// Add Meta Tag in Head
	// (1) param [ type : array ]
	// return [true]
	function addMeta($params){
	
		global $GET_GLOBAL_HEADER_PARAMS;
		
		$meta = "<meta ";
		
			foreach($params as $param => $value){
			
					$meta .= $param . "=\"" . $value . "\" ";
			}
			
		$meta .= '/>';
		
		$GET_GLOBAL_HEADER_PARAMS .= "$meta\n";

	}
	
	// Add Html Code in Head
	// (1) param [ type : string , value : html code ]
	// return [true]
	function addInHead($code){
	
		global $GET_GLOBAL_HEADER_PARAMS;
				
		$GET_GLOBAL_HEADER_PARAMS .= "$code\n";

	}
	
  function sendmail($to,$subject,$msg,$from = false){

		$headers ="MIME-Version: 1.0 \r\n";
		$headers.="from: $from \r\n";
		$headers.="Content-type: text/html;charset=utf-8 \r\n";
		$headers.="X-Priority: 3\r\n";
		$headers.="X-Mailer: smail-PHP ".phpversion()."\r\n";
		
		if(mail($to,$subject,$msg,$headers)){
			
			_debug("E-mail has been sent {to:$to , subject:$subject}" , "alert");
			return true;
		
		}else{
		
			return false;
		
		}

} 

	function addHtml($htmlContent , $tmplate , $in_top=true , $safe_tage='Dont Edit'){
	
		$objManager = new objectsManager;
		
		$fileObject = $objManager->getFilesObject();
		
		$defultContent = $fileObject->readFile(StyleDir . '/' . $tmplate . '.html');

		$putNewContent = $fileObject->writeFile(StyleDir . '/' . $tmplate . '.html' , '<!--' . $safe_tage . '-->' . $htmlContent . '<!--' . $safe_tage . '-->' . $defultContent);	
		
		if($putNewContent)return true; else return false;
				
	}
	
	function assign($var , $value , $template_name=''){
	
		global $GET_GLOBAL_TEMPS_ASSIGN;
		
		_debug("$var assing , value : " . $value . "" , "green");

		if(!empty($template_name)){
			
			$GET_GLOBAL_TEMPS_ASSIGN[$var] = array($value , $template_name);
			
		}else{
			
			$GET_GLOBAL_TEMPS_ASSIGN[$var] = $value;

		}
		
	}
	
	function addHtmlToModule($htmlContent , $tmplate , $in_top=true , $safe_tage='Dont Edit' , $moduleFolder){
	
		global $config , $db;
	
		if($db->getfastTotal("select module_id from " . PFX . "modules where module_path='$moduleFolder'") == 0){ 
		
			_error($moduleFolder . ' Folder Does Not exists .. Check Your Module Path'); 
		
			_end();
			
		}
		
		if(!file_exists($config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html')){

			_error($tmplate . '.html Template File Does Not exists .. Check Your Template File'); 
		
			_end();
			
		}
			
		$objManager = new objectsManager;

		$fileObject = $objManager->getFilesObject();
				
		$defultContent = $fileObject->readFile($config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html');

		if($in_top){
				
			$putNewContent = $fileObject->writeFile($config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html' , '<!--' . $safe_tage . '-->' . "\n" . $htmlContent . "\n" . '<!--' . $safe_tage . '-->' . $defultContent);	
			
		}else{

			$putNewContent = $fileObject->writeFile($config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html' ,  $defultContent . '<!--' . $safe_tage . '-->' . "\n" . $htmlContent . "\n" . '<!--' . $safe_tage . '-->');			
				
		}
		if($putNewContent)return true; else return false;
				
	}
	
	// Delete Html Code form module template file
	function deleteHtmlFromModule($htmlContent , $tmplate , $in_top=true , $safe_tage='Dont Edit' , $moduleFolder){
	
		global $config , $db;
	
		if($db->getfastTotal("select module_id from " . PFX . "modules where module_path='$moduleFolder'") == 0){
			
			_error($moduleFolder . ' Folder Does Not exists .. Check Your Module Path'); 
		
			_end();
			
		}
			
		if(!file_exists($config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html')){

			_error($tmplate . '.html Template File Does Not exists .. Check Your Template File'); 
		
			_end();
			
		}
		
		$objManager = new objectsManager;

		$fileObject = $objManager->getFilesObject();
				
		$defultContent = $fileObject->readFile($config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html');
		$defultContent = str_replace('<!--' . $safe_tage . '-->' . "\n" . $htmlContent . "\n" . '<!--' . $safe_tage . '-->' , "" , $defultContent);
		
		$putNewContent = $fileObject->writeFile($config->modulesPath . '/' . $moduleFolder . '/templates/' . $tmplate . '.html' , $defultContent);	
		
		if($putNewContent)return true; else return false;
				
	}

	// safe ID request with close the document
	// (1) param [ type : int]
	// (2) param [ type : boolean , value : 'true or false']
	// return [int]
	function _id($number , $exit=false){
	
		global $logs;
		
		$id = intval(abs($number));
		
		if(!is_numeric($number) ){
			
				//$logs->addLog(1);
				
				if($exit == true){
				
					exit;	
				}
			}
		
		return $id;
	}
	
	function _caption($text){
	
		// Clean Html
		$text = strip_tags($text);
		
		// Make Break <br />
		$text = str_replace("\n" , "<br />\n" , $text);
		
		$find    = array(
			'~\[url=(.+?)](.+?)\[/url]~is',
			'~(^|\s)(http|https|ftp)(://\S+)~i',
			'~(^|\s)(www.+[a-z0-9-_.])~i',
			'~(^|\s)([a-z0-9-_.]+@[a-z0-9-.]+\.[a-z0-9-_.]+)~i',
			'~\[img](http|https|ftp)://(.+?)\[/img]~i',
		);
		$replace = array(
			'<a href="$1" target=_blank>$2</a>',
			'$1<a href="$2$3" target="_blank">$2$3</a>',
			'$1<a href="http://$2" target="_blank">$2</a>',
			'$1<a href="mailto:$2">$2</a>',
			'<img src="$1://$2" border="0" alt="$1://$2">',
		);
		
		$text = preg_replace($find , $replace , $text);
				
		return $text;	
	}
	
	// print basic string
	// (1) param [ type : string , value : any string ]
	// return [true]
	function _print($string){
		
		print($string);
		
	}
	
	// add Error Log (user Error) and Print Error Message
	// (1) param [ type : string , value : Error Message ]
	// return [true]
	function _error($string){
		
		global $logs;
		
		$logs->addLog(6 , "User Error { " . $string . "} in {". htmlspecialchars(addslashes(urldecode($_SERVER['REQUEST_URI']))) ."}");
		
		_print('<div style="text-align:center; padding:10px; font-size:18px; font-family:arial; font-width:bold; color:red;">' . $string . '</div>');
		
	}
	
	// add Error Log
	// (1) param [ type : string , value : Error Message ]
	// return [true]
	function _userError($string){
		
		global $logs;
		
		$logs->addLog(6 , "User Error { " . $string . "} in {". htmlspecialchars(addslashes(urldecode($_SERVER['REQUEST_URI']))) ."}");
				
	}
	
	function _end(){
	
		return(exit(1));	
	}
	
	function _setting($option){
	
		global $setting;
		
		return($setting->rows[$option]);
			
	}
	
	function interfaceLanguage(){
	
		global $db;
		
		$row = $db->getfastRows("select site_language from " . PFX . "core");
						
		$getDefaultLangCode = $db->getFastRows("select language_code from " . PFX . "languages where id='$row[site_language]'");
		$default = $getDefaultLangCode['language_code'];
								
		return($default);
			
	}
	
	function _url($url){
	
		global $setting;
		
		return($setting->rows['siteurl'] . '/' . $url);	
	}
	
	// Set Captcha Value
	function _setCaptcha(){
	
		$lastCaptcha = _getCaptcha();
		$lastCaptcha = base64_decode($lastCaptcha);
		$lastCaptcha = explode("<^_^>" , $lastCaptcha);
				
		// Check Last Captcha Creat Time
		if($lastCaptcha[1] < time()-10){
			
			// How Many Time This Captcha Used
			$_SESSION['elia_captcha_used'] = 1;
			
			// Set Automatic Captcha
			return($_SESSION['elia_captcha_code'] = base64_encode( substr(md5(time()) , 0 , 7) . '<^_^>' . time() ));
				
		}else{
		
			return(_getCaptcha());
				
		}

	}
	
	// get Captcha value
	function _getCaptcha(){
		
		return($_SESSION['elia_captcha_code']);
	}
	
	// check Captcha
	// (1) param [ type : string , value : captcha value]
	// return [true || false]
	function _checkCaptcha($captcha_code){
	
		$captchaInfo = base64_decode($captcha_code);
		$captchaInfo = explode("<^_^>" , $captchaInfo);
								
		if($captcha_code !== _getCaptcha()){
		
			return false;	
		
		}else if($_SESSION['elia_captcha_used'] != 1 and ($captchaInfo[1]+(20*$_SESSION['elia_captcha_used'])) > time()){
			
			return false;
		}
		
		// add Captcha Use Time 
		$_SESSION['elia_captcha_used'] = $_SESSION['elia_captcha_used']+1;
		
		return true;
	}
	
	// Real Ip function
	function _ip(){
		
		// get Ip
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $_ENV['REMOTE_ADDR'];
			
		// return with ip name
		return($ip);
		
	}

	// Elia Version Name	
	function _elia_version(){
	
		global $setting;
		
		return($setting->rows['version_name']);
				
	}
	
	// Elia Version Code
	function _elia_code(){
	
		global $setting;
		
		return($setting->rows['version_code']);
				
	}
	
	// Elia Copyright
	function _elia_copyright(){
		
		// You Cant Remove or Edit this line
		_print('<div style="clear:both;"></div><div style="color:#000; padding:5px; margin-top:10px; margin-bottom:10px; width:200px; margin-right:auto; margin-left:auto;">Powerd by : <a href="http://www.wealia.net">Elia-CMS</a></div>');
			
	}
	
	
	function _lang($code , $language_code=''){
	
		global $db , $setting;
		
		if(!empty($language_code)){
			
			$default = $language_code;
	
		}else{
			
			$lang_id = _setting("site_language");
			$getDefaultLangCode = $db->getFastRows("select * from " . PFX . "languages where id='$lang_id'");
			$default = $getDefaultLangCode['language_code'];

		}
		
		$getWord = $db->query("select word_value from " . PFX . "language_words 
									where word_name='$code' and 
									language_code='$default'
									limit 1");
		
		$rows= $db->rows($getWord);
		
		return($rows['word_value']);	
	}
	
	function _mediaMimeType($file)
	{

			$mime_types = array(
			'avi' => 'video/avi' , 		'mp4' => 'video/mp4' , 		'flv' => 'video/x-flv' ,
 			'mod' => 'audio/mod', 		'mod' => 'audio/x-mod', 	'moov' => 'video/quicktime',
			'mov' => 'video/quicktime', 'movie' => 'video/x-sgi-movie', 'mp2' => 'audio/mpeg', 
			'mp2' => 'audio/x-mpeg',	'mp2' => 'video/mpeg', 			'mp2' => 'video/x-mpeg', 
			'mp2' => 'video/x-mpeq2a',  'mp3' => 'audio/mpeg3',			'mp3' => 'audio/x-mpeg-3', 
			'mp3' => 'video/mpeg', 		'mp3' => 'video/x-mpeg', 		'mpa' => 'audio/mpeg',
			'mpa' => 'video/mpeg', 		'mpc' => 'application/x-project', 'mpe' => 'video/mpeg', 
			'mpeg' => 'video/mpeg'
			);
			
			$extension = strtolower(end(explode('.',$file)));
	
			return $mime_types[$extension];
	}
	
	function _get($name){
	
		$request = $_REQUEST[$name];
		
		return( strip_tags(trim($request)));	
	
	}
	
	function _enkey($string){
		
		return(base64_encode( 
			   base64_encode( rand(1111,9999)) . '-' . 
			   base64_encode($string) . '-' . 
			   base64_encode( rand(1111,9999)) ));	
	}

	function _dekey($string){
	
		$string = base64_decode($string);
		$string = explode("-" , $string);
		
		return base64_decode($string[1]);
	}
	
	function _debug($message , $color , $request=''){
	
		global $db , $setting , $config;
		
		$time = time();
		$ip = _ip();
		if(empty($request)) $request = $_SERVER['REQUEST_URI'];
		
		if($db->getfastTotal("select id from " . PFX . "debug where time=$time and message='$message' and color='$color' and request='$request' and ip='$ip'") == 0){
			
			$insert = $db->query("insert into " . PFX . "debug values('' , '$time' , '$message' , '$color' , '$request' , '$ip' , '1')");	
	
		}
	}

	function _encodeDocument($content){
	
		$lines = explode("\n" , $content);
		
		$return = "";
		
		foreach($lines as $var){
		
			$return .= base64_encode($var) . "\n";	
		}
		
		return base64_encode($return);
		
	 }
	 
	function _decodeDocument($content){
		
			$lines = explode("\n" , base64_decode($content) );
			
			$return = "";
			
			foreach($lines as $var){
			
				$return .= base64_decode($var) . "\n";	
			}
			
			return $return;
			
	 }
 
	function setvar($name , $value){
		
		return $GLOBALS["ELIA_" . $name] = $value;	
	
	}
 
	function getvar($name){
		
		return $GLOBALS["ELIA_" . $name];	
	
	}	
	
	function _safeString($string){
		
		return(strip_tags(trim(addslashes($string))));
	}
	
	function _safeInt($int){
		
		return(intval(abs($int)));
	}

	function _safeGet($get){
		
		return( mysql_real_escape_string(strip_tags(trim($get))) );
	}
	
	function groupStatement($groupid , $option){
	
		global $db;
		
		if($groupid == "THIS"){ $groupid = base64_decode(intval(abs($_SESSION['admin_elia_groupID']))); }
		
		$row = $db->getfastRows("select $option from " . PFX . "groups where group_id=" . $groupid);	
	
		return($row[$option]);
	
	}
		
	/**
	 * Returns whether or not the user is using a mobile device.
	 *
	 * @return bool
	 */
	function isMobileBrowser()
	{
		static $isMobileBrowser = null;
	
		if (is_null($isMobileBrowser)) {
	
			$userAgent = $_SERVER["HTTP_USER_AGENT"];
			$isMobileBrowser = (preg_match("/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i", $userAgent) || preg_match("/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i", substr($userAgent, 0, 4)));
	
		}
	
		return $isMobileBrowser;
	}

	function countryISO(){
		
		return  array('AF' , 'AL' , 'DZ' , 'AS' , 'AD' , 'AO' , 'AI' , 'AQ' , 'AG' , 'AR' , 'AM' , 'AW' , 'AU' , 'AT' , 'AZ' , 'BS' , 'BH' ,
					  'BD' , 'BB' , 'BY' , 'BE' , 'BZ' , 'BJ' , 'BM' , 'BT' , 'BO' , 'BA' , 'BW' , 'BV' , 'BR' , 'IO' , 'BN' , 'BG' , 'BF' ,
					  'BI' , 'KH' , 'CM' , 'CA' , 'CV' , 'KY' , 'CF' , 'TD' , 'CL' , 'CN' , 'CX' , 'CC' , 'CO' , 'KM' , 'CG' , 'CD' , 'CK' , 
					  'CR' , 'CI' , 'HR' , 'CU' , 'CY' , 'CZ' , 'DK' , 'DJ' , 'DM' , 'DO' , 'EC' , 'EG' , 'SV' , 'GQ' , 'ER' , 'EE' , 'ET' , 
					  'FK' , 'FO' , 'FJ' , 'FI' , 'FR' , 'GF' , 'PF' , 'TF' , 'GA' , 'GM' , 'GE' , 'DE' , 'GH' , 'GI' , 'GR' , 'GL' , 'GD' , 
					  'GP' , 'GU' , 'GT' , 'GG' , 'GN' , 'GW' , 'GY' , 'HT' , 'HM' , 'HN' , 'HK' , 'HU' , 'IS' , 'IN' , 'ID' , 'IR' , 'IQ' , 
					  'IE' , 'IM' , 'IL' , 'IT' , 'JM' , 'JP' , 'JE' , 'JO' , 'KZ' , 'KE' , 'KI' , 'KP' , 'KR' , 'KW' , 'KG' , 'LA' , 'LV' , 
					  'LB' , 'LS' , 'LR' , 'LY' , 'LI' , 'LT' , 'LU' , 'MO' , 'MK' , 'MG' , 'MW' , 'MY' , 'MV' , 'ML' , 'MT' , 'MH' , 'MQ' , 
					  'MR' , 'MU' , 'YT' , 'MX' , 'FM' , 'MD' , 'MC' , 'MN' , 'ME' , 'MS' , 'MA' , 'MZ' , 'MM' , 'NA' , 'NR' , 'NP' , 'NL' , 
					  'AN' , 'NC' , 'NZ' , 'NI' , 'NE' , 'NG' , 'NU' , 'NF' , 'MP' , 'NO' , 'OM' , 'PK' , 'PW' , 'PS' , 'PA' , 'PG' , 'PY' , 
					  'PE' , 'PH' , 'PN' , 'PL' , 'PT' , 'PR' , 'QA' , 'RE' , 'RO' , 'RU' , 'RW' , 'BL' , 'SH' , 'KN' , 'LC' , 'MF' , 'PM' , 
					  'VC' , 'WS' , 'SM' , 'ST' , 'SA' , 'SN' , 'RS' , 'SC' , 'SL' , 'SG' , 'SK' , 'SI' , 'SB' , 'SO' , 'ZA' , 'GS' , 'ES' , 
					  'LK' , 'SD' , 'SR' , 'SJ' , 'SZ' , 'SE' , 'CH' , 'SY' , 'TW' , 'TJ' , 'TZ' , 'TH' , 'TL' , 'TG' , 'TK' , 'TO' , 'TT' , 
					  'TN' , 'TR' , 'TM' , 'TC' , 'TV' , 'UG' , 'UA' , 'AE' , 'GB' , 'US' , 'UM' , 'UY' , 'UZ' , 'VU' , 'VA' , 'VE' , 'VN' , 
					  'VG' , 'VI' , 'WF' , 'EH' , 'YE' , 'ZM' , 'ZW');	
	}
	
	function dateMonths(){
	
		return array('JANUARY' , 'FEBRUARY' , 'MARCH' , 'APRIL' , 'MAY' , 'JUNE' , 'JULY' , 'SEPTEMBER' , 'OCTOBER' , 'NOVEMBER' , 'DECEMBER');	
	
	}
	
	function debbocde($bbcode){
		
		/* Matching codes */
		$urlmatch = "([a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+)";
		
		/* Basically remove HTML tag's functionality */
		$bbcode = htmlspecialchars($bbcode);
		
		/* Replace "special character" with it's unicode equivilant */
		$match["special"] = "/\�/s";
		$replace["special"] = '&#65533;';
		
		/* Bold text */
		$match["b"] = "/\[b\](.*?)\[\/b\]/is";
		$replace["b"] = "<b>$1</b>";
		
		/* Italics */
		$match["i"] = "/\[i\](.*?)\[\/i\]/is";
		$replace["i"] = "<i>$1</i>";
		
		/* Underline */
		$match["u"] = "/\[u\](.*?)\[\/u\]/is";
		$replace["u"] = "<span style=\"text-decoration: underline\">$1</span>";
		
		/* Typewriter text */
		$match["tt"] = "/\[tt\](.*?)\[\/tt\]/is";
		$replace["tt"] = "<span style=\"font-family:monospace;\">$1</span>";
		
		$match["ttext"] = "/\[ttext\](.*?)\[\/ttext\]/is";
		$replace["ttext"] = "<span style=\"font-family:monospace;\">$1</span>";
		
		/* Strikethrough text */
		$match["s"] = "/\[s\](.*?)\[\/s\]/is";
		$replace["s"] = "<span style=\"text-decoration: line-through;\">$1</span>";
		
		/* Color (or Colour) */
		$match["color"] = "/\[color=([a-zA-Z]+|#[a-fA-F0-9]{3}[a-fA-F0-9]{0,3})\](.*?)\[\/color\]/is";
		$replace["color"] = "<span style=\"color: $1\">$2</span>";
		
		$match["colour"] = "/\[colour=([a-zA-Z]+|#[a-fA-F0-9]{3}[a-fA-F0-9]{0,3})\](.*?)\[\/colour\]/is";
		$replace["colour"] = $replace["color"];
		
		/* Size */
		$match["size"] = "/\[size=([0-9]+(%|px|em)?)\](.*?)\[\/size\]/is";
		$replace["size"] = "<span style=\"font-size: $1;\">$3</span>";
		
		/* Images */
		$match["img"] = "/\[img\]".$urlmatch."\[\/img\]/is";
		$replace["img"] = "<img src=\"$1\" />";
		
		/* Links */
		$match["url"] = "/\[url=".$urlmatch."\](.*?)\[\/url\]/is";
		$replace["url"] = "<a href=\"$1\">$2</a>";
		
		$match["surl"] = "/\[url\]".$urlmatch."\[\/url\]/is";
		$replace["surl"] = "<a href=\"$1\">$1</a>";
		
		/* Quotes */
		$match["quote"] = "/\[quote\](.*?)\[\/quote\]/ism";
		$replace["quote"] = "<div class=\"bbcode-quote\">�$1�</div>";
		
		$match["quote"] = "/\[quote=(.*?)\](.*?)\[\/quote\]/ism";
		$replace["quote"] = "<div class=\"bbcode-quote\"><span class=\"bbcode-quote-user\" style=\"font-weight:bold;\">$1 said:</span><br />�$2�</div>";
		
		/* Parse */
		$bbcode = preg_replace($match, $replace, $bbcode);
		
		/* New line to <br> tag */
		$bbcode=nl2br($bbcode);
		
		/* Code blocks - Need to specially remove breaks */
		function pre_special($matches)
		{
				$prep = preg_replace("/\<br \/\>/","",$matches[1]);
				return "<pre>$prep</pre>";
		}
		$bbcode = preg_replace_callback("/\[code\](.*?)\[\/code\]/ism","pre_special",$bbcode);
		
		
		/* Remove <br> tags before quotes and code blocks */
		$bbcode=str_replace("<br />","",$bbcode);
		$bbcode=str_replace("","",$bbcode); //Clean up any special characters that got misplaced...
		
		/* Return parsed contents */
		return $bbcode;

			
	}
	
	// 
	function _back(){
	
		return ($_SERVER['HTTP_REFERER']);
			
	}
	
	// Initialize ObjectManager
	function emanager(){
	
		return(new objectsManager());	
	}
	
	// get Php Version
	function phpv(){
	
		return(phpversion());	
	}
	
	// get Current Time
	function _ctime(){
	
		return(time());
			
	}
	
	function _jsVerbs($params=array() , $value=''){
	
		if(is_array($params) && empty($value)){
							
			if(count($params) !== 0){
				
				$RETURN = "<script type=\"text/javascript\">\n";
					
				foreach($params as $key => $var){
						
					$RETURN .= "\t var $key = '$var';\n";	
				}
					
				$RETURN .= "</script>\n";
			}
									  
		}else{
			
			$RETURN = "<script type=\"text/javascript\">\n";
			$RETURN .= "\t var $params = '$value';\n";	
			$RETURN .= "</script>\n";			
		}
		
		echo($RETURN);

	}
	
?>