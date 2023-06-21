
	function shareFacebook($url){
	
		window.open('https://www.facebook.com/sharer/sharer.php?u=' + $url , 'shareFacebook' , 'width=350 , height=300' , null);	
		
	}
	
	function shareTwitter($Content , $url){
	
		window.open('https://twitter.com/intent/tweet?url=' + $url + '&text=' + $Content + ' : ' , 'shareTwitter' , 'width=400 , height=250' , null);	
		
	}
	
	function shareGooglePlus($url){
	
		window.open('https://plus.google.com/share?url=' + $url , 'shareGooglePlus' , 'width=400 , height=450' , null);	
		
	}