
$(document).ready(function(){
	
	$("#submit_admintext").click(function(){
						
			var text = $("#admin_text").val();

			$("#submit_alert").fadeOut().fadeIn("slow").html('<img src="images/progs.gif" />');
						
			$.post("ajax/editatext.php" , {
				
				"text" : text ,
				"sender" : 1,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					
				});
		});
		
});
