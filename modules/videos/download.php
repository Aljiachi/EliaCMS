<?php

	if(script_run !== true){
		
		die('<h1 align="center">'. _lang("ERROR_404") .'</h1>');
	}

	// Hide All Templates
	define('templatesShown' , false);
	
	$id = _id($_REQUEST['id']);
	
	if($id == 0 || !isset($_REQUEST['id']) ){ die('<h1 align="center">'. _lang("ERROR_404") .'</h1>'); }
	
	$query	=	$db->query("select * from videos_module_files where id=$id");
	
	if($db->total($query) == 0){ die('<h1 align="center">'. _lang("ERROR_404") .'</h1>'); }
	
	$update = $db->query("update videos_module_files set downloads=downloads+1 where id=$id");
	
	$download = $db->rows($query);
		
	$action = _get("action");
		
	// define content path including trailing slash
	// for example, if your files are in /w/media (/w/media/file.mp4), then your content base would be /w/media/
	$content_base= "upload/videos/files/";
	
	// full path of default content
	// if this script is called with no parameters, then this file is provided
	$default_content = "upload/videos/files/" . $download['file'];

	$ext = strtolower(end(explode('.', $download['file'])));
	
	// download file chunk size (in bytes)
	$chunksize = 1*(1024*1024*1024);
	
	// disable caching
	header("Pragma: public");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private");
	
	// disable output buffering
	@ob_end_clean();
	
	// disable execution time limit
	set_time_limit(0);
	
	// disable compression (for IE)
	if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
	
	$file = $default_content;
	
	// check for valid file
	if ( !file_exists($file) ) die("File does not exist!");
		
	$mime = _mediaMimeType($download['file']);

	// file specific headers
	header('Accept-Ranges: bytes');
	header("Content-Description: File Transfer");
	header("Content-Type: $mime");
	
	if($action !== "videoplayback"){
		
		header('Content-Disposition: attachment; filename="'.$download['title'] . '.' . $ext .'"');
	
	}
	
	header("Content-Transfer-Encoding: binary");
	
	// determine file size
	$size = filesize($file);
	// workaround for int overflow
	if ( $size < 0 ) {
	$size = exec('ls -al "'.$file.'" | awk \'BEGIN {FS=" "}{print $5}\'');
	}
	
	// add multipart download and resume support
	if ( isset($_SERVER["HTTP_RANGE"]) ) {
	list($a,$range) = explode("=",$_SERVER["HTTP_RANGE"],2);
	list($range) = explode(",",$range,2);
	list($range,$range_end) = explode("=",$range);
	$range = round(floatval($range),0);
	if ( !$range_end ) $range_end = $size-1;
	else $range_end = round(floatval($range_end),0);
	
	$partial_length = $range_end-$range+1;
	header("HTTP/1.1 206 Partial Content");
	header("Content-Length: $partial_length");
	header("Content-Range: bytes ".($range-$range_end/$size));
	}
	else {
	$partial_length = $size;
	header("Content-Length: $partial_length");
	}
	
	// output file
	$bytes_sent = 0;
	if ( $fp = fopen($file,"r") ) {
	// fast forward within file, if requested
	if (isset($_SERVER['HTTP_RANGE'])) fseek($fp,$range);
	// read and output the file in chunks
	while( !feof($fp) AND (!connection_aborted()) AND ($bytes_sent < $partial_length) ) {
	$buffer = fread($fp,$chunksize);
	print($buffer);
	flush();
	$bytes_sent += strlen($buffer);
	}
	fclose($fp);
	}
	else die("Unable to open file.");
	
	// end the script
	exit();
?>