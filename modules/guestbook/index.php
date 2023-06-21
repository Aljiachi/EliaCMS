<?php 

	if(script_run !== true){
		
			die('<h1 align="center">404 . الصفحة المطلوبة غير موجودة</h1>');
		}

	$start = intval($_GET['limit']);
	
	if(!$start){ 
		
		$start = 1;
	}
	
	$sublimit = $start - 1;
	$nxt = $start + 1;
	$prv = $start - 1;
	$limit = 10;
	$TotalRows = mysql_num_rows(mysql_query("select * from module_guestbook "));
	$allpages  = $TotalRows / $limit;
	$Query = mysql_query("select * from module_guestbook order by id limit $sublimit , $limit");
	$Num   = mysql_num_rows($Query);

	print '<div style="float:right; width:250px;">';
	$menu->getRight();
	print '</div>';
	
	print '<div style="margin-right:252px;">';

	print '<a style="background:#1b5b7c; color:#fff; padding:7px; display:inline-block; margin:3px;" href="mod.php?mod=guestbook&page=addpost" target="_new">إضافة مشاركة</a>';
	
	while($rows = mysql_fetch_assoc($Query)) { $num++; ?>
	
    <div class="table" style="margin:3px;">
	<div style="background:#abdaf2; color:#222; padding:5px; margin-left:1px; margin-right:1px;" align="right">كتب : <?php print $rows['name'] ?></div>
	<div style="background:#0fa3f0; padding:7px; margin-left:1px; margin-right:1px;">
	<div style="font-size:16px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; color:#fff; line-height:140%; text-align:justify;"><?php print strip_tags($rows['comment']) ?></div>
	<div class="info">
		<div style="color:#c6e2f1;" align="left"><?php print date("d/m/Y h:s" , $rows['time']) ?></div>
	</div>		
		</div>
			</div>			
	<? } ?>
	
<?php if($Num < $TotalRows){ ?>

	<div class="page">
	<?php if($start < $allpages){ ?>
		<a class="nxt" href="mod.php?mod=guestbook&limit=<?php print $nxt; ?>">التالي</a>
	<? } ?>
	<?php if($start > 1){ ?>
		<a class="prv" href="mod.php?mod=guestbook&limit=<?php print $prv; ?>">السابق</a>
	<? } ?>
	</div>
<? } ?>
</div>