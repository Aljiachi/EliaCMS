<?php 

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}
	
	// Hide All Templates
	define('templatesShown' , false);
		
	if(isset($_POST['addcomment'])){
					
			$name = strip_tags(trim($_POST['name']));

			$captcha_code = strip_tags(trim($_POST['captcha_code']));
						
			$text = strip_tags($_POST['text']);
			
			$active = 0;
			
			$ip   = $_SERVER['REMOTE_ADDR'];
			
			$time = time();
			
			$key = _dekey(intval($_POST['key']));
						
			if(!_checkCaptcha($captcha_code)){
			
				_print("<div class='red'>" . _lang("ERROR_TRY_AGAIN") . "</div>");
				
				exit;	
			}
			
			if(!empty($name) and !empty($text)){
				
				$insert = $db->query("insert into `videos_module_comments` values('' , '$blog_id' , '$name' , '$time' , '$text' , '$ip' , '$active')") or die(mysql_error());
				
				if($insert){
				
					_print("<div class='green'>" . _lang("COMMENT_POSTED") . "</div>");	
				}
				
			}else{
				
				_print("<div class='red'>" . _lang("FILEDS_EMPTY") . "</div>");

			}
			
	}
		
?>