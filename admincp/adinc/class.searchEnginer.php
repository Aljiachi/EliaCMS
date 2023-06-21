<?php

	class eliaSearchEnginer{
	
		public $sql		, 
			   $result  ,
			   $error   ,
			   $row		;
		
		public $imenu = true;
		
		public $columns , $extra = array();
					   
		public function column($title , $value){
		
			$this->columns[$title] = $value;
				
		}
		
		public function extra($title , $value){
		
			$this->extra[$title] = $value;
				
		}
		
		public function result(){
		
			global $db , $adinc;
			
			$query = $db->query($this->sql);
			
			if(!is_array($this->columns)){ $this->error = "rows parameter not array"; return false;}

			if(!$query){ $this->error = "Mysql Error"; return false;}
			
			$this->result = "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
			
			foreach($this->columns as $key => $var){
			
				$this->result .= '<td class="td_title">' . $key .'</td>';	
			}
			
			foreach($this->extra as $keyextra => $varextra){
			
				$this->result .= '<td class="td_title">' . $keyextra .'</td>';	
			}
			
			$this->result .= '</tr>';
			
			while($this->row = mysql_fetch_assoc($query)){
			
				$this->result .= '<tr>';
		
				foreach($this->columns as $keyLong => $varLong){
				
					if(is_string($varLong)){
						
						$varLong = explode("." , $varLong);
						
						$this->result .= '<td class="td_row" width="'.$varLong[1].'">' . $this->row[$varLong[0]] .'</td>';	
				
					}else{
					
						$this->result .= '<td class="td_row"';
						
						if(!empty($varLong['width'])){
						
							$this->result .= ' width="'.$varLong['width'].'"';		
						}
						
						if($varLong['tags'] and is_array($varLong['tags'])){
							
							foreach($varLong['tags'] as $tag_name => $tag_value){
							
								$this->result .= ' ' . $tag_name . '="' . $tag_value . '"';	
							}
							
						}
						
						$this->result .= '>' . preg_replace("#\[(.*)]#esiU","\$this->row['\\1']" , $varLong['value']) .'</td>';	
							
					}
				}			
			
				foreach($this->extra as $keyextra => $varextra){
			
					$varextra = preg_replace("#\[(.*)]#esiU","\$this->row['\\1']",$varextra);
	
					$this->result .= '<td class="td_row">' . $varextra .'</td>';	
				
				}
				
				$this->result .= '</tr>';
					
			}
			
			$this->result .= '</table>';
			
			if($this->imenu){ 
			
				$adinc->getMenu($adinc->_lang("CONTROL_SEARCH_ENGINER_RESULT") , $this->result);
			
			}else{
				
				return($this->result);
	
			}
		}
		
		
	}
?>