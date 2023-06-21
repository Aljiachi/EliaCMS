<?php 

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}

	$templatesShowin = false;
	
	$id = intval(abs($_REQUEST['id']));
	
	$query = $db->query("select * from blog_module_topics where id = $id");
	
	if($db->total($query) == 0){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
		exit;	
	}
	
	$rows  = $db->rows($query);
	

	$d = explode("-" , $rows['date']);
	
	$this->getTemplate("blog_print" , array(
		'date' => $d[0] ,
		'time' => $d[1] ,
		'siteTitle' => $setting->rows['title'] , 
		'views' => $rows['views'] , 
		'photo' => $rows['photo'] , 
		'more' => $rows['more'] , 
		'title' => $rows['title'] , 
		'comments' => $comments ,
		'id' => $rows['id'] , 
		'totalComments' => $db->getfastTotal("select id from blog_module_comments where topic_id=" . $rows['id']) , 
		'text' => str_replace("\n" , "<br />" , $rows['all'])
	));
	
?>
