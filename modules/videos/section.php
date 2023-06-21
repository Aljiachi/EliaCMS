<?php


	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}
	
	$catid = _id($_REQUEST['catid']);
	
	$section = $db->query("select * from videos_module_sections where id=$catid");

	if($catid == 0 || !isset($_REQUEST['catid']) || $db->total($section) == 0){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
		
		}
		
	$rowsSection = $db->rows($section);
	
	$objectsManager = new objectsManager;	
	$pages = $objectsManager->getPaginatorObject();
	
	$pages->SetPerPage(20);
	
	$query	=	$pages->CreatePaginator("select * from videos_module_files where catid=$catid order by id desc");
	
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

			$totalHits = 0;
			
			$hitsQuery = $db->query("select hits from videos_module_files where catid=$catid");
			
			while($rowsHits = $db->rows($hitsQuery)){
				
				$totalHits = $totalHits + $rowsHits['hits'];
			}
			
			$totalHits = number_format($totalHits);
			
		$this->getTemplate("videos_section" , array(
			'siteTitle' => $setting->rows['title'] , 
			'siteUrl' => $setting->rows['siteurl'] ,
			'sectionPhoto' => $rowsSection['photo'] , 
			'sectionId' => $rowsSection['id'] , 
			'sectionName' => $rowsSection['title'] , 
			'filesQuery' => $query , 
			'totalFiles' => number_format($db->getfastTotal("select id from audio_module_files where catid=" . $catid)) , 
			'totalHits' => $totalHits , 
			'pagination' => $pages->GetPagination() 
			));
				
	_print('</div>');
	
?>