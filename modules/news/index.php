<?php 

	if(script_run !== true){
		
			die('<h1 align="center">404 . الصفحة المطلوبة غير موجودة</h1>');
		}
?>
<link rel='stylesheet' href='modules/news/style.css' type='text/css' />
<?php

	$start = intval($_GET['limit']);
	
	if(!$start){ 
		
		$start = 1;
	}
	
	$sublimit = $start - 1;
	$nxt = $start + 1;
	$prv = $start - 1;
	$limit = 10;
	$TotalRows = mysql_num_rows(mysql_query("select * from module_news "));
	$allpages  = $TotalRows / $limit;
	$Query = mysql_query("select * from module_news order by news_id limit $sublimit , $limit");
	$Num   = mysql_num_rows($Query);

	_print('<div style="float:right; width:250px;">');
	$menu->getRight();
	print '</div>';
	
	print '<div style="margin-right:252px;">';
?>
<?php while($rows = mysql_fetch_assoc($Query)) { ?>
	<div class="table">
	<div class="td">
	<div class="title"><a href="mod.php?mod=news&page=view&news_id=<?php print $rows['news_id'] ?>"><?php print $rows['news_title'] ?></a></div>
	<center style="font-size:12px;"><?php print substr(strip_tags($rows['news_text']) , 0 , 400); ?></center>
	<div class="info">
		<div class='inline'><?php print date("D : m : Y" , $rows['news_time']) ?></div>
	</div>		
		</div>
			</div>			
	<? } ?>
	
<?php if($Num < $TotalRows){ ?>

	<div class="page">
	<?php if($start < $allpages){ ?>
		<a class="nxt" href="mod.php?mod=news&limit=<?php print $nxt; ?>">التالي</a>
	<? } ?>
	<?php if($start > 1){ ?>
		<a class="prv" href="mod.php?mod=news&limit=<?php print $prv; ?>">السابق</a>
	<? } ?>
	</div>
<? } ?>
</div>