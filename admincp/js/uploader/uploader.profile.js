
$(document).ready(function(){
	
	var $accountId = $("#account_accountid").val();
	
	var uploader = new plupload.Uploader({
		runtimes : 'html5,html4',
		browse_button : 'pickfiles',
		container: 'container',
		multi_selection:false,
		chunk_size : '1mb',
		preinit: attachCallbacks,
		unique_names : true,
		multiple_queues : false,
		max_file_size : '2mb',
		filters : [
	        {title : "Image files", extensions : "jpg,gif,png"}
		],
	    resize : {width : 320, height : 240, quality : 90},
		url : 'juploader.php?action=change_avatar&accountid=' + $accountId
	});
		
	function attachCallbacks(Uploader) {
		if( (uploader.total.uploaded + 1) == uploader.files.length){
				uploader.files.length = 0;                      
			}
	}
	
	uploader.bind('FilesAdded', function(up, files) {
		
		// Make Loader
		$("#pickfiles").attr("src" , "images/loader.gif");
		$(".account-avatar-box .options").css({"opacity" : "1"});	
		$(".systemAlert").fadeIn();		
		// Start Uploade
		setTimeout(function () { uploader.start(); }, 100);		
	
	});
	
	uploader.bind('FileUploaded', function(up, file, info) {

		$("#account-avatar").attr("src" , "../upload/accounts/" + info.response);
		$("#pickfiles").attr("src" , "icons/change.png");
		$(".account-avatar-box .options").css({"opacity" : "1"});	
		$(".systemAlert").fadeOut();
		
	});

	uploader.init();

});