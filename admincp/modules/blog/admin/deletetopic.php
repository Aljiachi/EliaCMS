<?php

	$id 	 = intval(abs($_REQUEST['id']));

	$delete = $db->query("delete from blog_module_topics where id = $id"); 
	
	if($delete){ print mysql_error(); }
?>