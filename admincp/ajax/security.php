<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");
	
	$adinc->_checkSession();
	
		if($_REQUEST['action'] == "delete_log"){
			
				if(!$update){ print(mysql_error()); }else{
						
					$adinc->greenAlert( $adinc->_lang("DELETE_DONE"));
				
				}
					
		}
		
		
			$id = intval(abs($_REQUEST['id']));
				
			$update = $db->query("delete from " . PFX . "logs where id=$id");
			
	if($_REQUEST['action'] == "delete_ip"){
		
			$id = intval(abs($_REQUEST['id']));
				
			$update = $db->query("delete from " . PFX . "blocks_ip where id=$id");
			
				if(!$update){ print(mysql_error()); }else{
						
					$adinc->greenAlert( $adinc->_lang("DELETE_DONE"));
		
				}
					
		}
		
	if($_REQUEST['action'] == "debug"){
		
		$type = trim($_REQUEST['type']);
		
		if(isset($_REQUEST['type']) and !empty($type)){
		
			$debugs = $db->query("select * from " . PFX . "debug where color='$type' order by id desc");

		}else{

			$debugs = $db->query("select * from " . PFX . "debug order by id desc");
			
		}
		
		function displayDebug($message , $color , $request , $time , $ip){
		
			$HTML = "<div class=\"debug_$color\" style=' padding:5px; margin:3px;text-align:left;font-family:\"Lucida Console\", Monaco, monospace' dir=\"ltr\"><span>" . $message . '</span> <div style="margin-left:40px;">('. $ip . ' => ' . $request.') - ' . date("h:i" , $time) . '</div></div>';
		
			return $HTML;
		}
		
		while($rowsdebug = $db->rows($debugs)){
		
			print displayDebug($rowsdebug['message'] , $rowsdebug['color'] , $rowsdebug['request'] , $rowsdebug['time'] , $rowsdebug['ip']);
			
		}
		
	}
	
	if($_REQUEST['action'] == "edit_setting"){

		$request_safe = htmlspecialchars($_POST['request_safe']);
		$request_safe = str_replace("\n" , "|" , $request_safe);
		
		$post_safe = htmlspecialchars($_POST['post_safe']);
		$post_safe = str_replace("\n" , "|" , $post_safe);

		$unsafe_functions = htmlspecialchars($_POST['unsafe_functions']);
		$unsafe_functions = str_replace("\n" , "|" , $unsafe_functions);

		$auto_block_time = intval(abs($_POST['auto_block_time']));
		
		$auto_block = intval(abs($_POST['auto_block']));
			
		$links_in_comments = intval(abs($_POST['links_in_comments']));
		
		$update = $db->query("update " . PFX . "core set 
									request_safe='$request_safe' ,
									post_safe='$post_safe' , 
									unsafe_functions='$unsafe_functions' , 
									auto_block_time='$auto_block_time' , 
									auto_block='$auto_block' ,
									links_in_comments='$links_in_comments'");
		
			if(!$update){ print(mysql_error()); }else{
					
				$adinc->greenAlert( $adinc->_lang("UPDATE_DONE"));
			}
	}
?>