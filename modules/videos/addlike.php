<?php 

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}
	
	// Hide All Templates
	define('templatesShowin' , false);
		
	if(isset($_POST['addlike'])){
					
			$action = strip_tags(trim($_POST['action']));
						
			$videoid = intval(abs($_POST['videoid']));
						
			$ip   = $_SERVER['REMOTE_ADDR'];
			
			$getIdsLike = explode("," , $_COOKIE['YAYKSSARA']);
			$getIdsDisLike = explode("," , $_COOKIE['YAYKSIARA']);

			if(!in_array(md5($videoid) , $getIdsLike) and !in_array(md5($videoid) , $getIdsDisLike)){
								
				if(!empty($videoid) and !empty($action)){
	
					if($action == "like"){
				
						$addLike = $db->query("update videos_module_files set rat_good=rat_good+1 where id=$videoid");
	
						$ids = $_COOKIE['YAYKSSARA'] ;
						
						setcookie("YAYKSSARA", $ids . ',' . md5($videoid) ,  time()+31104000);
						
					}else if($action == "dislike"){
	
						$addLike = $db->query("update videos_module_files set rat_bad=rat_bad+1 where id=$videoid");
	
						$ids = $_COOKIE['YAYKSIARA'] ;
						
						setcookie("YAYKSIARA", $ids . ',' . md5($videoid) ,  time()+31104000);					
					}			
						
			}
			
		}else{
		
			_print('error_2');	
		}
			
	}
		
?>