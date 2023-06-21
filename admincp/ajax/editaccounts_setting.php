<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");

	$adinc->_checkSession();

	if(isset($_POST['sender']) and $_POST['sender'] == 1){
	
		$accounts_avatar_height 	= strip_tags($_POST['accounts_avatar_height']);
	
		$accounts_avatar_width 		= strip_tags($_POST['accounts_avatar_width']);
		
		$accounts_allow_signup 		= intval(abs($_POST['accounts_allow_signup']));

		$accounts_activation 		= intval(abs($_POST['accounts_activation']));

		$accounts_default_group 	= intval(abs($_POST['accounts_default_group']));

		$activation_default_group 	= intval(abs($_POST['activation_default_group']));
			
		$update = $db->query("update " . PFX . "core set 
									accounts_avatar_height='$accounts_avatar_height' ,
									accounts_avatar_width='$accounts_avatar_width' , 
									accounts_allow_signup='$accounts_allow_signup' , 
									accounts_allow_activation='$accounts_activation' , 
									accounts_default_group='$accounts_default_group' ,
									activation_default_group='$activation_default_group'");
		
		if(!$update){ print(mysql_error()); }else{
					
			$adinc->greenAlert( $adinc->_lang("UPDATE_DONE"));
						
		}
	
	}

?>