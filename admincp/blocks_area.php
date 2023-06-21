<?php

	session_start();
	
	include("../includes/config.php");

	include("../includes/class.db.php");

	include("adinc/class.adinc.php");
	
	include("adinc/functions.php");

	$adinc->getStyle();
		
	$action = _safeString($_REQUEST['action']);
	
	$area   = _safeInt($_REQUEST['area']);
		
	$adinc->getRight('blocks');

	$adinc->getNavbar(array(
		$adinc->_lang("NAVBAR_MENUS_MANGE") => 'blocks.php?action=control' , 
		$adinc->_lang("NAVBAR_MENUS_ADD") => 'blocks.php?action=addmenu'   ,
		$adinc->_lang("NAVBAR_MENUS_DISPLAY_MOD") => 'blocks_area.php'));
	
	if($action == "replace"){
		
			if(isset($area) and !empty($area) and is_numeric($area) and $area <= 3){
				
				$replace = $db->query("update core set block_view='$area'");
			
			}
						
			if($replace){
				
					$adinc->greenAlert($adinc->_lang("SAVE_DONE"));
					
					$adinc->location('blocks_area.php');
				}
		}
	
			$getStatus = $db->getfastRows("select block_view from core");

	$form = '        <center>
					 <a href="?action=replace&area=2"><img class="area_block" src="images/block-2.jpg"';  
					 	if($getStatus['block_view'] == 2){ $form .= ' style="background:#88bd5d; padding:5px;"';}
					 $form .= '/></a>
			 		 <a href="?action=replace&area=1"><img class="area_block" src="images/block-1.jpg"';  
					 	if($getStatus['block_view'] == 1){ $form .= ' style="background:#88bd5d; padding:5px;"';}
					 $form .= ' /></a>
					 <a href="?action=replace&area=3"><img class="area_block" src="images/block-3.jpg"';  
					 	if($getStatus['block_view'] == 3){ $form .= ' style="background:#88bd5d; padding:5px;"';}
					 $form .= ' /></a>
					 </center>
					 
					 ';

	$adinc->getMenu($adinc->_lang("CONTROL_MENUS_DISPLAY_MOD_AREA") , $form);
	
	$adinc->closePage();
?>