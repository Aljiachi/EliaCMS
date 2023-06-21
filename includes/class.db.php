<?php
	
	try {
							
		$pdo = new PDO("mysql:host=localhost;dbname=" . dbname , dbuser , dbpass);
							
	} catch(PDOException $e){
						
			die("Connect Later");
	}
	
	
	class db{
		
		private $Error = array();

		public function query($sql){
		
			global $pdo;
			
			$Query = $pdo->query($sql);	
			
			//if(!$Query){ $this->Error[] = array(mysql_error() , __FILE__ , __LINE__ ); }
			
			return $Query;
		}
		
		public function rows($query){
			
			return $query->fetch();
		
		}
		
		public function object($query){
			
			return $query->fetch();
		
		}
		
		public function total($query){
		
			return count($query->fetchAll());	
		
		}
		
		public function getTotal($table){
		
			$Query = $this->query("select `id` from $table");
			
			//if(!$Query){ $this->Error[] = array(mysql_error() , __FILE__ , __LINE__ ); }
			
			return $this->total($Query);
				
		}
		
		public function getfastRows($query){
			
				$getQuery = $this->query($query);
				
				//if(!$getQuery){ $this->Error[] = array(mysql_error() , __FILE__ , __LINE__ );  }
				
				return $this->rows($getQuery);
				
		}
		
		
		public function getfastTotal($query){
			
				$getQuery = $this->query($query);
				
				//if(!$getQuery){ $this->Error[] = array(mysql_error() , __FILE__ , __LINE__ );  }
				
				return $this->total($getQuery);
				
		}
			
		public function getTotalById($table , $id){
		
			$Query = $this->query("select `id` from $table where id = $id");
			
			//if(!$Query){ $this->Error[] = array(mysql_error() , __FILE__ , __LINE__ ); }
			
			return $this->total($Query);
				
		}
		
		public function insertId(){
		
			global $pdo;
			
			return $pdo->lastInsertId();
				
		}
	
		public function getErrors(){
		
			return($this->Error);	
		}
	
		public function close(){
		
			global $logs , $config;
						
			if(count($this->Error) != 0){
							
				foreach($this->Error as $var){
						
					$logs->addLog(3 , "MYSQL_ERROR{,message: ".htmlspecialchars(addslashes($var[0])).",line: ".$var[2] ." ,page: ".htmlspecialchars(addslashes($var[1])).",}");		

				}
			}
			
			// Close Elia Connection
			$config->db->_close();
			
		}

		public function debug_string_backtrace() {
			
			ob_start();
			
			debug_print_backtrace();
			
			$trace = ob_get_contents();
			
			ob_end_clean();
	
			// Remove first item from backtrace as it's this function which
			// is redundant.
			$trace = preg_replace ('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1);
	
			// Renumber backtrace items.
			$trace = preg_replace ('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace);
	
			return $trace;
			
		} 			
		
	}
	
	// New db Object
	$db = new db;
	
?>