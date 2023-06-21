<?php
	
	$salt = "NDYxMjA";
	$pass = '123';
	
	echo md5($pass . md5($salt));
	
?>