<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");

	$adinc->_checkSession();

	if($_REQUEST['action'] == "edit_word"){
		
			$wordid = intval(abs($_POST['wordid']));

			$word = $_POST['word'];
				
			if($db->getfastTotal("select id from " . PFX . "language_words where id='$wordid'") !== 0){

				$update = $db->query("update " . PFX . "language_words set word_value='$word' where id='$wordid'");
				
					if(!$update){ print(mysql_error()); }else{
							
						print 'done_1';	
					
					}
				
			}
		}
			
	if($_REQUEST['action'] == "add_word"){
	
			$word_name = strip_tags(trim($_POST['word_name']));
	
			$word_value = addslashes($_POST['word_value']);
			
			$language_code = strip_tags(trim($_POST['language_code']));
			
			$module_id = strip_tags(trim($_POST['module_id']));
			
			$word_type = intval(abs($_POST['word_type']));
			
			if(empty($language_code) || empty($word_name) || empty($word_value)){return false;}
			
			if($db->getfastTotal("select id from " . PFX . "language_words where word_name='$word_name'") == 0){

				$update = $db->query("insert into " . PFX . "language_words values('' , '$word_name' , '$word_value' , '$language_code' , '$module_id' , '$word_type')");
				
					if(!$update){ print(mysql_error()); }else{
							
						print mysql_insert_id();
					
					}
				
			}else{
			
				print 'error_1';	
			}
		}
			
	if($_REQUEST['action'] == "delete_word"){
		
			$wordid = intval(abs($_REQUEST['wordid']));
				
			if($db->getfastTotal("select id from " . PFX . "language_words where id='$wordid'") !== 0){

				$update = $db->query("delete from " . PFX . "language_words where id='$wordid'");
				
					if(!$update){ print(mysql_error()); }else{
							
						print 'done_1';	
					
					}
				
			}
		}
	
?>