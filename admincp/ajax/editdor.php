<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");

	$adinc->_checkSession();

	$defaultSetting = $db->getfastRows("select password from " . PFX . "core");
	
	if(isset($_POST['sender']) and $_POST['sender'] == 1){
	
		$close_do = intval($_POST['close_do']);
		
		$close_text = $_POST['close_text'];

		$update = $db->query("update " . PFX . "core set close_do = '$close_do' , close_text = '$close_text'");

		if(!$update){ print(mysql_error()); }else{
		
			$adinc->greenAlert( $adinc->_lang("UPDATE_DONE"));
		}
	}
	
?>