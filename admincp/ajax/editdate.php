<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");

	$adinc->_checkSession();

	if(isset($_POST['sender']) and $_POST['sender'] == 1){
	
		$date = strip_tags($_POST['type_date']);

		$time = strip_tags($_POST['type_time']);
			
		$update = $db->query("update " . PFX . "core set type_date='$date' , type_time='$time'");
		
		if(!$update){ print(mysql_error()); }else{
		
			$adinc->greenAlert( $adinc->_lang("UPDATE_DONE"));
		}
	}
?>