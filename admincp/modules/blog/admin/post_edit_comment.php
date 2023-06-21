<?php

	$id 	 = intval(abs($_REQUEST['id']));

	$name = strip_tags(trim($_POST['name']));
	
	$post = $_POST['comment'];
	
	$update = $db->query("update blog_module_comments set name='$name' , comment='$post' where id = $id");
?>