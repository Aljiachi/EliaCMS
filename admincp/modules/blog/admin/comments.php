<script type="text/javascript">

	function deleteComment(id){
	
		$("#topic-" + id).fadeOut('slow');
		$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=deletepost&id=" + id);	
	}
	

	function activeComment(id){
	
		$("#topic-" + id + " td").fadeOut('slow');
		
		$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=actpost&active=1&id=" + id);	
	}
	
	function unActiveComment(id){
	
		$("#topic-" + id + " td").fadeOut('slow');
		
		$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=actpost&active=0&id=" + id);	
	}
	
		function deleteComment(id){
	
		$("#topic-" + id + " td").fadeOut('slow');
		
		$("#loadArea").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=deletecomment&id=" + id);	
	}
	
	function updateComment(id){
	
		$("#TB_overlay").fadeIn('slow');
		$("#TB_window").show().html('Loading...');
		$("#TB_window").load("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=edit_comment&id=" + id);
	}
	
	function close_box(){
		
		$("#TB_overlay").fadeOut('slow');
		$("#TB_window").hide().html();

	}
	
	function postEdit(id){
	
		var name = $("#post_name").val();
		var post = $("#post").val();
		
		$.post("fast-modules.php?action=admin&module=<?php print ModuleID ?>&page=post_edit_comment&id=" + id , {
			
				"sender" : 1 ,
				"name" : name , 
				"comment" : post 
			} , function(){
			
				$("#writer-" + id).html(name);
							
				var new_post = post.replace(/\n/gi , "<br />");
				
				$("#comment-" + id).html(new_post);
				
				close_box();
				
			});	
	}
	
</script>
<?php
	
	print '<div id="loadArea" style="display:none;"></div>';
	
	print '
<div id="TB_overlay" class="TB_overlayBG" >

    
</div>
	<div id="TB_window">

		
	</div>
';

	$this->includeAdminFile("index");
	
	$objectsManager = new objectsManager;
		
	$page = $objectsManager->getPaginatorObject();	
	
	$page->SetPerPage(15);
	
	$active = intval(abs($_REQUEST['active']));
	
	$page->SetPageUrl("modules.php?action=admin&module=".ModuleID."&page=comments&active=" . $active);
	
	$Topics = $page->CreatePaginator("select * from blog_module_comments where active = $active order by id desc");
	
	$total_actived = $db->total($db->query("select * from blog_module_comments where active = 1"));
	$total_unactived = $db->total($db->query("select * from blog_module_comments where active = 0"));

	print '<a href="modules.php?action=admin&module='.ModuleID.'&page=comments&active=1">مفعل ('.$total_actived.')</a> | ';	
	print '<a href="modules.php?action=admin&module='.ModuleID.'&page=comments&active=0">غير مفعل ('.$total_unactived.')</a>';	
	
	$table = '
	<table width="100%" align="center" cellpadding="0" cellspacing="0">';
	
		$table .= '
		<tr>
		<td class="td_title">#</td>
		<td class="td_title">الكاتب</td>
		<td class="td_title" width="70%">التعليق</td>
		</tr>
		';		

	while($rows = $db->rows($Topics)){
	
		$posts = $db->query("select id from blog_module_topics where cat_id =" . $rows['id']);
		
		$posts = $db->total($posts);
		
		$table .= '
		<tr id="topic-'.$rows['id'].'">
		<td class="td_row">'.$rows['id'].'</td>
		<td class="td_row">
			<div>
			<div><img src="icons/admin.png" /> <span id="writer-'.$rows['id'].'">'.$rows['name'].'</span></div>
			<img src="icons/ip.png" /> '.$rows['ip'].'
			</div>
			</td>
		<td class="td_row">
			<div id="comment-'.$rows['id'].'">'.str_replace("\n" , "<br />" , $rows['comment']).'</div>
			<div class="blog_adminbar">
			';
			if($rows['active'] == 0){
				
				$table .='<a href="javascript:activeComment('.$rows['id'].')">تفعيل</a>';
		
			}else{

				$table .='<a href="javascript:unActiveComment('.$rows['id'].')">تعطيل</a>';
				
			}
			
			$table .=' 
			| <a href="javascript:updateComment('.$rows['id'].')">تحرير</a>
			| <a onclick="if(window.confirm(\'هل أنت متأكد العملية\') == false){return false;}" href="javascript:deleteComment('.$rows['id'].')">حذف</a>
			
			</div></td>
		</tr>
		';
	}
	
	$table .= '</table>';

	print $page->GetPagination();
	
	$adinc->getMenu('برنامج المدونة - التحكم بالتصنيفات' , $table . $page->GetPagination() );
	
?>