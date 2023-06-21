function deleteLog($id){

	$(".systemAlert").fadeIn();
	$("#loader").load('ajax/security.php?action=delete_log&id=' + $id , function(){
		$("#loader").hide().fadeIn();
		$(".systemAlert").fadeOut();
	});
	$("#log-" + $id).fadeOut();
		
}

function deleteIP($id){

	$(".systemAlert").fadeIn();
	$("#loader").load('ajax/security.php?action=delete_ip&id=' + $id , function(){
		$("#loader").hide().fadeIn();
		$(".systemAlert").fadeOut();
	});
	$("#ip-" + $id).fadeOut();
		
}


$(document).ready(function(){
	
	$("#submit_core").click(function(){
								
			var request_safe = $("#request_safe").val();

			var post_safe = $("#post_safe").val();

			var unsafe_functions = $("#unsafe_functions").val();

			var auto_block_time = $("#auto_block_time").val();

			var auto_block = $("#auto_block").val();
			
			var links_in_comments = $("#links_in_comments").val();
			
			if(empty(request_safe) == ""){
				
					var getRed = redBox("request_safe");
				
				}else{
				
					var getUnRed = reRedBox("request_safe");
				}
				
			if(empty(post_safe) == ""){
				
					var getRed = redBox("post_safe");
				
				}else{
				
					var getUnRed = reRedBox("post_safe");
				}
				
			if(empty(unsafe_functions) == ""){
				
					var getRed = redBox("unsafe_functions");
				
				}else{
				
					var getUnRed = reRedBox("unsafe_functions");
				}
				
			if(empty(auto_block_time) == ""){
				
					var getRed = redBox("auto_block_time");
				
				}else{
				
					var getUnRed = reRedBox("auto_block_time");
				}
				
			if(empty(auto_block) == ""){
				
					var getRed = redBox("auto_block");
				
				}else{
				
					var getUnRed = reRedBox("auto_block");
				}
			
			if(empty(unsafe_functions) == "" || empty(auto_block) == "" || empty(auto_block_time) == "" || empty(request_safe) == "" ||  empty(post_safe) == ""){ return false; }
			
			$("#submit_alert").fadeOut().fadeIn("slow").html('<center><img src="images/progs.gif" /></center>');

			$.post("ajax/security.php?action=edit_setting" , {
				
				"request_safe" : request_safe ,
				"unsafe_functions" : unsafe_functions ,
				"auto_block" : auto_block ,
				"auto_block_time" : auto_block_time ,
				"post_safe" : post_safe ,
				"links_in_comments" : links_in_comments ,
				"sender" : 1,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					
				});
		});
		
});
