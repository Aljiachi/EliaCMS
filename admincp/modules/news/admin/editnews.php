<?php

	$news_id = intval(abs($_GET['news_id']));

	if(isset($_POST['editnews'])){
	
		$title = $_POST['news_title'];
		
		$time  = time();
		
		$text  = $_POST['news_text'];
		
		if(empty($title) or empty($text) ) {
		
			print("<div class='alert'>تحقق من الحقول</div>");
		
		}else{
		
		$Update = mysql_query("update module_news set news_title='$title' , news_text='$text' where news_id = $news_id");
		
		if($Update){
			
			print "<div class='alert'>تمت العملية بنجاح</div>";
		
			$this->reload($_SERVER["HTTP_REFERER"]);

		}else{
		
			print mysql_error();
		}
	
	}
	
	}
	
	$Edit_News_Query = mysql_query("select news_title , news_text from module_news where news_id = $news_id");
	
	$Edit_News_Rows  = mysql_fetch_assoc($Edit_News_Query);
	
?>

<script src="js/mceditor/McEditor.js"></script>

<script>
	
	Editor.Path			= "js/mceditor";
	Editor.TextArea 	= "news_text";
	Editor.Mode     	= "html"; 		
	Editor.ToolBar      = "full"; 		
	Editor.Style    	= "default";    
	Editor.IconsPath    = "js/mceditor/icons";
	Editor.direction    = "rtl";		
	Editor.Switch		= true;	
	
</script>
<table width="80%" align="center" class="table">
<form method="post">
	<tr>
	<td class="td">عنوان الخبر : </td>
	<td class="td"><input type="text" value="<? print $Edit_News_Rows['news_title']; ?>" name="news_title" /></td>
	</tr>
	<tr><td class="td" colspan="2" align="center">نص الخبر</td></tr>
	<tr><td class="td" colspan="2" align="center">
	<textarea id="news_text" name="news_text" cols="40" rows="3"><? print $Edit_News_Rows['news_text']; ?></textarea>
	<script> 
	/* Get Nc Editor */ 
	Editor.Start();
	</script>
	</td></tr>
	<tr><td colspan="2" class="td"><center>
	
<input type="submit" name="editnews" onclick="return Editor.setCode()" value="تعديل" />
<input type="button" onClick="Editor.ReEditor();" value="تفريغ">
<input type="button" onClick="Editor.view();" value="معاينة">
	</center></td></tr>

	</form>

</table>
