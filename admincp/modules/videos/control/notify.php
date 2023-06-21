<?php


	$Notify = $pdo->query("select * from videos_module_comments where active = 0");
	$TotalNotify = count($Notify->fetchAll());
	
	
	if($TotalNotify !== 0){

		print '<div><a href="modules.php?action=admin&module='.$this->NotifyModuleId.'&page=comments">يوجد ' . $TotalNotify . ' تعليق بإنتظار التفعيل</a></div>';

	}
?>