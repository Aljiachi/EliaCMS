<?php

	class eliaModuleAdmin{
	
		public $extraLink;

		public function __construct(){
			
			$this->extraLink = array(
				' إضافة خبر  ' 		=> 'modules.php?action=admin&module='.ModuleID.'&method=addNews' , 
				' التحكم بالاخبار ' 	=> 'modules.php?action=admin&module='.ModuleID.'&method=Newses' , 
				' التحكم بالتعليقات  ' 	=> 'modules.php?action=admin&module='.ModuleID.'&method=mangeComments');
			
		}
		
		public function main(){
		
			global $db , $adinc;
			
			// display Links
			$adinc->getExtraLinks($this->extraLink);
				
		}
		
		public function addNews(){
		
			global $db , $adinc;
			
			// &_&
			$this->addNewsPost();
			
			$adinc->getJs("editatext");

			$form = '
<table width="100%" align="center" cellpadding="0" cellspacing="0">
<form method="post">
	<tr>
	<td class="td">عنوان الخبر : </td>
	<td class="td"><input type="text" name="news_title" /></td>
	</tr>
	<tr><td class="td" colspan="2" align="center">نص الخبر</td></tr>
	<tr><td class="td" colspan="2" align="center">
	<textarea id="admin_text" name="news_text" cols="40" rows="3"></textarea>
	</td></tr>
	<tr><td colspan="2" class="td"><center>
	<input type="submit" name="addnews"  value="حفظ" />
	</center>
	
	</td></tr>

	</form>

</table>';

			$adinc->getMenu("الأخبار - إضافة خبر" , $form);
			
		}
		
		
		public function addNewsPost(){
				
			global $db , $adinc;
				
			if(isset($_POST['addnews'])){
			
				$title = $_POST['news_title'];
				
				$time  = time();
				
				$text  = $_POST['news_text'];
				
				if(empty($title) or empty($text) ) {
				
					$adinc->redAlert("<div class='alert'>تحقق من الحقول</div>");
				
				}else{
				
				$insert = $db->query("insert into module_news values ('' , '$title' , '$time' , '$text')");
				
				if($insert){
					
					$adinc->greenAlert('تم الحفظ بنجاح');
				
				}
				
			}
				
		 }
		
	  } # end addNewPost
	  
	  public function Newses(){
		
			global $db , $adinc;
						
			$adinc->getExtraLinks($this->extraLink);
			
			$query = $db->query("select * from module_news");
			
			$Html = "<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
			
			$Html.= "<tr>\n
					<td class='td_title'>#</td>\n
					<td class='td_title'>العنوان</td>\n
					<td class='td_title'>".$adinc->_lang("EDIT")."</td>\n
					<td class='td_title'>".$adinc->_lang("DELETE")."</td>\n
					</tr>\n
					";
					
			while($row = $db->rows($query) ){
			
				$Html .= "<tr>\n
					<td class='td_row'>$CONTROL_NEWS_ROWS->news_id</td>\n
					<td class='td_row'><a href='../mod.php?mod=news&page=view&news_id=$row[news_id]' target='_new'>$row[news_title]</a></td>\n
					<td class='td_row'><a href='modules.php?action=admin&module=".ModuleID."&page=editnews&news_id=$row[news_id]'><img src=\"icons/edit.png\" /></a></td>\n
					<td class='td_row'><a onclick=\"if(window.confirm('". $adinc->_lang("ALERT_CONFIRM") ."') == false){return false;}\" href='modules.php?action=admin&module=".ModuleID."&page=deletenews&news_id=$row[news_id]'><img src=\"icons/remove.png\" /></a></td>\n
					</tr>\n
					";
			}
			
			$Html .= "\n</table>\n";
	  
	  		$adinc->getMenu($adinc->_lang("NEWS_ADD_NEWS") , $Html);
	  }
	
	} # Class
	
?>