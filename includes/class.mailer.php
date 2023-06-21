<?php

	class eliaMiler{
	
		
		public  $toEmail , $fromEmail , $Subject , $Message , $dTemplate;
		
		
		private $headers , $sendact , $gTemplate;
		
		
		const 	DEFAULT_TEMPLATE = 'default';
		
		
		public function __counstruct($isHtml = false){
		
			global $db , $adinc;
			
			// default Template Name			
			$this->dTemplate = 'default';
			// set Admin Email as Default
			$this->fromEmail = _setting("admin_email");
			// headers Options
			$this->headers ="MIME-Version: 1.0 \r\n";
			$this->headers.="from: $this->fromEmail \r\n";
			
			// enable html Content if :isHtml true
			if($isHtml) $this->headers.="Content-type: text/html;charset=utf-8 \r\n";
			
			// headers Options
			$this->headers.="X-Priority: 3\r\n";
			$this->headers.="X-Mailer: smail-PHP ". _phpv() ."\r\n";
				
		} // end(__counstruct) 
		
		public function setTemplate($tempname=eliaMiler::DEFAULT_TEMPLATE){
		
			// set Template
			$this->Message = $this->getTemplate($tempname);	
		}
		
		public function send(){
			
			global $db , $adinc;
						
			if(mail($this->toEmail , $this->Subject , $this->Message , $this->headers))
				$this->sendact = true;
			else
				$this->sendact = false;
				
			return $this->sendact;
				
		}
		
		public function isSent(){
		
			return($this->sendact);	
		}
		
		function getTemplate($templateName='default'){

			global $setting;
						
			$this->gTemplate = '';
			
			switch($templateName){
			
				case "default"  :  
				default			:
									
					$this->gTemplate .= '<!DOCTYPE html>';
					$this->gTemplate .= '<html>';
					$this->gTemplate .= '<head>';
					$this->gTemplate .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
					$this->gTemplate .= '<title>'. $setting->rows['title'] .'</title>';
					$this->gTemplate .= '<style type="text/css">';
					$this->gTemplate .= 'body{background:#ececec; padding:0; margin:0;}';
					$this->gTemplate .= '.page{';
					$this->gTemplate .= 'width:600px;';
					$this->gTemplate .= 'margin-left:auto;';
					$this->gTemplate .= 'margin-right:auto;';
					$this->gTemplate .= 'position:relative;';
					$this->gTemplate .= 'margin-top:0;';
					$this->gTemplate .= 'background:#fff;';
					$this->gTemplate .= '-webkit-border-bottom-right-radius: 7px;';
					$this->gTemplate .= '-webkit-border-bottom-left-radius: 7px;';
					$this->gTemplate .= '-moz-border-radius-bottomright: 7px;';
					$this->gTemplate .= '-moz-border-radius-bottomleft: 7px;';
					$this->gTemplate .= 'border-bottom-right-radius: 7px;';
					$this->gTemplate .= 'border-bottom-left-radius: 7px;';
					$this->gTemplate .= '}';
					$this->gTemplate .= '.header{ background:#676767; padding:25px; font-size:26px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; color:#fff;}';
					$this->gTemplate .= '.content{ font-family:Tahoma, Geneva, sans-serif; font-size:14px; line-height:150%; }';
					$this->gTemplate .= '.subject{';
					$this->gTemplate .= 'font-family:Arial, Helvetica, sans-serif;';
					$this->gTemplate .= 'font-size:22px;';
					$this->gTemplate .= 'font-weight:bold;';
					$this->gTemplate .= 'color:#d9653b;';
					$this->gTemplate .= 'padding:10px;';
					$this->gTemplate .= '}';
					$this->gTemplate .= '.links{';
					$this->gTemplate .= 'padding:10px;';
					$this->gTemplate .= 'margin:10px;';
					$this->gTemplate .= 'border-top:1px solid #ccc;';
					$this->gTemplate .= 'font-family:Tahoma, Geneva, sans-serif;';
					$this->gTemplate .= '}';
					$this->gTemplate .= '.links a{ color:#72a6a6; font-weight:bold; padding:5px; font-size:12px; border-bottom:2px solid #fff; text-decoration:none;}';
					$this->gTemplate .= '.links a:hover{ border-bottom:2px solid #ccc;}';
					$this->gTemplate .= '</style>';
					$this->gTemplate .= '</head>';
					$this->gTemplate .= '<body>';
					$this->gTemplate .= '<div class="page">';
					$this->gTemplate .= '<div class="header">'. $setting->rows['title'] .'</div>';
					$this->gTemplate .= '<div class="content">';
					$this->gTemplate .= '<div class="subject">'.$this->Subject.'</div>';
					$this->gTemplate .= '<div style="padding:15px;">'.$this->Message.'</div>';
					$this->gTemplate .= '</div>';
					$this->gTemplate .= '<!--Links-->';
					$this->gTemplate .= '<div class="links">';
					$this->gTemplate .= '<a href="'._url('index').'">{indexPage}</a>';
					$this->gTemplate .= '<a href="'._url('accounts?act=signup').'">{signupPage}</a>';
					$this->gTemplate .= '<a href="'._url('contact').'">{contactPage}</a>';
					$this->gTemplate .= '</div>';
					$this->gTemplate .= '</div>';
					$this->gTemplate .= '</body>';
					$this->gTemplate .= '</html>';
					
				break;	
				
			}
			
			return $this->gTemplate;
			
		}
		
		public function getMessage(){
		
			return($this->Message);	
		}
	}
	
?>