<?php

	$Notify = $pdo->query("select id from audio_module_comments where active = 0");
	$TotalNotify = count($Notify->fetchAll());
	
	if($TotalNotify !== 0){
			
		print '<div><a href="modules.php?action=admin&module='.$this->NotifyModuleId.'&page=comments&&active=0">يوجد ' . $TotalNotify . ' تعليق بإنتظار التفعيل</a></div>';
		
	}
?>