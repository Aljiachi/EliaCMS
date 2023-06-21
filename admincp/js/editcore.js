
$(document).ready(function(){
	
	$("#submit_core").click(function(){
						
			var title = $("#title_core").val();

			var meta = $("#meta_core").val();

			var keywords = $("#keywords_core").val();

			var siteurl = $("#siteurl_core").val();

			var reportByEmail = $("#report_core").val();

			var siteLanguage = $("#site_language_core").val();

			var adminLanguage = $("#admin_language_core").val();
			
			if(empty(title) == ""){
				
					var getRed = redBox("title");
				
				}else{
				
					var getUnRed = reRedBox("title");
				}
				
			if(empty(reportByEmail) == ""){
				
					var getRed = redBox("report");
				
				}else{
				
					var getUnRed = reRedBox("report");
				}
				
			if(empty(siteurl) == ""){
				
					var getRed = redBox("siteurl");
				
				}else{
				
					var getUnRed = reRedBox("siteurl");
				}
				
			if(empty(meta) == ""){
				
					var getRed = redBox("meta");
				
				}else{
				
					var getUnRed = reRedBox("meta");
				}
				
			if(empty(keywords) == ""){
				
					var getRed = redBox("keywords");
				
				}else{
				
					var getUnRed = reRedBox("keywords");
				}
			
			if(empty(keywords) == "" || empty(meta) == "" || empty(title) == "" || empty(siteurl) == "" ||  empty(reportByEmail) == ""){ return false; }
			
			$("#submit_alert").fadeOut().fadeIn("slow").html('<center><img src="images/progs.gif" /></center>');

			$.post("ajax/editcore.php" , {
				
				"title" : title ,
				"meta" : meta ,
				"siteurl" : siteurl ,
				"keywords" : keywords ,
				"report_byemail" : reportByEmail ,
				"site_language" : siteLanguage , 
				"admin_language" : adminLanguage ,
				"sender" : 1,
				
				} , function(date){
				
					$("#submit_alert").fadeOut().fadeIn("slow").html(date);
					
				});
		});
		
});
