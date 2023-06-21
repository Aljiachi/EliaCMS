<?php

	$QUERY_NEWS_MOD_SETTING = mysql_query("select * from module_news_setting"); 
	
	$NEWS_MOD_SETTING 	    = mysql_fetch_assoc($QUERY_NEWS_MOD_SETTING); 
	
	if(!$QUERY_NEWS_MOD_SETTING){
	
		die(mysql_error());
	}
	
?>