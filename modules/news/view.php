<?php 

	if(script_run !== true){
		
			die('<h1 align="center">404 . الصفحة المطلوبة غير موجودة</h1>');
		}
?>
<link rel='stylesheet' href='modules/news/style.css' type='text/css' />
<?php
	
	if(file_exists(modulesPath . "/" . $this->mod . "/setting.php") ){
			
				include_once(modulesPath . "/" . $this->mod . "/setting.php");
			
			}else{
			
				die($this->not_exists);
			}
				
	$news_id = intval(abs($_GET['news_id']));
	
	$GetNews = mysql_query("select * from module_news where news_id = $news_id");

	$PrintNews = mysql_fetch_assoc($GetNews);
	

	print '<div style="float:right; width:250px;">';
	$menu->getRight();
	print '</div>';
	
	print '<div style="margin-right:252px;">';
	
	print "<div class='table' style='margin:5px;'>\n";

	print "<div class='title'>".$PrintNews['news_title']."</div>\n";
	
	print "<div class='info'>\n<div class='inline'>".date("D : m : Y" ,$PrintNews['news_time'])."</div>\n";

	print "</div>\n";
	
	print "<div class='content'>" .$PrintNews['news_text'] . "\n</div>\n</div>\n";
	
	print '</div>';
	
?>