<?php

	$news_id = intval(abs($_GET['post_id']));

	$active = intval(abs($_REQUEST['active']));

	$Delete_News_Query_Access = mysql_query("select id from module_guestbook where id = $news_id"); 
	
	if(mysql_num_rows($Delete_News_Query_Access) == true){
	
		$Update = mysql_query("update module_guestbook set  active = $active where id = $news_id");
		
		if($Update){
			
			print "<div class='alert'>تمت العملية بنجاح</div>";
		
		}else{
		
			print mysql_error();
		}
	
	}
	
?>
