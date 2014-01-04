<?
include_once('db_vars.php');

$obj = $_GET['obj'];
$content_list = "";
$cur_params = "";
$query_dop = "";
$param_id1 = "15";
$param_id0 = $page*15;
$type_k="";
$oper="e";
$lat="";
$lng="";
$usrname="";
$usrid="";
$user_ido="-1";
$m="0";
$sum0 = 0;
$sum1 = 0;
$proc = 0;
$separ="&&&&***^^^";
$name_ln[4]="Lost";
$name_ln[5]="Found";
$name_ln[6]="Needs home";
$date1 = "";
$daysago="";

if ($_COOKIE['id_hash_id']) { $user_ido=$_COOKIE['id_hash_id']; }

if ($oper=="a") {$name_ln[4]="<U>".$name_ln[4]."</U>";}
$query = "SELECT obj.*, tg.id_t1, u.user_name from Objects obj join Tags_Obj1 tg on (tg.id_obj=obj.id_obj and (tg.id_t1=4 or tg.id_t1=5 or tg.id_t1=6)) left join user u on (u.user_id=obj.id_user) where obj.id_obj='".$obj."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
if ($row = mysql_fetch_object($result)) {
    //if ($row->id_t1==4) { $type_k="Lost"; $name_ln[4]="<U>".$name_ln[4]."</U>";}
    //if ($row->id_t1==5) { $type_k="Found";  $name_ln[5]="<U>".$name_ln[5]."</U>";}
    //if ($row->id_t1==6) { $type_k="Needs home";  $name_ln[6]="<U>".$name_ln[6]."</U>";}
	$type_k=$row->id_t1;
    $subj=$row->subj;
    $text=$row->text;
    $addr=$row->map1;
    $lat=$row->lat_adr;
    $lng=$row->long_adr;
    $obj_img="images/obj/".$row->id_p.".jpg";
	$usrname=$row->user_name;
	$usrid=$row->id_user;
		$sum0 = $row->sum;
		$sum1 = $row->sumcur;
	$date1=$row->date1;
	$published = strtotime($date1); //переводим в TIMESTAMP
	$today = time();
	$daysago=floor(($today - $published) / 86400);
}
$type_k="";
$query = "SELECT tg.id_t1 from Tags_Obj1 tg where tg.id_obj='".$obj."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_object($result)) {
	$type_k=$type_k.",".$row->id_t1;
}
if ($user_ido==$usrid) { $m="1"; }
if ($sum0 > 0) {
	$proc = floor(($sum1/$sum0)*100);
}
echo $subj;
echo $separ.$type_k;
echo $separ.$addr;
echo $separ.$obj_img;
echo $separ.$text;
echo $separ.$lat;
echo $separ.$lng;
echo $separ.$usrname;
echo $separ.$usrid;
echo $separ.$m;
echo $separ.$sum0;
echo $separ.$sum1;
echo $separ.$proc;
echo $separ.$date1;
echo $separ.$daysago;
$query = "SELECT tg.id_p from Objects obj join Photos tg on (tg.id_obj=obj.id_obj) where obj.id_obj='".$obj."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_object($result)) {
    $obj_img="images/obj/".$row->id_p.".jpg";
    echo $separ.$obj_img;
}
?>
