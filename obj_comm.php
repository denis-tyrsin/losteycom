<?
include_once('db_vars.php');
$obj = $_GET['obj'];
$content_list = "";
$cur_params = "";
$query_dop = "";
$query = "SELECT tg.*, usr.* from Objects obj join comments tg on (tg.id_obj=obj.id_obj) left join user usr on (tg.id_user=usr.user_id) where obj.id_obj='".$obj."' order by date desc";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$obj_com="";
while ($row = mysql_fetch_object($result)) {
    $obj_com=$obj_com."<div class=\"comment\"><div class=\"info\"><a href=\"?uname=".$row->first_name."\"><U><img src=\"images/users/s".$row->img.".jpg\" alt=\"\"><br>".$row->first_name."</U></a></div><div class=\"text\"><p>".$row->text;
    //$obj_com=$obj_com."<div class=\"comment\"><div class=\"info\"><a href=\"?uname=".$row->first_name."\"><U><img src=\"\" alt=\"\"><br>".$row->first_name."</U></a></div><div class=\"text\"><p>".$row->text;
    $obj_com=$obj_com."</p><div class=\"time\">".$row->date;
    $obj_com=$obj_com."</div></div></div>";
}
echo $obj_com;
?>