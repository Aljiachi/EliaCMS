<?php

	if(script_run !== true){
		
		die('<h1 align="center">404 . الصفحة المطلوبة غير موجودة</h1>');
	}
	
	$catid = _id($_REQUEST['catid']);
	
	$section = $db->query("select * from audio_module_sections where id=$catid");

	if($catid == 0 || !isset($_REQUEST['catid']) || $db->total($section) == 0){exit;}
		
	$rowsSection = $db->rows($section);
	
	$objectsManager = new objectsManager;
	$pages = $objectsManager->getPaginatorObject();
	
	$pages->SetPerPage(20);
	
	$query	=	$pages->CreatePaginator("select * from audio_module_files where catid=$catid order by id desc");
			
	$topFiles= $db->query("select * from audio_module_files order by hits desc limit 10");
	$topSectionfiles = $db->query("select * from audio_module_files where catid=$catid order by hits desc limit 10");
		
	$moduleSetting = $this->moduleSetting();

	$centerMargens = "";
		
	if($moduleSetting->getLeftMenu() == 0){
		
		print '<div style="float:left; width:250px;">';
		$menu->getLeft();
		print '</div>';
		
		$centerMargens .= 'margin-left:252px; ';
	}
	
	if($moduleSetting->getRightMenu() == 0){
		
		print '<div style="float:right; width:250px;">';
		$menu->getRight();
		print '</div>';

		$centerMargens .= 'margin-right:252px; ';
	
	}
	
	print '<div style="'.$centerMargens.'">';

			$totalHits = 0;
			
			$hitsQuery = $db->query("select hits from audio_module_files where catid=$catid");
			
			while($rowsHits = $db->rows($hitsQuery)){
				
				$totalHits = $totalHits + $rowsHits['hits'];
			}
			
			$totalHits = number_format($totalHits);
			
			$this->getTemplate("audio_section" , array(
			'siteTitle' => $setting->rows['title'] , 
			'sectionPhoto' => $rowsSection['photo'] , 
			'sectionId' => $rowsSection['id'] , 
			'sectionName' => $rowsSection['title'] , 
			'filesQuery' => $query , 
			'totalFiles' => number_format($db->getfastTotal("select id from audio_module_files where catid=" . $catid)) , 
			'totalHits' => $totalHits , 
			'topFiles' => $topFiles , 
			'topSectionFiles' => $topSectionfiles ,
			'pagination' => $pages->GetPagination() 
			));
			
	print '</div>';
		
?>