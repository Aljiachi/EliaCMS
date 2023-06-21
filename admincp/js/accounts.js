// JavaScript Document

	function checkFiled($name , $alert_box){
	
			var $checkValue = $("#" + $name).val();
				
			$checkValue.replace(/ /gi , "");
					
			if($checkValue == ""){
					
				$("#" + $alert_box).html('<span class="red_alert">' + _lang("ERROR_EMPTY") + '</span>').fadeIn("slow");
			
				return false;
						
			}
	}
	
	function addAccountAccess(){
	
		var RETURN = true;
		
		// Check nickName
		RETURN = checkFiled("account_nickname" , "alert_nickname");

		// Check Email
		RETURN = checkFiled("account_email" , "alert_email");
	
		// Check Password
		RETURN = checkFiled("account_password" , "alert_password");
		
		// Check Password
		RETURN = checkFiled("account_password" , "alert_password");
		
		return RETURN;
	}
	
	function searchBy($type){
	
		var LOCATION = "accounts.php?action=search&by=" + $type + "&q=";	
	
		var SEARCHQ = $("#accounts_search_query").val();
				
		// Go Go 
		window.location = LOCATION + SEARCHQ;
	}
	
	