<script type="text/javascript">

	function deleteTopic(id){
	
		$("#topic-" + id).fadeOut(300);
		$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=deletepost&post_id=" + id);	
	}


	function activeComment(id){
	
		$("#topic-" + id + " td").fadeOut('slow');
		
		$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=activepost&active=1&post_id=" + id);	
	}
	
	function unActiveComment(id){
	
		$("#topic-" + id + " td").fadeOut('slow');
		
		$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=activepost&active=0&post_id=" + id);	
	}
	
	
</script>
<?php
	
	print '<center>';
	
	print '<a href="modules.php?action=setting&module='.ModuleID.'" class="fastgo_button">إعدادات الإضافة</a>';
	
	$act = strip_tags(trim($_REQUEST['act']));

	if($act == "w"){

		print '<a href="modules.php?action=admin&module='.ModuleID.'&act=d" class="fastgo_button">المشاركاات المفعلة</a>';

	}else{
		
		print '<a href="modules.php?action=admin&module='.ModuleID.'&act=w" class="fastgo_button">مشاركات بالإنتظار</a>';

	}
	
	print '</center>';
	
	print '<div id="loadArea" style="display:none;"></div>';
	
	$objectsManager = new objectsManager;
		
	$page = $objectsManager->getPaginatorObject();
	
	$page->SetPerPage(20);
	
	if($act == "w"){
		
		$Files = $page->CreatePaginator("select * from module_guestbook where active=0");
		
	}else{

		$Files = $page->CreatePaginator("select * from module_guestbook where active=1");
		
	}
	
	$html = '<table width="100%" align="center" cellpadding="0" cellspacing="0">';
	
		$html .= "<tr>\n
			<td class='td_title'>التعليق</td>\n";

		if($act == "w"){
		
			$html .= "<td class='td_title' width=\"16\">تفعيل</td>\n";	
		
		}else{
		
			$html .= "<td class='td_title' width=\"16\">تعطيل</td>\n";	
		}
		
		$html .= "<td class='td_title' width=\"16\">حذف</td>\n
			</tr>\n";
			
	while($CONTROL_NEWS_ROWS = mysql_fetch_object($Files) ){
	
		$html .= "<tr id='topic-".$CONTROL_NEWS_ROWS->id."'>\n
			<td class='td_row'><div class=\"blog_adminbar\" style=\"margin-bottom:5px;\">كتب : <a href='#'>".$CONTROL_NEWS_ROWS->name."</a></div>
			<div class=\"guestbook_text\">".str_replace("\n" , "<br />" , $CONTROL_NEWS_ROWS->comment)."</div>
			</td>\n";
		
			if($act == "w"){

			$html .= "<td class='td_row'><a href=\"javascript:activeComment($CONTROL_NEWS_ROWS->id)\"><img src=\"icons/activ.png\" /></a></td>\n";
				
			}else{
			
			$html .= "<td class='td_row'><a href=\"javascript:unActiveComment($CONTROL_NEWS_ROWS->id)\"><img src=\"icons/activ.png\" /></a></td>\n";
			}
				
			$html .= "
			<td class='td_row'><a onclick=\"if(window.confirm('هل أنت متأكد العملية') == false){return false;}\" href=\"javascript:deleteTopic($CONTROL_NEWS_ROWS->id)\"><img src=\"icons/remove.png\" /></a></td></td>\n
			</tr>\n
			";
	}
	
	$html .= "\n</table>\n";

	print $page->GetPagination();

	$adinc->getMenu('سجل الزوار' , $html . $page->GetPagination());

?>