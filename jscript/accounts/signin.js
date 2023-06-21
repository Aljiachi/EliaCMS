
	function accountsCheckInput(name){
	
		var $value = $("#input-accounts-" + name).val();
		var $inner = $("#input-accounts-" + name);
		
		if($value.replace(/ /gi , "") == ""){
		
			$inner.css({"border" : "1px solid red"});	
			
			return false;
		}
		
	
	}
	
	function accountsGetValue(name){
		
		var $value = $("#input-accounts-" + name).val();

		return($value);		
	}

	function isEmail(email) { 
		return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(email);
	} 
	

	function accountsSignin(){
		
		// check email input 
		$checkEmail = accountsCheckInput('email');		
		
		// check password input 
		$checkPassword = accountsCheckInput('password');		
		
		if($checkPassword == false || $checkEmail == false){  $("html, body").animate({ scrollTop: 0 }, "slow"); return false; }
	
		var $email = $("#input-accounts-email").val();
		
		if(!isEmail($email)){
		
			$("#input-accounts-email").css({"border" : "1px solid red"});	
			
			 $("html, body").animate({ scrollTop: 0 }, "slow");

			 return false;
			 	
		}
		
	$.post("ajaxar?act=COMPLATE_SIGNIN" , {
			"accounts-signin-submit"			: 1	,
			"input-accounts-email" 				: accountsGetValue('email') ,
			"input-accounts-password" 			: accountsGetValue('password') 
			
			} , function(d){
																
				var $response = JSON.parse(d);
												
				if($response.result == "faild"){

			 		$("html, body").animate({ scrollTop: 0 }, "slow");

					$("#request-return").hide().fadeIn().html('<div class="red">' + $response.msg + '</div>');
					
				}else if($response.result == "complate"){
				
					$(".accounts-signup").slideUp();	
					
					$("#request-return").hide().fadeIn().html('<div class="green">' + $response.info.nickname + ' , ' + $response.msg + '</div>');
					
					setTimeout("window.location = '" +  $response.referer + "';", 1300);
				
				}
			
			});
			
		return false;
		
	}