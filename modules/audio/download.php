<?php

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}
	
	// Hide All Templates
	define('templatesShown' , false);

	$id = intval(abs($_REQUEST['id']));
	
	if($id == 0 || !isset($_REQUEST['id']) ){ die('<h1 align="center">'. _lang("ERROR_404") .'</h1>'); }
	
	$query	=	$db->query("select * from audio_module_files where id=$id");
	
	if($db->total($query) == 0){ die('<h1 align="center">'. _lang("ERROR_404") .'</h1>'); }
	
	$update = $db->query("update audio_module_files set downloads=downloads+1 where id=$id");
	
	$download = $db->rows($query);
	
	header("location: " . _url("upload/audio/files/" . $download['file']));	
	
	ob_get_flush();
			
?>