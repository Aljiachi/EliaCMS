<script type="text/javascript">

	function deleteTopic(id){
	
		$("#topic-" + id).fadeOut('slow');
		$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=deletetopic&id=" + id);	
	}
</script>
<?php
	
	print '<div id="loadArea" style="display:none;"></div>';
	
	$this->includeAdminFile("index");
	
	$objectsManager = new objectsManager;
		
	$page = $objectsManager->getPaginatorObject();
	
	$page->SetPerPage(20);
	
	$Topics = $page->CreatePaginator("select * from blog_module_topics order by id desc");
	
	$table = '
	
	<table width="100%" align="center" cellpadding="0" cellspacing="0">';
	
		$table .= '
		<tr>
		<td class="td_title">#</td>
		<td class="td_title">عنوان التدوينة</td>
		<td class="td_title">التعليقات</td>
		<td class="td_title">تعديل</td>
		<td class="td_title">حذف</td>
		</tr>
		';		


	while($rows = $db->rows($Topics)){
	
		$posts = $db->query("select id from blog_module_comments where topic_id =" . $rows['id']);
		
		$posts = $db->total($posts);
		
		$table .= '
		<tr id="topic-'.$rows['id'].'">
		<td class="td_row">'.$rows['id'].'</td>
		<td class="td_row">'.$rows['title'].'</td>
		<td class="td_row">'.$posts.'</td>
		<td class="td_row"><a href="modules.php?action=admin&module='.ModuleID.'&page=updatetopic&id='.$rows['id'].'"><img src="icons/edit.png" /></a></td>
		<td class="td_row"><a onclick="if(window.confirm(\'هل أنت متأكد العملية\') == false){return false;}" href="javascript:deleteTopic('.$rows['id'].')"><img src="icons/remove.png" /></a></td>
		</tr>
		';
	}
	
	$table .= '</table>';
	
	$adinc->getMenu('برنامج المدونة - التحكم بالتدوينات' , $table);
?>