
	function _var($verb){
	
		return(window[$verb]);
				
	}
		
	function accountsGetValue(name){
		
		var $value = $("#input-accounts-" + name).val();

		return($value);		
	}
	
	function viewSocialGroup($name){
	
		$(".social-networks").hide();	
		$("#social-network-" + $name).fadeIn('fast');

	}

	function accountsProfile(){
	
		$.post("ajaxar?act=COMPLATE_PROFILE&accountid=" + _var('accountid') , {
				"accounts-profile-submit"			: 1										,
				"input-accounts-gender" 			: accountsGetValue('gender') 			,
				"input-accounts-location" 			: accountsGetValue('location') 			,
				"input-accounts-birthdate-day"		: accountsGetValue('birthdate-day') 	,
				"input-accounts-birthdate-months"	: accountsGetValue('birthdate-months') 	,
				"input-accounts-birthdate-years" 	: accountsGetValue('birthdate-years')  	,
				"input-accounts-nickname"			: accountsGetValue('nickname')		   	,
				"input-accounts-social-facebook"	: accountsGetValue('social-facebook')  	,
				"input-accounts-social-twitter"		: accountsGetValue('social-twitter')   	,
				"input-accounts-social-youtube"		: accountsGetValue('social-youtube')   	,
				"input-accounts-social-googleplus"	: accountsGetValue('social-googleplus')
				} , function(d){
																																
					var $response = JSON.parse(d);
																		
					if($response.result == "faild"){
	
						$("html, body").animate({ scrollTop: 0 }, "slow");
	
						$("#request-return").hide().fadeIn().html('<div class="red">' + $response.msg + '</div>');
						
					}else if($response.result == "complate"){
					
						$(".accounts-signup").slideUp();	
						
						$("#request-return").hide().fadeIn().html('<div class="green">' + $response.msg + '</div>');
											
					}
				
				});
				
			return false;
			
	}
	