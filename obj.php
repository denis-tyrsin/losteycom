<?
include_once('db_vars.php');

$obj = $_GET['obj'];
$content_list = "";
$cur_params = "";
$query_dop = "";
$param_id1 = "15";
$param_id0 = $page*15;
$query = "SELECT obj.*, tg.id_t1, u.* from Objects obj join Tags_Obj1 tg on (tg.id_obj=obj.id_obj and (tg.id_t1=4 or tg.id_t1=5 or tg.id_t1=6)) left join user u on (u.user_id=obj.id_user) where obj.id_obj='".$obj."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
if ($row = mysql_fetch_object($result)) {
    $type_k="";
    if ($row->id_t1==4) { $type_k="Lost"; }
    if ($row->id_t1==5) { $type_k="Found"; }
    if ($row->id_t1==6) { $type_k="Needs home"; }
    $subj=$row->subj;
    $text=$row->text;
    $addr=$row->map1;
    $obj_img="images/obj/".$row->id_p.".jpg";
}
//echo $query;
?>

				<div class="title-block">
					<div class="title">
<?
echo $subj;
?>                    </div>
					<div class="status">
<?
echo $type_k;
?>
                    </div>
					<div class="position">
<?
echo $addr;
?>
                    </div>
				</div>
				<ul class="social">
					<li>Share and help spread info </li>
					<li><a href="#"><img src="images/ico/ico1.png" alt=""></a></li>
					<li><a href="#"><img src="images/ico/ico2.png" alt=""></a></li>
					<li><a href="#"><img src="images/ico/ico3.png" alt=""></a></li>
					<li><a href="#"><img src="images/ico/ico4.png" alt=""></a></li>
				</ul>
				<div class="perconal-cont clear">
					<div class="gallery">
						<a href="#" class="fl-l">
                        <img src="
<?
echo $obj_img;
?>
                        " alt="">
                        </a>
						<div class="small-imgs">
<?
$query = "SELECT tg.id_p from Objects obj join Photos tg on (tg.id_obj=obj.id_obj) where obj.id_obj='".$obj."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_object($result)) {
    $obj_img="<a href=\"#\"><img src=\"images/obj/".$row->id_p.".jpg\" alt=\"\" width=\"100px\"></a>";
    echo $obj_img;
}
?>
						</div>
					</div>
					<div class="content">
						<p>
<?
echo $text;
?>
                        </p>
					</div>
				</div>
				<div class="coments-wrap clear">
					<div class="title">Comments</div>
<?
$query = "SELECT tg.*, usr.user_name from Objects obj join comments tg on (tg.id_obj=obj.id_obj) left join user usr on (obj.id_user=usr.user_id) where obj.id_obj='".$obj."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$obj_com="";
while ($row = mysql_fetch_object($result)) {
    $obj_com=$obj_com."<div class=\"comment\"><div class=\"info\"><img src=\"images/cat.png\" alt=\"\">4 Paws<br>".$row->user_name."</div><div class=\"text\"><p>".$row->text;
    $obj_com=$obj_com."</p><div class=\"time\">".$row->date;
    $obj_com=$obj_com."</div></div></div>";
}
echo $obj_com;
?>


				</div>
				<div class="other-wrap">
					<div class="title">Other losties</div>
					<div class="other-block">
						<img src="images/1.jpg" alt="">
						<a href="#">Twizzles<br> Merrifield, VA</a>
					</div>
					<div class="other-block">
						<img src="images/2.jpg" alt="">
						<a href="#">Twizzles<br> Merrifield, VA</a>
					</div>
					<div class="other-block">
						<img src="images/3.jpg" alt="">
						<a href="#">Twizzles<br> Merrifield, VA</a>
					</div>
				</div>
			</div>
