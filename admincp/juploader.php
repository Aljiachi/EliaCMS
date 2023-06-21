<?php

	session_start();

	include("../includes/config.php");
	
	include("../includes/class.db.php");

	include("adinc/class.adinc.php");

	include("adinc/functions.php");
	
	include("adinc/class.objectsManager.php");

	include("adinc/class.juploader.php");
	
	
	$juploader = new eliaJuploader;
	// upload folder
	$juploader->targetDir = "upload/accounts";
	// HTTP headers for no cache etc
	$juploader->headers();
	// main Function
	$juploader->main();
	// upload Function
	$juploader->uploade();
	
?>