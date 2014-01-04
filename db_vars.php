<?php 
    $db_host = '127.0.0.1'; 
    $db_user = 'root'; 
    $db_password = 'XcK1BjWjkC2vy9P7'; 
    $database = 'lostie'; 
    $URL="http://lostey.com/lostey";
    $lang = '1'; 

	$supersecret_hash_padding = 'oosioaudsio zxpokoiqw erywrxczz';

	global $supersecret_hash_padding;
    
    mysql_connect($db_host, $db_user, $db_password); 
    	mysql_query("SET NAMES utf8");
	mysql_query("SET character_set_server='utf8'");
	mysql_query("SET character_set_system='utf8'");
	mysql_query ("set character_set_client='utf8'"); 
	mysql_query ("set character_set_results='utf8'"); 
	mysql_query ("set collation_connection='utf8_general_ci'"); 
    mysql_select_db($database); 
  
?>