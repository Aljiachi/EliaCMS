<?php 

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}

	$id = intval(abs($_REQUEST['id']));
	
	$query = $db->query("select * from blog_module_topics where id = $id");
	
	if($db->total($query) == 0){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
		exit;	
	}
	
	$rows  = $db->rows($query);
	
	$update = $db->query("update blog_module_topics set views=views+1 where id = $id");

	$comments = $db->query("select * from blog_module_comments where active='1' and topic_id ='$id' order by id asc");

	print '<div style="float:left; width:250px;">';
	$menu->getLeft();
	print '</div>';
	
	print '<div style="margin-left:252px;">';
	
	$d = explode("-" , $rows['date']);
	
	$this->getTemplate("blog_view" , array(
		'date' => $d[0] , 
		'time' => $d[1] ,
		'siteTitle' => $setting->rows['title'] , 
		'views' => $rows['views'] , 
		'photo' => $rows['photo'] , 
		'more' => $rows['more'] , 
		'title' => $rows['title'] , 
		'comments' => $comments ,
		'id' => $rows['id'] , 
		'captcha' => _setCaptcha() ,
		'totalComments' => $db->getfastTotal("select id from blog_module_comments where topic_id=" . $rows['id']) , 
		'text' => str_replace("\n" , "<br />" , $rows['all'])
	));
	

	print '</div>';	
?>
