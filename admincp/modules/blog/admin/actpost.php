<?php

	$id 	 = intval(abs($_REQUEST['id']));

	$active = intval(abs($_REQUEST['active']));
	
	$delete = $db->query("update blog_module_comments set active = $active where id = $id"); 
	
	if($delete){ print mysql_error(); }
?>