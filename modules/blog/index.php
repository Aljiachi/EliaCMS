<?php 

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}
	
	$objectsManager = new objectsManager;
	
	$pages = $objectsManager->getPaginatorObject();
	
	$pages->SetPerPage(10);
	
	$query	=	$pages->CreatePaginator("select * from blog_module_topics");
		
	while($rows = $db->rows($query)){ 
			
		$d = explode("-" , $rows['date']);
			
		$this->getTemplate("blog_index" , array(
				'date' => $d[0] , 
				'time' => $d[1] ,
				'siteTitle' => $setting->rows['title'] , 
				'views' => $rows['views'] , 
				'photo' => $rows['photo'] , 
				'more' => $rows['more'] , 
				'title' => $rows['title'] , 
				'comments' => $db->getfastTotal("select id from blog_module_comments where topic_id=" . $rows['id']) , 
				'id' => $rows['id']
				));
	
	  }
				
	print $pages->GetPagination();

?>