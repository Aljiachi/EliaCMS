
	function showEditBox($id){
	
		$("#word_box_" + $id).hide();
		$("#edit_box_" + $id).show();
			
	}
	
	function updateWord($id){
	
		$(".systemAlert").fadeIn();
	
		var $word = $("#word_input_" + $id).val();
				
		$.post("ajax/languages.php?action=edit_word" , {
			"sender" : 1 , 
			"word" : $word , 
			"wordid" : $id
			} , function(date){
			
				$(".systemAlert").fadeOut();
				$("#word_box_" + $id).html($word).fadeIn();
				$("#edit_box_" + $id).hide();
			
		});
	}
	
	function deleteWord($id){
	
		$(".systemAlert").fadeIn();
		$("#loader").hide();
		$("#loader").load('ajax/languages.php?action=delete_word&wordid=' + $id , function(){
			$(".systemAlert").fadeOut();
		});
		$("#word-" + $id).fadeOut();
			
	}
	

	function addWord($language_code){
		
		var $word_name = $("#word_name").val();
		var $word_value = $("#word_value").val();
		var $module_id = $("#module_id").val();
		var $word_type = $("#word_type").val();
				
		if($language_code == ""){
		
			$(".extra_links a").css({"border" : "1px solid red"});
			return false;	
		}
		
		if($word_name == ""){
		
			$("#alert_name").html('<span class="red_alert">تحقق من الحقل</span>').fadeIn("slow");
		
			return false;
		}
		
		if($word_value == ""){
		
			$("#alert_value").html('<span class="red_alert">تحقق من الحقل</span>').fadeIn("slow");
		
			return false;
		}
	
		$(".systemAlert").fadeIn();
			
		var $t = "";
		
		$.post("ajax/languages.php?action=add_word" , {
			"sender" : 1 , 
			"word_name" : $word_name , 
			"word_value" : $word_value , 
			"module_id" : $module_id ,
			"word_type" : $word_type ,
			"language_code" : $language_code 
			} , function(date){
					
				$(".systemAlert").fadeOut();
		
				if(parseInt(date)){
										
					$t += "<tr id=\"word-"+date+"\">\n<td class='td_row'><div>"+$word_name+"</div></td>\n"; 
					$t += "<td class='td_row' align=\"right\"><div id=\"word_box_"+date+"\" onclick=\"showEditBox("+date+")\">"+$word_value+"</div><div id=\"edit_box_"+date+"\" style=\"display:none;\"><input type=\"text\" id=\"word_input_"+date+"\" value=\""+$word_value+"\" /><input type=\"button\" onclick=\"updateWord("+date+")\" value=\"حفظ\" /></div></td>\n";			
					$t += "<td class='td_row' align=\"center\"><a href='#delete' onclick='deleteWord("+date+")'><img src='icons/remove.png' border='0' /></a></td>\n"; 
					$t += '<td class=\'td_row\' align="center"><input type="checkbox" name="check[]" value="'+date+'" /></td>';
					$t += "</tr>\n";	
									
					$("#words_table").html($t + $("#words_table").html());
					$("#word-"+date).hide().fadeIn();
					$("#word_name").val("");
					$("#word_value").val("");
					
				}else{
				
					alert('الرمز ' + $word_name + ' مستخدم');	
				}
		});
			
	}
