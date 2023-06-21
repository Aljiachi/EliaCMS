<?php
		
	define('dbhost' , 'loaclhost');
	define('dbname' , 'kcms');
	define('dbpass' , '');
	define('dbuser' , 'root');
	
	class config{

			private $dbtype = 'pdo';
			private $host = "localhost" ;
			private $user = "root";
			private $pass = "";
			private $dbname = "kcms";
			public  $modulesPath = "modules";
			public  $pluginsPath = "plugins";
			public  $admincpPath = "admincp";
			public  $EmaEmail  = "php4u@live.com"; 
			public  $enpasswordFunctions = "md5"; // md5|sha1|md5|md5|sha1...
			public  $ipBlock	 = true; // [Active:true , unActive:false]
			public  $eliaConnection , $db , $elia;

			public function __construct(){
	
				// intlz hooksPath Verbale for Easy way
				$this->hooksPath = $this->pluginsPath;
				
			}

			private function connect(){
				
					require 'libs/adodb/adodb.inc.php';

					$this->db             = ADONewConnection($this->dbtype);
					$this->eliaConnection = $this->db->Connect($this->host, $this->user, $this->pass , $this->dbname);
					/*if(!$this->eliaConnection)
					{
						$headers ="MIME-Version: 1.0 \r\n";
						$headers.="from: $this->EmaEmail \r\n";
						$headers.="Content-type: text/html;charset=utf-8 \r\n";
						$headers.="X-Priority: 3\r\n";
						$headers.="X-Mailer: smail-PHP ".phpversion()."\r\n";
		
						$message = "<html><head>
						  <title>Database Error</title>
						  <style>P,BODY{ font-family:Windows UI,arial; font-size:16px; }</style>
						  </head>
						  <body>
						  <br><br><blockquote><b>There was an error connecting the database.</b><br>
						  <br><br><b>Error Query</b><br>
						  <form name='mysql'><textarea dir=\"ltr\" rows=\"8\" cols=\"60\">" .  mysql_error() . "\n$err</textarea></form></blockquote><br>
						  <p><b><a href=\"http://www.elia.net/\" target=\"_blank\">Powered by: Elia-CMS</a></b></p></body></html>";
						  
						 @mail($this->EmaEmail , "connection Error - Elia-CMS" , $message , $headers);

						 die('<h1 align="center">Under Work , Please Visit Us Again</h1>');
					}*/
					

				}
				
			public function run(){
				
					$connect = $this->connect();
						
					// Make sure a default timezone is set... silly PHP 5.
					if (ini_get("date.timezone") == "") date_default_timezone_set("GMT");

					// Start Of System DB Tables
					define('PFX', '');
					// Modules Path
					define('modulesPath', $this->modulesPath);
					// Plugins Path
					define('pluginsPath', $this->hooksPath);
					// Plugins Path
					define('hooksPath', $this->hooksPath);
					define('pluginsPath', $this->hooksPath);

				}
				
				public function fileMissed($file_name){

						$headers ="MIME-Version: 1.0 \r\n";
						$headers.="from: $this->EmaEmail \r\n";
						$headers.="Content-type: text/html;charset=utf-8 \r\n";
						$headers.="X-Priority: 3\r\n";
						$headers.="X-Mailer: smail-PHP ".phpversion()."\r\n";
						
						$message = "<html><head>
						  <title>System Error - File Missed</title>
						  <style>P,BODY{ font-family:Windows UI,arial; font-size:16px; }</style>
						  </head>
						  <body>
						  <br><br><blockquote><b>There was an error connecting the database.</b><br>
						  <br><br><b>File name : $file_name in $file_path</b><br></blockquote><br>
						  <p><b><a href=\"http://www.elia.net/\" target=\"_blank\">Powered by: Elia-CMS</a></b></p></body></html>";
						  
						 @mail($this->EmaEmail , "System Error - Elia CMS" , $message , $headers);

				}
				
			public function enpassword($string){
			
				$functions = explode("|" , $this->enpasswordFunctions);
								
				foreach($functions as $function){
				
					$string = $function($string);	
				}
				
				return $string;
			}
			
		  public function public_base_directory(){
			  
			//get public directory structure eg "/top/second/third"
			$public_directory = dirname($_SERVER['PHP_SELF']);
			//place each directory into array
			$directory_array = explode('/', $public_directory);
			//get highest or top level in array of directory strings
			$public_base = max($directory_array);
		   
			return $public_base;
		} 
		
	}

	$config = new config;
	$config->run();
	
	// Global Values 	
	$GET_GLOBAL_HEADER_PARAMS = "";
	$GET_GLOBAL_TEMPS_ASSIGN  = array();
	
?>