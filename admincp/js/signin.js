$(document).ready(function(){
	
	$("#login_submit").click(function(){
									
			var userName = $("#username").val();

			var passWord = $("#password").val();
		
			if(empty(userName) == ""){
				
					var getRed = redBox("name");
				
				}else{
				
					var getUnRed = reRedBox("name");
				}

			if(empty(passWord) == ""){
				
					var getRed = redBox("pass");
				
				}else{
				
					var getUnRed = reRedBox("pass");
				}
				
			if(empty(userName) == "" || empty(passWord) == ""){
				
					return false;
				}
			$("#submit_loading").fadeIn('slow');
				
			$.post("ajax/signin.php" , {
				
				"username" : userName ,
				"password" : passWord ,
				"sender" : 1,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					$("#submit_loading").hide();
					
				});
				
			return false;
			
		});
			
});

function Login(){
								
			var userName = $("#username").val();

			var passWord = $("#password").val();
		
			if(empty(userName) == ""){
				
					var getRed = redBox("name");
				
				}else{
				
					var getUnRed = reRedBox("name");
				}

			if(empty(passWord) == ""){
				
					var getRed = redBox("pass");
				
				}else{
				
					var getUnRed = reRedBox("pass");
				}
				
			if(empty(userName) == "" || empty(passWord) == ""){
				
					return false;
				}
			$("#submit_loading").fadeIn('slow');
				
			$.post("ajax/signin.php" , {
				
				"username" : userName ,
				"password" : passWord ,
				"sender" : 1,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					$("#submit_loading").hide();
					
				});
				
			return false;	
}