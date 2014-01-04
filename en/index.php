<?
include_once('../db_vars.php');
//header ("Location: $URL");

//$ref=$_SERVER['QUERY_STRING'];
//if ($ref!='') $ref='?'.$ref;

//echo $URL;

header('HTTP/1.1 301 Moved Permanently');
setcookie('lang', '1', (time()+2592000), '/', '', 0); 
header('Location: '.$URL);
exit();

?>
