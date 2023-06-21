// JavaScript Document

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
	
	function accountsSignup(){
		
		// check nickname input 
		$checkNickname = accountsCheckInput('nickname');
		
		// check email input 
		$checkEmail = accountsCheckInput('email');		
		
		// check password input 
		$checkPassword = accountsCheckInput('password');		

		/*
			if($checkPassword == false || $checkEmail == false || $checkNickname == false){  $("html, body").animate({ scrollTop: 0 }, "slow"); return false; }
		 
		if($("#input-accounts-password").val() !== $("#input-accounts-password-confirm").val()){
		
			$("#input-accounts-password-confirm").css({"border" : "1px solid red"});	
			
			 $("html, body").animate({ scrollTop: 0 }, "slow");

			 return false;
			 			
		}		
		
		var $email = $("#input-accounts-email").val();
		
		if(!isEmail($email)){
		
			$("#input-accounts-email").css({"border" : "1px solid red"});	
			
			 $("html, body").animate({ scrollTop: 0 }, "slow");

			 return false;
			 	
		}
		*/

		$.post("ajaxar?act=COMPLATE_REGISTRATION" , {
			"accounts-signup-submit"			: 1	,
			"input-accounts-nickname" 			: accountsGetValue('nickname') ,
			"input-accounts-email" 				: accountsGetValue('email') ,
			"input-accounts-password" 			: accountsGetValue('password') ,
			"input-accounts-password-confirm" 	: accountsGetValue('password-confirm') ,
			"input-accounts-gender" 			: accountsGetValue('gender') ,
			"input-accounts-location" 			: accountsGetValue('location')
			} , function(d){
								
				alert(d);
				
				var $response = JSON.parse(d);
												
				if($response.result == "faild"){

			 		$("html, body").animate({ scrollTop: 0 }, "slow");

					$("#request-return").hide().fadeIn().html('<div class="red">' + $response.msg + '</div>');
					
				}else if($response.result == "complate"){
				
					$(".accounts-signup").slideUp();	
					
					$("#request-return").hide().fadeIn().html('<div class="green">' + $response.info.nickname + ' , ' + $response.msg + '</div>');
					
					setTimeout("window.location = 'accounts?act=login';" , 1300);
					
				}else if($response.result == "awaiting_activation"){
				
					$(".accounts-signup").slideUp();	
					
					$("#request-return").hide().fadeIn().html('<div class="green">' + $response.info.nickname + ' , ' + $response.msg + '</div>');
					$("#request-return").hide().fadeIn().append('<div class="alert">' + $response.act + '</div>');
					//setTimeout("window.location = 'index';" , 3300);
					
				}
				
				return false;
			});

		return false;
	}
	
	function accountsCheckPasswordLength(){
		
		var $passwordValue = $("#input-accounts-password").val();
		
		$("#accounts-signup-password-length").fadeIn();
		
		if($passwordValue.length <= 5){
		
				$("#accounts-signup-password-length div").css({'background' : 'red'}).animate({width: '20%'}, 800);
		
		}else if($passwordValue.length > 5 && $passwordValue.length <= 8){
		
				$("#accounts-signup-password-length div").css({'background' : 'blue'}).animate({width: '40%'}, 800);	
		
		}else if($passwordValue.length > 8 && $passwordValue.length <= 12){
		
				$("#accounts-signup-password-length div").css({'background' : '#4E1402'}).animate({width: '60%'}, 800);	
	
		}else if($passwordValue.length > 12 && $passwordValue.length <= 14){
		
				$("#accounts-signup-password-length div").css({'background' : 'green'}).animate({width: '100%'}, 800);	
		}

	}
	
	function accountsCheckEmailExists(){
	
		var $emailValue = $("#input-accounts-email").val();

		$.post("ajaxar.php?act=CHECK_EMAIL" , {		"ajaxar_email" : $emailValue	} , function(d){
			
				var $result = JSON.parse(d);
				
				if($result.return == "true"){
				
					$("#request-return").fadeIn().html('<div class="green">' + $result.msg + '</div>');
				
				}else{

					$("#request-return").fadeIn().html('<div class="red">' + $result.msg + '</div>');
					
					return false;
				}
				
			});
			
	}