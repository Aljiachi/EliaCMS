<?php
				
	ob_start();
	
	class Adinc{
		
			public $locationDelay = 1;
			public $plugins , $events;
			
			public function __construct(){

				// static defined
				define("ALLOW_ADMINPANEL" , "group_allow_adminpanel");
				define("MANGE_THEMES" , "group_ap_themes");
				define("MANGE_SETTING" , "group_ap_setting");
				define("MANGE_PLUGINS" , "group_ap_plugins");
				define("MANGE_MODULES" , "group_ap_modules");
				define("MANGE_LANGUAGES" , "group_ap_languages");
				define("MANGE_ACCOUNTS" , "group_ap_accounts");
				define("MANGE_MENUS" , "group_ap_menus");
				define("MANGE_PAGES" , "group_ap_pages");
				define("MANGE_SECURITY" , "group_ap_security");
				define("MANGE_GROUPS" , "group_ap_groups");
	
			}
			
			public function getStyle(){
				
				global $config , $db;
					
				print "<!DOCTYPE html>
						<html>
						<head>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<title>Elia-CMS || Control Panel</title>
						<link type=\"text/css\" rel=\"stylesheet\" href=\"style.css\" />
						<link rel=\"stylesheet\" href=\"js/base/jquery.ui.all.css\">
						<link rel=\"stylesheet\" href=\"js/editor/themes/default.min.css\" type=\"text/css\" media=\"all\" />
						<script type=\"text/javascript\" src=\"js/jquery.js\"></script>
						<script type=\"text/javascript\" src=\"js/ui.core.js\"></script>
						<script type=\"text/javascript\" src=\"js/ui.widget.js\"></script>
						<script type=\"text/javascript\" src=\"js/jquery.tooltipster.js\"></script>
						<script type=\"text/javascript\" src=\"js/editor/jquery.sceditor.bbcode.min.js\"></script>
						<script type=\"text/javascript\" src=\"js/functions.js\"></script>
						</head>
						<body>";

					if($_SESSION['admin_elia_notAdmin'] == "yes"){
						
						$setting = $db->getfastRows("select account_id,account_email,account_password,account_lastip from " . PFX . "accounts where 
  									  account_email='".base64_decode($_SESSION['admin_elia_username'])."' and account_password='".base64_decode($_SESSION['admin_elia_password'])."' and account_groupid='3' limit 1");
						
						$loggedUsername = $setting['account_email'];
						$loggedPassword = $setting['account_password'];
						$loggedIp 		= $setting['account_lastip'];
						
					}else{
					
						$setting = $db->getfastRows("select username , password , ip from " . PFX . "core");
						$loggedUsername = $setting['username'];
						$loggedPassword = $setting['password'];
						$loggedIp		= $setting['ip'];
						
					}
											
					if(!isset($_SESSION['admin_elia_username']) and base64_encode($loggedUsername) !== $_SESSION['admin_elia_username']){
					
						session_destroy();
						
						header("location: login.php");	
						
						exit;
					
					}

					if($_SERVER['REMOTE_ADDR'] !== $loggedIp){
						
						session_destroy();
							
						header("location: logout.php");	
							
						exit;
						
					}

					if(!isset($_SESSION['admin_elia_password']) and base64_encode($loggedPassword) !== $_SESSION['admin_elia_password']){
						
						session_destroy();
						
						header("location: login.php");	
					
						exit;
					}
		
				}
				
	
			
			public function greenAlert($alert){
				
					echo "\n<div class=\"green\">$alert</div>\n";
				}

			public function redAlert($alert){
				
					echo "\n<div class=\"red\">$alert</div>\n";
				}

			public function closePage(){
				
					print "<div class=\"systemAlert\">جاري المعالجة .. يرجى الإنتظار <img src=\"images/progs.gif\" /></div>
						</div>
						<div style=\"clear:both;\"></div>
						<div class=\"bottom\">
						<div class=\"link\"><a href=\"index.php\">".base64_decode($_SESSION['admin_elia_username'])."</a></div> |
						<div class=\"link\"><a rel=\"js\" href=\"index.php\">رئيسية لوحة التحكم</a></div> | 
						<div class=\"link\"><a href=\"../index.php\" target=\"_new\">رئيسية الموقع</a></div> | 
						<div class=\"link\"><a href=\"http://www.elia.net\" target=\"_new\">Elia CMS</a></div> | 
						<div class=\"link\"><a onclick=\"if(window.confirm(\'هل أنت متأكد من العملية\') == false){ return false; }\" href=\"logout.php\">تسجيل الخروج</a></div>
						</div>
						</body>
						</html>";
				}
				
			public function getRight($page){

					include("adinc/class.NotificationManager.php");
					
					$NotificationManager = new NotificationManager();
									
					$notifyContext = $NotificationManager->getNotify();
					
					if(!empty($notifyContext)){
							
						$notifysManager = explode("," , $notifyContext);
						
					}else{
						
						$notifysManager=array();
					}
					
					if(count($notifysManager)!==0){
						
					print '<div>
					
						<div class="notify_icon"><img src="icons/notification.png" width="24" class="notify_click" />
						<div class="notify_num">'.(count($notifysManager)-1).'</div>
						</div>';

					print '<div class="notify_body">';
					
						foreach($notifysManager as $var){$a++;
						
							if($a !== end($notifysManager)){ 
								
								$var = explode("%end&" , $var);
	
								  if(!empty($var[0]) and !empty($var[1])){
									  
										print '<div>';
																		
											print '<div class="notify_object">' . $var[0] . '</div>';
											print '<div class="notify_vars">' . $var[1] . '</div>';								
								
										print '</div>';
	
								  }
							}
						}
											
					print '</div></div>';
					
					}

					print '
					<div class="right">
						<div class="links">
							<a rel="js" href="themes.php"';if($page == "themes"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>الستايلات و القوالب</a>
							<a rel="js" href="pages.php?action=control"';if($page == "pages"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>الصفحات الإضافية</a>
							<a rel="js" href="modules.php?action=control"';if($page == "mods"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>البرامج الإضافية</a>
							<a rel="js" href="groups.php"';if($page == "groups"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>المجموعات</a>
							<a rel="js" href="accounts.php"';if($page == "accounts"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>الحسابات</a>
							<a rel="js" href="setting.php"';if($page == "setting"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>الإعدادات</a>
							<a rel="js" href="security.php"';if($page == "security"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>الحماية</a>
							<a rel="js" href="plugins.php?action=control"';if($page == "plugins"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>الهاكات</a>
							<a rel="js" href="blocks.php?action=control"';if($page == "blocks"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>القوائم</a>
							<a rel="js" href="languages.php"';if($page == "languages"){ print ' class="selected"'; }else{ print ' class="unSelected"'; } print '>اللغات</a>
							<a onclick="if(window.confirm(\'هل أنت متأكد العملية\') == false){return false;}" class="unSelected" href="logout.php">خروج</a>
						</div>
					</div>' . "\n";
					print '
					<div class="center">

					' . "\n";
					
				}
			public function getMenu($title , $content){
				
					print "<div class=\"menu_title\">$title</div>\n";
					print "<div class=\"menu_body\">$content</div>\n";
					
				}
				
			public function getNavbar($links){

					print '<div id="navbar">' . "\n";
					
						foreach($links as $title => $link){
							
								print "\t" . '<a href="'.$link.'">' . $title . '</a>' . "\n";
							}
							
					print '</div>' . "\n";
					
							
				}
		
				
			public function getExtraLinks($links){
				
					print '<div class="extra_links">' . "\n";
					
						foreach($links as $title => $link){
							
								print '<a href="'.$link.'">' . $title . '</a>' . "\n";
							}
							
					print '</div>' . "\n";
					
							
				}
		
		   public function location($location){
			   
			   	print '<meta http-equiv="refresh" content="'.$this->locationDelay.';URL='.$location.'" />';
			   }
			   
			public function getJs($file){
				
					print '<script type="text/javascript" src="js/'.$file.'.js"></script>' . "\n";
				}
				
			public function stop(){
				
				return(exit);	
			}

			public function _lang($word_name){
					
				global $db;
		
				$core_setting = $db->getfastRows("select admin_language from " . PFX . "core");
				$lang_id = $core_setting['admin_language'];
				$getDefaultLangCode = $db->getFastRows("select * from " . PFX . "languages where id='$lang_id'");
				$default = $getDefaultLangCode['language_code'];
	
				$getWord = $db->query("select word_value from language_words 
												where  word_name='$word_name' and word_type ='1' and language_code='$default'
												limit 1");
					
				$rows= $db->rows($getWord);
				
				return($rows['word_value']);	
			}
			
			function _debug($message , $color , $request=''){
				
					global $db, $config;
					
					$time = time();
					$ip = $_SERVER['REMOTE_ADDR'];
					if(empty($request)) $request = $_SERVER['REQUEST_URI'];
					
					if($db->getfastTotal("select id from " . PFX . "debug where time=$time and message='$message' and color='$color' and request='$request' and ip='$ip'") == 0){
						
						$insert = $db->query("insert into debug values('' , '$time' , '$message' , '$color' , '$request' , '$ip' , '2')");	
				
					}
				}
				
		  public function _checkSession(){
			  		
				global $db;
																				
				if($_SESSION['admin_elia_notAdmin'] == "yes"){
						
					$setting = $db->getfastRows("select account_id,account_email,account_password,account_lastip from " . PFX . "accounts where 
  									  account_email='".base64_decode($_SESSION['admin_elia_username'])."' and account_password='".base64_decode($_SESSION['admin_elia_password'])."' and account_groupid='3' limit 1");
						
					$loggedUsername = $setting['account_email'];
					$loggedPassword = $setting['account_password'];
					$loggedIp 		= $setting['account_lastip'];
						
				}else{
					
					$setting = $db->getfastRows("select username , password , ip from " . PFX . "core");
					$loggedUsername = $setting['username'];
					$loggedPassword = $setting['password'];
					$loggedIp		= $setting['ip'];
						
				}
																				
				if(!isset($_SESSION['admin_elia_username']) and base64_encode($loggedUsername) !== $_SESSION['admin_elia_username']){
					
					session_destroy();
						
					header("location: login.php");	
						
					exit;
					
				}
						
				if($_SERVER['REMOTE_ADDR'] !== $loggedIp){
						
					session_destroy();
							
					header("location: login.php");	
							
					exit;
						
				}

				if(!isset($_SESSION['admin_elia_password']) and base64_encode($loggedPassword) !== $_SESSION['admin_elia_password']){
						
					session_destroy();
					
					header("location: login.php");	
					
					exit;
				}
		
		  }
		  
		  public function jsLanguage($params=array()){
			
			if(count($params) !== 0){
			
				$RETURN = "<script type=\"text/javascript\">\n";
				
				foreach($params as $key => $var){
				
					$RETURN .= "\t var LANGUAGE_$key = '$var';\n";	
				}
				
				$RETURN .= "</script>\n";
			}
			
			echo($RETURN);
		  }
		  
		  public function getSession($session_name){
			
			return $_SESSION[$session_name];  
		  }
		  
		}

	$adinc = new Adinc();
	
?>
