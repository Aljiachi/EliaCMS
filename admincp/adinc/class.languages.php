<?php

	class languagesManager{
		
		public function addLang(){
		
			global $db , $adinc;

			if(isset($_POST['addlang'])){
			
				$lang_name = strip_tags(trim($_POST['lang_name']));

				$lang_code = strip_tags(trim($_POST['lang_code']));

				if(!empty($lang_name) && !empty($lang_code)){
				
					$insert = $db->query("insert into " . PFX . "languages values ('' , '$lang_name' , '$lang_code')");
					
					if($insert){
						
						// Done Message
						$adinc->greenAlert('تم الحفظ بنجاح');
							
						// Location
						$adinc->location("languages.php");
						
					}else{
						
						// Done Message
						$adinc->redAlert('حدث خطأ');
							
						// Location
						$adinc->location("languages.php?action=addlang");
					}
				}
			}
			
			$form = '<script type="text/javascript">
			
	
	function access(){
		
			var name = $("#lang_name").val();
			
				name.replace(/ /gi , "");
				
	
			var code = $("#lang_code").val();
			
				code.replace(/ /gi , "");
				
			if(name == ""){
				
					$("#alert_name").html(\'<span class="red_alert">تحقق من الحقل</span>\').fadeIn("slow");
		
					return false;
					
				}	
				
			if(code == ""){
				
					$("#alert_code").html(\'<span class="red_alert">تحقق من الحقل</span>\').fadeIn("slow");
		
					return false;
					
				}	
		}
			</script>
            <form method="post">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">إسم اللغة</td>
    <td><input type="text" id="lang_name" name="lang_name" /> <span id="alert_name" style="display:none;"></span></td>
  </tr>
  <tr>
    <td>مفتاح اللغة (AR , EN , KOR...)</td>
    <td><input type="text" id="lang_code" name="lang_code" /> <span id="alert_code" style="display:none;"></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="addlang" onclick="return access();" value="حفظ" /></td>
  </tr>
</table>

			</form>';
			
			$adinc->getMenu("إضافة لغة" , $form);			
		}
		
		public function languagesControl(){
		
			global $db , $config , $adinc;
															
			$languagesQuery = $db->query("select * from " . PFX . "languages order by id asc");
								
			$t.= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$t .= "<tr>\n
				<td class='td_title'>اللغة</td>\n
				<td class='td_title' align='center' width=\"120\">التحكم بعبارات الموقع</td>\n
				<td class='td_title' align='center' width=\"160\">التحكم بعبارات لوحة التحكم</td>\n
				<td class='td_title' align='center' width=\"120\">تصدير اللغة</td>\n
				<td class='td_title' align='center' width=\"120\">حذف اللغة</td>\n
			</tr>\n
			";

		while($rows = $db->rows($languagesQuery) ) {
			
		 $t .= "<tr id=\"lang-$rows[id]\">\n
				<td class='td_row'><div>$rows[language_name] ($rows[language_code])</div></td>\n"; 
				$t .= "<td class='td_row' align='center'><a href=\"languages.php?action=words&type=0&langid=$rows[id]\"><img src=\"icons/manage.png\" border=\"0\" /></a></td>\n";
				$t .= "<td class='td_row' align='center'><a href=\"languages.php?action=words&type=1&langid=$rows[id]\"><img src=\"icons/manage.png\" border=\"0\" /></a></td>\n";
				$t .= "<td class='td_row' align='center'><a href=\"languages.php?action=export&langid=$rows[id]\"><img src=\"icons/export.png\" border=\"0\" /></a></td>\n";
				$t .= "<td class='td_row' align='center'><a href=\"languages.php?action=delete&langid=$rows[id]\"><img src=\"icons/remove.png\" border=\"0\" /></a></td>\n";				
				$t .= "</tr>\n";	
			}
						
			$t .= "</table>\n";
			
			
			$adinc->getMenu('التحكم باللغات' , $t);

		}
		
	public function wordsControl(){
	
		global $db , $config , $adinc;
			
			$langid = intval(abs($_REQUEST['langid']));

			$module = strip_tags(trim($_REQUEST['module']));
			
			$type   = intval(abs($_REQUEST['type']));
			
			// Av Languages	
			$Languages = $db->query("select * from " . PFX . "languages");

			$avLanguages = array();			

			while($rowsLanguages = $db->rows($Languages)){

				$avLanguages[$rowsLanguages['language_name']] = 'languages.php?action=words&langid='.$rowsLanguages['id'].'&module='.$module;			
			
			}
			
			$adinc->getExtraLinks($avLanguages);
			
			if(!empty($module) and $langid !== 0){

				$moduleInfo = $db->query("select * from " . PFX . "modules where  module_path='$module' limit 1");
				$languageInfo = $db->query("select * from " . PFX . "languages where id='$langid' limit 1");

				if($db->total($moduleInfo) == 0 || $db->total($languageInfo) == 0){
				
					return false;	
				}	

				$rowsInfoMod = $db->rows($moduleInfo);
				$rowsInfoLang= $db->rows($languageInfo);
				
				$dispalyName = $rowsInfoMod['module_name'] . ' - ' . $rowsInfoLang['language_name'];					
			
			}else if(!empty($module)){
			
				$moduleInfo = $db->query("select * from " . PFX . "modules where  module_path='$module' limit 1");
				
				if($db->total($moduleInfo) == 0){
				
					
					return false;	
				}	

				$rowsInfoMod = $db->rows($moduleInfo);

				$dispalyName = $rowsInfoMod['module_name'];

			}else if(isset($_REQUEST['langid']) and $langid !== 0){
				
				$languageInfo = $db->query("select * from " . PFX . "languages where id='$langid' limit 1");
				
				if($db->total($languageInfo) == 0){
				
					return false;	
				}
			
				$rowsInfoLang = $db->rows($languageInfo);
	
				$dispalyName = $rowsInfoLang['language_name'];
				
			}
					
			$objManager = new objectsManager();
			
			$Paginator  = $objManager->getPaginatorObject();
			
			$Paginator->SetPerPage(50);	
						
			if(!empty($module) and $langid !== 0){

				$Words = $Paginator->CreatePaginator("select * from " . PFX . "language_words where word_type='$type' and module_id='$module' and language_code='$rowsInfoLang[language_code]'  order by id desc");
			
				$Paginator->setPageUrl("languages.php?action=words&langid=$langid&module=$module&type=$type");
	
			}else if(isset($_REQUEST['langid']) and $langid !== 0){

				$Words = $Paginator->CreatePaginator("select * from " . PFX . "language_words where word_type='$type' and language_code='$rowsInfoLang[language_code]' order by id desc");

				$Paginator->setPageUrl("languages.php?action=words&langid=$langid&type=$type");
				
			}else if(isset($_REQUEST['module']) and !empty($module)){			

				$Words = $Paginator->CreatePaginator("select * from " . PFX . "language_words where word_type='$type' and module_id='$module' order by id desc");
	
				$Paginator->setPageUrl("languages.php?action=words&module=$module&type=$type");

			}
		
					
			$form = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%">رمز الكلمة</td>
    <td><input type="text" id="word_name"/> <span id="alert_name" style="display:none;"></span></td>
  </tr>
  <tr>
    <td>الكلمة</td>
    <td><input type="text" id="word_value" name="lang_code" /> <span id="alert_value" style="display:none;"></span></td>
  </tr>
  <tr>
    <td><input type="hidden" id="module_id" value="'.$module.'" /> <input type="hidden" id="word_type" value="'.$type.'" /></td>
    <td><input type="submit" name="addlang" onclick="return addWord(\''.$rowsInfoLang['language_code'].'\');" value="حفظ" /></td>
  </tr>
</table>';

			print '<a name="addword"></a>';
			
			$adinc->getMenu("إضافة عبارة" , $form);	
				
			$t = "<form method=\"post\" action=\"languages.php?action=multiaction_words\">\n";
			
			$t.= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
			
			$t .= "<tr>\n
			<td class='td_title' width=\"200\">رمز الكلمة</td>\n
			<td class='td_title' align=\"right\">الكلمة</td>\n
			<td class='td_title' width=\"90\" align=\"center\">delete</td>\n
			<td class='td_title' width=\"70\" align=\"center\"><input type=\"checkbox\" name=\"check_all\" onclick=\"checkAll(this.form)\" /></td>
			</tr>\n
			<tbody id=\"words_table\">";

		while($rows = $db->rows($Words) ) {
			
		 $t .= "<tr id=\"word-$rows[id]\">\n
				<td class='td_row'><div>$rows[word_name]</div></td>\n"; 
				$t .= "<td class='td_row' align=\"right\">
				<div id=\"word_box_$rows[id]\" onclick=\"showEditBox($rows[id])\">" . htmlspecialchars($rows['word_value']) . "</div>
				<div id=\"edit_box_$rows[id]\" style=\"display:none;\"><input type=\"text\" id=\"word_input_$rows[id]\" value=\"".htmlspecialchars($rows['word_value'])."\" /><input type=\"button\" onclick=\"updateWord($rows[id])\" value=\"حفظ\" /></div>
				</td>\n";			
				$t .= "<td class='td_row' align=\"center\"><a href='#delete' onclick='deleteWord($rows[id])'><img src='icons/remove.png' border='0' /></a></td>\n"; 
				$t .= '<td class=\'td_row\' align="center"><input type="checkbox" name="check[]" value="'.$rows['id'].'" /></td>';
				
				$t .= "</tr>\n";	
			}
						
			$t .= "</tbody>\n</table>\n";
			
			$t .= '<div class="tools_bar"><div style="float:left;">'.$Paginator->GetPagination().'</div><input type="submit" name="delete_selcted" value="رفع الحظر" /></div>';
						
			$t .= "</form>\n";
						
			$adinc->getMenu('التحكم بالعبارات ('.$dispalyName.')' , $t);
		
	}
		
		public function import(){
		
			global $db , $adinc;
						
			if(isset($_POST['import'])){
					
				$uploader = new Uploader;
				$uploader->FAKE_UPLOAD = true;
				$uploader->FILE_VER = "xml_file";
				$uploader->FILES_SIZE = 129628;
				$uploader->TYPES = array('text/xml');
				$uploader->FILE_EXT = array('.xml');
				
				$uploader->getFileInformation();
						
				if(!$uploader->checkFileContent()){print 'error file content'; exit;}
				if(!$uploader->checkFileSize() ){ print 'error file size'; exit;}
				if(!$uploader->checkFileType() ){ print 'error file type'; exit;}

				$eliaFile = simplexml_load_file($uploader->file_tmp);
				
				$eliaLangCode = $eliaFile->language['lang_code'];
				$eliaModule   = $eliaFile->language['module'];
				$eliaLangType =  $eliaFile->language['admin'];
				
				if(isset($eliaLangType) && !empty($eliaLangType) && $eliaLangType == "admin"){ $type = 1; }
				
				foreach($eliaFile->language[0] as $key => $var){
									
					if(isset($var['module']) && !empty($var['module'])){ $eliaModule = $var['module']; }
					if(isset($var['admin']) && !empty($var['admin'])){ $type = 1; }
										
					if($db->getFastTotal("select id from " . PFX . "language_words where language_code='$eliaLangCode' and word_name='$var[code]' and module_id='$eliaModule' and word_type='$type'") == 0 and !empty($var) and !empty($var['code'])){
						
						$insertWord  = $db->query("insert into " . PFX . "language_words values('' , '".$var['code']."' , '$var' , '$eliaLangCode' , '$eliaModule' , '$type')");
						
						$eliaModule = $eliaFile->language['module'];

					}
				}
				
				if($insertWord){
						
						// Done Message
						$adinc->greenAlert('تم الحفظ بنجاح');
							
						// Location
						$adinc->location("languages.php?action=words");
						
					}else{
						
						// Done Message
						$adinc->redAlert('حدث خطأ');
							
						// Location
						//$adinc->location("languages.php?action=import");
					}
					
			}
		
			$form = '<script type="text/javascript">	
						function access(){
							var xml_file = $("#xml_file").val();
								xml_file.replace(/ /gi , "");
							if(xml_file == ""){
									$("#alert_xml_file").html(\'<span class="red_alert">تحقق من الحقل</span>\').fadeIn("slow");
									return false;			
								}	
						}
					</script>
            <form method="post" enctype="multipart/form-data">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>حدد ملف (XML)</td>
				<td><input type="file" id="xml_file" name="xml_file" /> <span id="alert_xml_file" style="display:none;"></span></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="import" onclick="return access();" value="حفظ" /></td>
			  </tr>
</table>

			</form>';
			
			$adinc->getMenu("إستيراد عبارات" , $form);							
		}
		
		public function multiActionLogs(){

			global $db , $adinc , $config;
					
			$check = $_POST['check'];
		
			if(count($check) == 0){

				$adinc->redAlert('لم تحدد أي إشعار');	
			
				return false;
			}
			
			$impad = implode(",",$check);	
					
			if(isset($_POST['delete_selcted'])){
									
				$MultiAction = $db->query("DELETE FROM " . PFX . "logs WHERE `id` in (".$impad.")");
	
				if($MultiAction){
					
					$adinc->greenAlert('تم الحفظ بنجاح');
			
					$adinc->location('security.php?action=logs');
				}
				
			}
			
		}
		
		public function export(){
		
			global $db , $adinc , $config;
			
			$langid = intval(abs($_REQUEST['langid']));
			
			$getLanguageInfo = $db->query("select * from " . PFX . "languages where id='$langid'");
			
			if($db->total($getLanguageInfo) !== 0){
				
				$rowsInfo = $db->rows($getLanguageInfo);
				
				$getWords = $db->query("select * from " . PFX . "language_words where language_code='$rowsInfo[language_code]'");	
				
				$XML = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<elia>\n\t<language lang_code=\"$rowsInfo[language_code]\">\n";
				
				while($rows = $db->rows($getWords)){
				
					$wordElement = "\t\t<word code=\"$rows[word_name]\"";
					
					if($rows['module_id'] !== "") $wordElement .= " module=\"$rows[module_id]\"";
					
					if($rows['word_type'] == 1) $wordElement .= " admin=\"admin\"";
					
					$wordElement .= "><![CDATA[" . $rows['word_value'] . "]]></word>";
					
					$XML .= $wordElement . "\n";
				
				}
				
				$XML .= "\t</language>\n</elia>";
				
				$xmlFileName = "elia_language_" . $rowsInfo['language_code'] . ".xml";
				
				header("Content-Disposition: attachment; filename=$xmlFileName"); 
				
				print $XML;
		
			}
		
		} // End Export Function 
		
		public function deleteLanguage(){
		
			global $db , $adinc;
			
			$langid = intval(abs($_REQUEST['langid']));
			
			$core_setting = $db->getfastRows("select site_language , admin_language from " . PFX . "core");
	
			if($langid == $core_setting['site_language'] || $langid == $core_setting['admin_language']){
			
				$adinc->redAlert('لا تستطيع حذف اللغة الإفتراضية');
				
				return false;
						
			}
		
		
			$checkLanguage = $db->query("select * from " . PFX . "languages where id='$langid'");
			
			if($db->total($checkLanguage) == 0){
			
				$adinc->redAlert('الإجراء غير سليم');
				
				return false;
				
			}
			
			$langInfo = $db->rows($checkLanguage);
			
			$deleteWord = $db->query("delete from " . PFX . "language_words where language_code='$langInfo[language_code]'");
			
			$deleteLanguage = $db->query("delete from " . PFX . "languages where id='$langid'");
			
			if($deleteLanguage){
			
				$adinc->greenAlert('تمت عملية الحذف بنجاح');	
			
				$adinc->location('languages.php');
			}
		}
		
	}
?>