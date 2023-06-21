// JavaScript Document

	function empty(string){
		
			return string.replace(/ /g , "");
		}
		
	function redBox(id){
		
			$("#td_" + id).css({"background" : "#ffebe8"});
			
		}
	
	function reRedBox(id){

			$("#td_" + id).css({"background" : ""});
		
	}
	
	function _lang($code){
	
		return(window['LANGUAGE_' + $code]);
				
	}

	$(document).ready(function(){

			var F = "left";
				
		//	var ReBoxSize =	$(".center").css({"height" : $(window).scrollX + "px"});
		
			$('.menu_title').append('<span class="toggleLink" style="float:'+ F +'; clear:both;font-weight:bolder;">إخفاء</span>');
		
			$('span.toggleLink').click(function(){
		
			if ($(this).text()=="إخفاء") {
				
					$(this).html("إظهار");
					$(this).parent().next('.menu_body').fadeOut('slow');
				
				}else {
					
				$(this).html("إخفاء");
				$(this).parent().next('.menu_body').fadeIn('slow');
			}
			
		});
		
		$(".notify_click").click(function(){
		
			if($(".notify_body").css("display") == "none"){
				
				$(".notify_body").show()
				$(".notify_click").css({"padding-bottom":"15px"});
				
			}else{
				
				$(".notify_body").hide();
				$(".notify_click").css({"padding-bottom":""});
			}
		});
		
	/*	$("a[rel=js]").click(function(){
			
			var $this = $(this);
			$("body").append("<div id='fastloader' align='right' style='padding:7px;'><img src='images/progs.gif' /></div>");

			$("body").load($this.attr("href") , function(){
				
				window.location.hash = "#/" + $this.attr("href");
				$("#fastloader").hide();
			});
			
			return false;	
		});
	*/
	 //check if hash tag exists in the URL
		if(window.location.hash) {
		 
		 //set the value as a variable, and remove the #
		 var hash_value = window.location.hash.replace('#/', '');

		var CurrentLoc = window.location;
			CurrentLoc.split("#/");
		
		
		alert(CurrentLoc.split("#/")[0]);
		
	/*	$("body").load(hash_value , function(){
				
				window.location.hash = "#/" + hash_value;
					
			});	*/	 		 
		}
		
	});
	
	
function checkAll(form){
  for (var i = 0; i < form.elements.length; i++){
    eval("form.elements[" + i + "].checked = form.elements[0].checked");
  }
}