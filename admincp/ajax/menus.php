<?php

	session_start();
	
	include("../../includes/config.php");

	include("../../includes/class.db.php");

	include("../adinc/class.adinc.php");
	
	$adinc->_checkSession();
	
		if($_REQUEST['action'] == "delete_menu"){
				if(!$update){ print(mysql_error()); }else{
						
					$adinc->greenAlert( $adinc->_lang("DELETE_DONE"));
				
				}
					
		}

			
	if($_REQUEST['action'] == "resort"){
		
			if(isset($_POST['resortPost']) and $_POST['sender'] == 1){
				
				$menuid = intval(abs($_POST['menuid']));
					
				$sort = intval(abs($_POST['sort']));
				
				$update = $db->query("update `" . PFX . "blocks` set `sort`='$sort' where id=$menuid");
				
					if(!$update){ print(mysql_error()); }else{
							
						$adinc->greenAlert( $adinc->_lang("DELETE_DONE"));
			
					}
						

			}
		}
		
	if($_REQUEST['action'] == "move"){
		
			if(isset($_POST['resortPost']) and $_POST['sender'] == 1){
				
				$menuid = intval(abs($_POST['menuid']));
					
				$align = intval(abs($_POST['align']));
				
				$update = $db->query("update `" . PFX . "blocks` set `align`='$align' where id=$menuid");
				
					if(!$update){ print(mysql_error()); }else{
							
						$adinc->greenAlert( $adinc->_lang("DELETE_DONE"));
			
					}
						

			}
		}
		
	if($_REQUEST['action'] == "active"){
		
			if(isset($_POST['resortPost']) and $_POST['sender'] == 1){
				
				$menuid = intval(abs($_POST['menuid']));
					
				$event = strip_tags(trim($_POST['event']));
				
				if($event == "enable"){
				
					$update = $db->query("update `" . PFX . "blocks` set `active`='1' where id=$menuid");
	
				}else if($event == "disable"){
				
					$update = $db->query("update `" . PFX . "blocks` set `active`='0' where id=$menuid");
	
				}
								
					if(!$update){ print(mysql_error()); }else{
							
						$adinc->greenAlert( $adinc->_lang("DELETE_DONE"));
			
					}
						

			}
		}
	
?>