
$(document).ready(function(){
	
	$("#submit_accounts_setting").click(function(){
		
		var $accounts_avatar_width 		= $("#accounts_avatar_width").val();

		var $accounts_avatar_height 	= $("#accounts_avatar_height").val();

		var $accounts_allow_signup 		= $("#accounts_allow_signup").val();

		var $accounts_activation 		= $("#accounts_activation").val();

		var $accounts_default_group 	= $("#accounts_default_group").val();

		var $activation_default_group 	= $("#activation_default_group").val();

			
		if(empty($accounts_avatar_width) == ""){
				
			var getRed = redBox("avatar_width");
				
		}else{
				
			var getUnRed = reRedBox("avatar_width");
		
		}

		if(empty($accounts_avatar_height) == ""){
				
			var getRed = redBox("avatar_height");
				
		}else{
				
			var getUnRed = reRedBox("avatar_height");
		
		}
		
		if(empty($accounts_avatar_width) == "" || empty($accounts_avatar_height) == ""){ return false; }
		
		
			$("#submit_alert").fadeOut().fadeIn("slow").html('<center><img src="images/progs.gif" /></center>');

			$.post("ajax/editaccounts_setting.php" , {
				"accounts_avatar_height" 	: $accounts_avatar_height 	, 
				"accounts_avatar_width" 	: $accounts_avatar_width 	, 
				"accounts_allow_signup" 	: $accounts_allow_signup 	, 
				"accounts_activation"		: $accounts_activation 		, 
				"accounts_default_group"	: $accounts_default_group 	,
				"activation_default_group"  : $activation_default_group ,
				"sender" 					: 1							,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					
				});
						
	});
	
});
