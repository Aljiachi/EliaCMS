<?php 

	if(script_run !== true){
		
			die('<h1 align="center">404 . الصفحة المطلوبة غير موجودة</h1>');
		}

	if(isset($_POST['addpost'])){
		
			$name = strip_tags(trim($_POST['name']));
			
			$mail = strip_tags(trim($_POST['mail']));
			
			$text = strip_tags($_POST['comment']);
			
			$active = 0;
			
			$ip   = $_SERVER['REMOTE_ADDR'];
			
			$time = time();
			
			if(!empty($name) and !empty($mail) and !empty($text)){
				
					$insert = $db->query("insert into module_guestbook values ('' , '$name' , '$mail' , '$ip' , '$active' , '$text' , '$time')");
					
					if($insert){
						
								print '<h3 style="color:green;" align="center">تم إضافة المشاركة بنجاح</h3>';
					
						}else{
						
								print mysql_error();
									
							}
				}
		}
?>


<form method="post">
    
<table width="100%" dir="rtl" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>الإسم</td>
    <td><input type="text" name="name" /></td>
  </tr>
  <tr>
    <td>البريد الإلكتروني</td>
    <td><input type="text" name="mail" /></td>
  </tr>
  <tr>
    <td>المشاركة</td>
    <td><textarea name="comment" cols="50" rows="5"></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="إرسال" name="addpost" /></td>
  </tr>
</table>

    </form>