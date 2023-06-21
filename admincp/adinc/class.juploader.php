<?php

	class eliaJuploader{
	
		public $cleanupTargetDir = false; // Remove old files
		public $maxFileAge; // Temp file age in seconds
		public $fileManager;
		public $targetDir;
		public $fileName;
		public $filekey;
		public $action;
		public $chunks;
		public $chunk;
		public $act;
		
		public function headers(){
		
			// HTTP headers for no cache etc
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");	
		
		}
		
		public function main(){
		
			global $adinc , $config;
			
			$objManager = new objectsManager;
	
			$this->fileManager = $objManager->getFilesObject();
			
			$this->maxFileAge = (5 * 3600);
			
			// 5 minutes execution time
			@set_time_limit(5 * 60 * 60);
			
			// Get parameters
			$this->chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
			$this->chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
			$this->fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';	
			$end = end(explode("." , $this->fileName));
			
			// Clean the fileName for security reasons
			$this->fileName = preg_replace('/[^\w\._]+/', '_', md5(rand(12345678,98765432)) . "." . $end);
						
			$siteInside = str_replace("/" . $config->admincpPath . "/juploader.php" , "" , $_SERVER['SCRIPT_NAME']);
			$siteInside = str_replace("/" , "" , $siteInside);
			
			// Append DOCUMENT_ROOT into $targetDir
			$this->targetDir = $_SERVER["DOCUMENT_ROOT"] . '/' . $siteInside . '/' . $this->targetDir;
		}
		
		public function uploade(){
		
			global $db , $adinc;
			
			// Make sure the fileName is unique but only if chunking is disabled
			if ($this->chunks < 2 && file_exists($this->targetDir . DIRECTORY_SEPARATOR . $this->fileName)) {
				$ext = strrpos($fileName, '.');
				$fileName_a = substr($fileName, 0, $ext);
				$fileName_b = substr($fileName, $ext);
			
				$count = 1;
				while (file_exists($this->targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
					$count++;
			
				$this->fileName = $fileName_a . '_' . $count . $fileName_b;
			}
			
			$filePath = $this->targetDir . DIRECTORY_SEPARATOR . $this->fileName;
			
			// Create target dir
			if (!file_exists($this->targetDir))
				@mkdir($this->targetDir);
			
			// Remove old temp files	
			if ($cleanupTargetDir && is_dir($this->targetDir) && ($dir = opendir($this->targetDir))) {
				while (($file = readdir($dir)) !== false) {
					$tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
			
					// Remove temp file if it is older than the max age and is not the current file
					if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
						@unlink($tmpfilePath);
					}
				}
			
				closedir($dir);
			} else
			//	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
				
			
			// Look for the content type header
			if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
				$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
			
			if (isset($_SERVER["CONTENT_TYPE"]))
				$contentType = $_SERVER["CONTENT_TYPE"];
			
			// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
			if (strpos($contentType, "multipart") !== false) {
				if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
					// Open temp file
					$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
					if ($out) {
						// Read binary input stream and append it to temp file
						$in = fopen($_FILES['file']['tmp_name'], "rb");
			
						if ($in) {
							while ($buff = fread($in, 4096))
								fwrite($out, $buff);
			
			 			} else
			
							die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			
						fclose($in);
						fclose($out);
						@unlink($_FILES['file']['tmp_name']);
			
					} else
			
						die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			
				} else
			
					die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			
			} else {
				
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen("php://input", "rb");
			
					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			
					fclose($in);
					fclose($out);
					
				} else
				
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}
			
			// Check if file has been uploaded
			if (!$this->chunks || $this->chunk == $this->chunks - 1) {
				// Strip the temp .part suffix off 
				rename("{$filePath}.part", $filePath);
			}
			
					
			if (!$this->chunks || $this->chunk == $this->chunks - 1) {
				
				// onUploaded Event
				$this->onUploaded($this->fileName);
				// return File name
				echo $this->fileName;
			}
					

		}
		
		public function onUploaded($fileName , $filePath=''){
		
			global $db , $adinc;
			
			$action = _safeString($_REQUEST['action']);
			
			switch($action){
			
				case "change_avatar" : 
					
					// account request id
					$accountId = _safeInt($_REQUEST['accountid']);
					
					// check if account exists
					if($db->getfastTotal("select account_id from " . PFX . "accounts where account_id='$accountId' limit 1") !== 0){
						
						$accountInfo = $db->getfastRows("select account_id,account_avatar from " . PFX . "accounts where account_id='$accountId' limit 1");
						
						// update Avatar
						$change = $db->query("update " . PFX . "accounts set account_avatar='$fileName' where account_id='$accountId' limit 1");
						
						// delete current Avatar
						$this->fileManager->deleteFile("../upload/accounts/" . $accountInfo['account_avatar']);
						// return true					
						if($change) return true;
						
					}
					
				break;	
			}
		}
		
	}
?>