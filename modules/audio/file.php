<?php 

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}

	$id = _id($_REQUEST['id']);
	
	$query = $db->query("select * from audio_module_files where id = $id");
	
	if($db->total($query) == 0){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
		exit;	
	}
	
	$rows  = $db->rows($query);
		
	$section = $db->query("select * from audio_module_sections where id=" . $rows['catid']);
		
	$rowsSection = $db->rows($section);
	
	$update = $db->query("update audio_module_files set hits=hits+1 where id = $id");

	$comments = $db->query("select * from audio_module_comments where active='1' and topic_id ='$id' order by id asc");

	$moduleSetting = $this->moduleSetting();

	$centerMargens = "";
		
	if($moduleSetting->getLeftMenu() == 0){
		
		_print('<div style="float:left; width:250px;">');

			$menu->getLeft();

		_print('</div>');
		
		$centerMargens .= 'margin-left:252px; ';
	}
	
	if($moduleSetting->getRightMenu() == 0){
		
		_print('<div style="float:right; width:250px;">');
		
			$menu->getRight();
		
		_print('</div>');

		$centerMargens .= 'margin-right:252px; ';
	
	}
	
	_print('<div style="'.$centerMargens.'">');
	
	$d = explode("-" , $rows['date']);
	
	$this->getTemplate("audio_view" , array(
		'time' => $rows['time'] ,
		'date' => date("Y/m/d" , $rows['time']) ,
		'siteTitle' => $setting->rows['title'] , 
		'hits' => $rows['hits'] , 
		'downloads' => $rows['downloads'] , 
		'file' => $rows['file'] , 
		'title' => $rows['title'] , 
		'comments' => $comments ,
		'id' => $rows['id'] ,
		'sectionPhoto' => $rowsSection['photo'] , 
		'sectionId' => $rowsSection['id'] , 
		'sectionName' => $rowsSection['title'] , 
		'caption' => $rows['caption'] ,
		'captcha' => _setCaptcha() ,
		'modSetting_addcomments' => $moduleSetting->getAddComments() , 
		'totalComments' => $db->getfastTotal("select id from audio_module_comments where topic_id=" . $rows['id'])
	));
	

	_print('</div>');
		
?>
