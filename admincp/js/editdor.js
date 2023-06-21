
$(document).ready(function(){
	
	$("#submit_dor").click(function(){
						
			var close_do = $("#close_do").val();
			
			var close_text = $("#close_text").val();
			
			if(empty(close_text) == ""){
				
					var getRed = redBox("closetext");
				
				}else{
				
					var getUnRed = reRedBox("closetext");
				}

			if(empty(close_text) == ""){ return false; }
			
			$("#submit_alert").fadeOut().fadeIn("slow").html('<center><img src="images/progs.gif" /></center>');

			$.post("ajax/editdor.php" , {
				
				"close_do" : close_do ,
				"close_text" : close_text ,
				"sender" : 1,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					
				});
		});
		
});
