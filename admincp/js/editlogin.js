
$(document).ready(function(){
	
	$("#submit_login").click(function(){
						
			var username = $("#user_login").val();

			var password = $("#pass_login").val();

			var admin_email = $("#user_email").val();
			
			if(empty(username) == ""){
				
					var getRed = redBox("name");
				
				}else{
				
					var getUnRed = reRedBox("name");
				}

			if(empty(admin_email) == ""){
				
					var getRed = redBox("email");
				
				}else{
				
					var getUnRed = reRedBox("email");
				}


			if(empty(username) == "" || empty(admin_email) == ""){ return false; }
			
			$("#submit_alert").fadeOut().fadeIn("slow").html('<center><img src="images/progs.gif" /></center>');

			$.post("ajax/editlogin.php" , {
				
				"username" : username ,
				"password" : password ,
				"admin_email" : admin_email ,
				"sender" : 1,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					
				});
		});
		
});
