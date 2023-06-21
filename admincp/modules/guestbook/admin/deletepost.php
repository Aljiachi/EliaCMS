 <?php


	$news_id = intval(abs($_GET['post_id']));
	
	$Delete_News_Query_Access = mysql_query("select id from module_guestbook where id = $news_id"); 
	
	if(mysql_num_rows($Delete_News_Query_Access) == true){
	
		$Delete_News_Query = mysql_query("delete from module_guestbook where id = $news_id");
		
			if($Delete_News_Query){
			
			//	print "<div class='alert'>تمت العملية بنجاح</div>";
				
			}
	}else{
	
		//header("location:" . $_SERVER["HTTP_REFERER"]);

	}
	
?>