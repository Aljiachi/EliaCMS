<?php

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}

	$query	=	$db->query("select * from audio_module_sections");
		
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

		while($rows = $db->rows($query)){ 
			
			$files = $db->query("select * from audio_module_files where catid=" . $rows['id'] . " order by id desc limit 6");
					
			$this->getTemplate("audio_index" , array(
				'siteTitle' => $setting->rows['title'] , 
				'photo' => $rows['photo'] , 
				'more' => $rows['more'] , 
				'title' => $rows['title'] , 
				'files' => $files ,
				'id' => $rows['id'] , 
				'totalFiles' => $db->getfastTotal("select id from audio_module_files where catid=" . $rows['id'])
				));
	  }
			
	_print('</div>');
		
?>