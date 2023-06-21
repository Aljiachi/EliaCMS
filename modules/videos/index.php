<?php
	
	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}

	addJs(_url("jscript/jquery.carouFredSe.js"));
	
	addJs(_url("jscript/helper-plugins/jquery.mousewheel.min.js"));

	addJs(_url("jscript/helper-plugins/jquery.touchSwipe.min.js"));
	
	addJs(_url("jscript/helper-plugins/jquery.ba-throttle-debounce.min.js"));
		
	$query	=	$db->query("select * from videos_module_sections");
		
	$viewMore = $db->query("SELECT * FROM videos_module_files order by id desc LIMIT 12");

	assign("relatedVideos" ,  $viewMore);
	
	$moduleSetting = $this->moduleSetting();

		while($rows = $db->rows($query)){ 
			
			$files = $db->query("select * from videos_module_files where catid=" . $rows['id'] . " order by id desc limit 6");
					
			$this->getTemplate("videos_index" , array(
				'siteTitle' => $setting->rows['title'] , 
				'photo' => $rows['photo'] , 
				'title' => $rows['title'] , 
				'files' => $files ,
				'id' => $rows['id'] , 
				'totalFiles' => $db->getfastTotal("select id from audio_module_files where catid=" . $rows['id'])
				));
	  }
			
		
?>