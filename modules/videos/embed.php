<?php 

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');

	}

	// Hide All Templates
	define('templatesShown' , false);
	
	$id = _id($_REQUEST['id']);
	
	$query = $db->query("select * from videos_module_files where id = $id");
	
	if($db->total($query) == 0){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
		exit;	
	}
	
	$rows  = $db->rows($query);
		
	$section = $db->query("select * from videos_module_sections where id=" . $rows['catid']);
		
	$rowsSection = $db->rows($section);
	
	$update = $db->query("update videos_module_files set hits=hits+1 where id = $id");
	
	$moduleSetting = $this->moduleSetting();

	$d = explode("-" , $rows['date']);
	
	$this->getTemplate("videos_embed" , array(
		'time' => $rows['time'] ,
		'date' => date("Y/m/d" , $rows['time']) ,
		'siteTitle' => $setting->rows['title'] , 
		'file' => $rows['file'] , 
		'photo' => $rows['photo'] , 
		'likes' => $rows['rat_good'] , 
		'dislikes' => $rows['rat_bad'] ,
		'title' => $rows['title'] , 
		'id' => $rows['id'] ,
		'sectionPhoto' => $rowsSection['photo'] , 
		'sectionId' => $rowsSection['id'] , 
		'sectionName' => $rowsSection['title'] , 
		'caption' => $rows['caption'] 
		));
	
?>