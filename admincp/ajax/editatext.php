<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");

	$adinc->_checkSession();
				
	if(isset($_POST['sender']) and $_POST['sender'] == 1){
	
		$text = $_POST['text'];
			
		$update = $db->query("update " . PFX . "core set admin_text = '$text'");
		
		if(!$update){ print(mysql_error()); }else{
		
			print '<span style="padding:5px;"><img src="images/ok.png" /></span>';	
		}
	}
?>