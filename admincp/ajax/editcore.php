<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");

	$adinc->_checkSession();

	if(isset($_POST['sender']) and $_POST['sender'] == 1){
	
		$title = strip_tags($_POST['title']);

		$siteurl = strip_tags($_POST['siteurl']);

		$meta = strip_tags($_POST['meta']);

		$keywords = strip_tags($_POST['keywords']);
		
		$report_byemail = intval(abs($_POST['report_byemail']));

		$site_language = intval(abs($_POST['site_language']));

		$admin_language = intval(abs($_POST['admin_language']));
			
		$update = $db->query("update " . PFX . "core set 
									title='$title' ,
									meta='$meta' , 
									keywords='$keywords' , 
									siteurl='$siteurl' , 
									site_language='$site_language' , 
									admin_language='$admin_language' ,
									report_bymail='$report_byemail' ");
		
					if(!$update){ print(mysql_error()); }else{
					
						$adinc->greenAlert( $adinc->_lang("UPDATE_DONE"));
						
					}
	}
?>