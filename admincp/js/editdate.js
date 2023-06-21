
$(document).ready(function(){
	
	$("#submit_date").click(function(){
						
			var date = $("#date_date").val();

			var time = $("#time_date").val();
			
			if(empty(date) == ""){
				
					var getRed = redBox("date");
				
				}else{
				
					var getUnRed = reRedBox("date");
				}
				
			if(empty(time) == ""){
				
					var getRed = redBox("time");
				
				}else{
				
					var getUnRed = reRedBox("time");
				}

			
			if(empty(date) == "" || empty(time) == ""){ return false; }
			
			$("#submit_alert").fadeOut().fadeIn("slow").html('<center><img src="images/progs.gif" /></center>');

			$.post("ajax/editdate.php" , {
				
				"type_date" : date ,
				"type_time" : time ,
				"sender" : 1,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					
				});
		});
		
});
