$(document).ready(function(){

	$("#getFile").click(function(){
			
			$("#pickfiles").hide();
			$("#uploadfiles").hide();
			$(".systemAlert").fadeIn();

		$.post("fast-modules.php?action=admin&module=" + _lang("ModuleID") +  "&method=uploader&type=remote" , {
			"url" : $("#file_url").val() , 
			"key" : _lang("Videokey")
			} , function(date){

				$(".systemAlert").fadeOut();

				if(date == "error"){

					$("#UrlAlerts").html("<red>حدث طأ في رفع الملف ... حاول مرى اخرى</red>");

				}else{
					
					$("#info_area").fadeIn();
					$("#UrlAlerts").html("<green>أكتمل رفع الفيديو بنجاح ... قم بتعديل معلوماته</green>");
					$("#submit").removeAttr("disabled");
					$("#submit").removeAttr("class");

				}
				
			});	
	});	
		
	$("#getYoutube").click(function(){
			
			$("#pickfiles").hide();
			$("#uploadfiles").hide();
			$(".systemAlert").fadeIn();
		
		$.post("fast-modules.php?action=admin&module=" + _lang("ModuleID") +  "&method=youtubeDownloader" , {
			"videoid" : $("#youtube_url").val() , 
			"key" : _lang("Videokey")
			} , function(date){

				$(".systemAlert").fadeOut();
				
				if(date == "error"){

					$("#YoutubeAlerts").html("<red>حدث طأ في رفع الملف ... حاول مرى اخرى</red>");
					
				}else{
					
					$("#YoutubeAlerts").html("<green>تم جلب المعلومات بنجاح ... أختر الجودة المناسبة</green>" + date);

				}
				
			});	
	});	
});

function changeHandler(cont){

	$(".bbox").hide();
	$("#uploadTypes a").attr("class" , "");
	
	switch(cont){
	
		case 1: $("#boxFile").fadeIn();  $("#uploadFile").attr("class" , "selected"); break;	
		case 2: $("#boxUrl").fadeIn();  $("#uploadUrl").attr("class" , "selected"); break;	
		case 3: $("#boxYoutube").fadeIn();   $("#uploadYoutube").attr("class" , "selected");break;
			
	}
}

function downloadFromYoutube($url , $thumb , $mimeType){
	
		$(".systemAlert").fadeIn();

		$.post("fast-modules.php?action=admin&module=" + _lang("ModuleID") +  "&method=uploader&type=youtube" , {
			"url" : $url , 
			"thumb" : $thumb , 
			'mtype' : $mimeType , 
			"key" : _lang("Videokey")
			} , function(date){

				$(".systemAlert").fadeOut();

				if(date == "error"){

					alert('حدث طأ في رفع الملف ... حاول مرى اخرى');

				}else{
					
					$("#photoInput").hide();
					$("#info_area").fadeIn();
					$("#YoutubeAlerts").html("<green>أكتمل رفع الفيديو بنجاح ... قم بتعديل معلوماته</green>");
					$("#submit").removeAttr("disabled");
					$("#submit").removeAttr("class");

				}
				
		});			
}

var uploader = new plupload.Uploader({
	runtimes : 'html5,html4',
	browse_button : 'pickfiles',
	container: 'container',
	multi_selection:false,
	chunk_size : '1mb',
    preinit: attachCallbacks,
    unique_names : true,
    multiple_queues : false,
	max_file_size : '1200mb',
	url : 'fast-modules.php?action=admin&module=' + _lang("ModuleID") + '&method=uploader&key=' + _lang("Videokey") + '&type=file'
});

function attachCallbacks(Uploader) {
 	if( (uploader.total.uploaded + 1) == uploader.files.length){
		    uploader.files.length = 0;                      
  		}
}

function closeUploadDialog(dialogId , Target){
	
	//var q = new plupload.Uploader({});
	uploader.removeFile(Target);
	document.getElementById(dialogId).style.display = "none";	
	
}

uploader.bind('FilesAdded', function(up, files) {
	$("#pickfiles").hide();
	$("#uploadfiles").fadeIn();
	for (var i in files) {
		$("#input_1").val(files[i].name);
		document.getElementById('filelist').innerHTML += '<div id="' + files[i].id + '"><b> <div class="file">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ')</div> </b></div>';
	}
});


uploader.bind('UploadProgress', function(up, file) {
	if(file.percent == 100){
			$("#container").html("<green>أكتمل رفع الفيديو بنجاح ... قم بتعديل معلوماته</green>");
			$("#submit").removeAttr("disabled");
			$("#submit").removeAttr("class");

	}else{
		
		document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<div class="Progress"><span style="width:'+ file.percent+'%;" class="leng">' + file.percent + "%</span></div>";

	}
});

	

document.getElementById('uploadfiles').onclick = function() {
	uploader.start();
	$("#info_area").fadeIn();
	return false;
};

uploader.init();