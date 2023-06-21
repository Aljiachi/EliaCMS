<?php

	$EDIT_SETTING = mysql_query("select * from module_news_setting");
	$ROWS_EDIT_SETTING = mysql_fetch_assoc($EDIT_SETTING);
	
	if(isset($_POST['submit'])){
	
		$comments = $_POST['comments_do'];
		$viewNews = $_POST['news_do'];
	
		$Edit = mysql_query("update module_news_setting set viewpost_active = '$viewNews'");
		
		if($Edit){ print "OK!!"; }
		
	}
	
?>

<form method="post">

	<p>Comments : 
	<select name="comments_do">
		<option value="1">yes</option>
		<option value="0">no</option>
	</select>
	</p>
	<p>View News : 
	<select name="news_do">
		<option value="1">yes</option>
		<option value="0">no</option>
	</select>
	</p>
	<input type="submit" name="submit" value="Edit" />
</form>