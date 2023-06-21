 <?php


	$news_id = intval(abs($_GET['news_id']));
	
	$reload  = strip_tags($_GET['reload']);

	$Delete_News_Query_Access = mysql_query("select news_id from module_news where news_id = $news_id"); 
	
	if(mysql_num_rows($Delete_News_Query_Access) == true){
	
		$Delete_News_Query = mysql_query("delete from module_news where news_id = $news_id");
		
			if($Delete_News_Query){
			
				print "<div class='alert'>تمت العملية بنجاح</div>";
				
				if($reload){
				
					$this->reload("mod.php?mod=news");
			
				}else{
				
					$this->reload($_SERVER["HTTP_REFERER"]);
				}
			}
	}else{
	
		header("location:" . $_SERVER["HTTP_REFERER"]);

	}
	
?>