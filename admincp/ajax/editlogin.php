<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");

	$adinc->_checkSession();

	$defaultSetting = $db->getfastRows("select password from " . PFX . "core");
	
	if(isset($_POST['sender']) and $_POST['sender'] == 1){
	
		$username = strip_tags($_POST['username']);

		$admin_email = strip_tags($_POST['admin_email']);
		
		if(empty($_POST['password'])){
		
			$update = $db->query("update " . PFX . "core set username='$username' , admin_email='$admin_email'");
				
		}else{
			
			$password = md5($_POST['password']);

			$update = $db->query("update " . PFX . "core set username='$username' , password='$password' ,  admin_email='$admin_email'");

		}
		
		
		if(!$update){ print(mysql_error()); }else{
		
			$adinc->greenAlert( $adinc->_lang("UPDATE_DONE"));
		}
	}
?>