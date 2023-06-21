<?php

	$id 	 = intval(abs($_REQUEST['id']));

	$delete = $db->query("delete from blog_module_comments where id = $id"); 
	
	if($delete){ print mysql_error(); }
?>