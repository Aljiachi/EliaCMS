<?php 

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}

	addJs(_url("jscript/player/jwplayer.js"));

	$id = _id($_REQUEST['id']);
	
	$query = $db->query("select * from videos_module_files where id = $id");
	
	if($db->total($query) == 0){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
		exit;	
	}
	
	$rows  = $db->rows($query);
		
	addMeta(array(
	'name' => 'keywords' , 
	'content' => $rows['keywords']
	));
	
	addMeta(array(
	'property' => 'og:url' , 
	'content' => $setting->rows['siteurl']
	));
	
	$section = $db->query("select * from videos_module_sections where id=" . $rows['catid']);
		
	$rowsSection = $db->rows($section);
	
	$update = $db->query("update videos_module_files set hits=hits+1 where id = $id");

	$comments = $db->query("select * from videos_module_comments where active='1' and topic_id ='$id' order by id asc");

	$fr = explode("," , $rows['keywords']);
	
	$SQL = "SELECT * FROM  videos_module_files where title LIKE '%#$rows[title]#s%'";
							
	foreach($fr as $varFr){
	
		if(!empty($varFr) ){
			
			$SQL .= " or title LIKE '%$varFr%'";	
	
		}
	}
	
	$SQL .= " and id != $id order by title";
	
	$SQL2 = "SELECT * FROM videos_module_files where id != $id order by id desc";
	
	$viewMore = $db->query($SQL . " LIMIT 12");

	assign("relatedVideos" ,  $viewMore);
	
	$moduleSetting = $this->moduleSetting();

	$centerMargens = "";
	
	print '<div style="'.$centerMargens.'">';
	
	$d = explode("-" , $rows['date']);
	
	if(in_array(md5($id) , explode("," , $_COOKIE['YAYKSSARA']) )){
	
		$isLiked = false;	

	}else{

		$isLiked = true;	
		
	}
	
	if(in_array(md5($id) , explode("," , $_COOKIE['YAYKSIARA']) )){
	
		$isDisLiked = false;	
		
	}else{
	
		$isDisLiked = true;	
	
	}
		
	$this->getTemplate("videos_watch" , array(
		'date' => date("Y/m/d" , $rows['time']) ,
		'siteTitle' => $setting->rows['title'] , 
		'hits' => $rows['hits'] , 
		'downloads' => $rows['downloads'] , 
		'file' => $rows['file'] , 
		'photo' => $rows['photo'] , 
		'likes' => $rows['rat_good'] , 
		'dislikes' => $rows['rat_bad'] ,
		'title' => $rows['title'] , 
		'comments' => $comments ,
		'id' => $rows['id'] ,
		'key' =>   _enkey($rows['id']) ,
		'sectionPhoto' => $rowsSection['photo'] , 
		'sectionId' => $rowsSection['id'] , 
		'sectionName' => $rowsSection['title'] , 
		'caption' => _caption($rows['caption']) ,
		'modSetting_addcomments' => $moduleSetting->getAddComments() , 
		'isLiked' => $isLiked , 
		'captcha' => _setCaptcha() ,
		'isDisLiked' => $isDisLiked ,
		'totalLiks' => ($rows['rat_good']+$rows['rat_bad']) ,
		'liksProgress' =>  ($rows['rat_good']+$rows['rat_bad'])>0?ceil($rows['rat_good']/($rows['rat_good']+$rows['rat_bad'])*100):0 ,
		'totalComments' => $db->getfastTotal("select id from audio_module_comments where topic_id=" . $rows['id'])
		));
	
	print '</div>';	
	
?>
