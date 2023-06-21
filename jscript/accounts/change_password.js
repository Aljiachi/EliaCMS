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

	function _var($verb){
	
		return(window[$verb]);
				
	}
		
	function accountsChangePassword(){
	
		// check password input 
		$checkPassword = accountsCheckInput('password');		
			
		if($checkPassword == false){  $("html, body").animate({ scrollTop: 0 }, "slow"); return false; }
		
		if($("#input-accounts-password").val() !== $("#input-accounts-password-confirm").val()){
		
			$("#input-accounts-password-confirm").css({"border" : "1px solid red"});	
			
			 $("html, body").animate({ scrollTop: 0 }, "slow");

			 return false;
			 			
		}		

	$.post("ajaxar?act=CHANGE_PASSWORD&accountid=" + _var("accountid") , {
			"accounts-change-password-submit"	: 1	,
			"input-accounts-password" 			: accountsGetValue('password') ,
			"input-accounts-password-confirm" 	: accountsGetValue('password-confirm')
			} , function(d){
												
				var $response = JSON.parse(d);
												
				if($response.result == "faild"){

			 		$("html, body").animate({ scrollTop: 0 }, "slow");

					$("#request-return").hide().fadeIn().html('<div class="red">' + $response.msg + '</div>');
					
				}else if($response.result == "complate"){
				
					$(".accounts-signup").slideUp();	
					
					$("#request-return").hide().fadeIn().html('<div class="green">' + $response.msg + '</div>');
										
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