<?
include_once('db_vars.php');
require_once('registration/checkuser.php');
include('functions_pr.php');

$date_get = "";
$uname_get = "";
$loc_get = "";
$lat_get = "";
$long_get = "";
$id_get = "";
$mob_get = "";
$mob_get = $_GET['m'];
if ($mob_get!="") {
    setcookie('mob', $mob_get, (time()+2592000), '/', '', 0);
} else {
	if ($_COOKIE['mob']) {
		$mob_get = $_COOKIE['mob'];
	}    
}
$id_get = $_GET['n'];
if ($id_get!="") {
    $query = "SELECT * from Objects where id_obj='".$id_get."'";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    if ($row = mysql_fetch_object($result)) {
        $subj_get=$row->subj;
        $text_get=$row->text;
        $sum_get=$row->sum;
        $img_get="images/obj/".$row->id_p.".jpg";
    }
}
$date_get = $_GET['date'];
$uname_get = $_GET['uname'];
$encode = detect_encoding($uname_get);
if ($encode == "cp1251") {
    $uname_get = iconv("cp1251", "utf-8", $uname_get);
}
$loc_get = $_GET['loc'];
$encode = detect_encoding($loc_get);
if ($encode == "cp1251") {
    $loc_get = iconv("cp1251", "utf-8", $loc_get);
}
$lat_get = $_GET['lat'];
$long_get = $_GET['long'];

if ($uname_get!="") { 
    if ($uname_get=="no") { $uname_get=""; setcookie('uname', '', (time()+2592000), '/', '', 0); } else {
        setcookie('uname', $uname_get, (time()+2592000), '/', '', 0);
    }
} else {
	if ($_COOKIE['uname']) {
		$uname_get = $_COOKIE['uname'];
	}
}
if ($date_get!="") { 
    if ($date_get=="no") { $date_get=""; setcookie('date', '', (time()+2592000), '/', '', 0); } else {
        setcookie('date', $date_get, (time()+2592000), '/', '', 0);
    }
} else {
	if ($_COOKIE['date']) {
		$date_get = $_COOKIE['date'];
	}
}
if ($loc_get!="") { 
    if ($loc_get=="no") { 
        $loc_get="";
        $lat_get="";
        $long_get="";
        setcookie('loc', '', (time()+2592000), '/', '', 0);
        setcookie('lat', '', (time()+2592000), '/', '', 0);
        setcookie('long', '', (time()+2592000), '/', '', 0);
    } else {
        setcookie('loc', $loc_get, (time()+2592000), '/', '', 0);
        setcookie('lat', $lat_get, (time()+2592000), '/', '', 0);
        setcookie('long', $long_get, (time()+2592000), '/', '', 0);
    }
} else {
	if ($_COOKIE['loc']) {
		$loc_get = $_COOKIE['loc'];
	}
	if ($_COOKIE['lat']) {
		$lat_get = $_COOKIE['lat'];
	}
	if ($_COOKIE['long']) {
		$long_get = $_COOKIE['long'];
	}
}
$status_filtr=$date_get.$uname_get.$loc_get;
if ($_COOKIE['lang']) { 
    $lang = $_COOKIE['lang'];
} else {
    setcookie('lang', '1', (time()+2592000), '/', '', 0);
}

//$query = "UPDATE Names_ln SET name='выбрать место' WHERE id_ns=44";
//$result = mysql_query($query) or die('Query failed: ' . mysql_error());

$itag=0;
$query = "SELECT * from Names_ln where id_ln='".$lang."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_object($result)) {
    $name_ln[$row->id_s]=$row->name;
}
$query = "SELECT * from Names_ln where id_ln='".$lang."' and type>0 order by type";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_object($result)) {
		$itag=$itag+1;
    		$name_tag[$itag][1]=$row->name;
    		$name_tag[$itag][2]=$row->id_s;
    		$name_tag[$itag][3]=$row->type;
}

$find_str = $_GET['find'];
$where_str2="";
if ($find_str=="no") {
        setcookie('find', '', (time()+2592000), '/', '', 0);
        setcookie('findstr', '', (time()+2592000), '/', '', 0);
    $find_str="";
} else {
 if ($find_str!="") {
    //if ($find_str!="") {
	$find_str=$find_str."";
	$find_str0=$find_str.",";
	$where_str="";
	$find_str_arr = explode(",", $find_str0);
	for ($i=-1; $i++<count($find_str_arr);) {
		$find_str_arr2[$i]=$find_str_arr[$i];
		$fstr=trim($find_str_arr[$i]);
		if ($fstr!="") {
			for ($j=0; $j++<$itag;) {
				$ntag=trim($name_tag[$j][1]);
				if ($ntag==$fstr) {
					$find_id_arr[$i]=$name_tag[$j][2];
					$find_tp_arr[$i]=$name_tag[$j][3];
				}
			}
			if ($where_str=="") {
				$where_str="obj.id_obj in (SELECT id_obj FROM Tags_Obj1 where id_t1=".$find_id_arr[$i].")";
			} else {
				$where_str=$where_str." and obj.id_obj in (SELECT id_obj FROM Tags_Obj1 where id_t1=".$find_id_arr[$i].")";
			}
		}
	}
	for ($i=-1; $i++<count($find_str_arr);) {
	   if ($find_str_arr2[$i]!="") {
		if ($where_str2=="") {
            if ($find_id_arr[$i]!='') {
                $where_str2="(obj.id_obj in (SELECT id_obj FROM Tags_Obj1 where id_t1=".$find_id_arr[$i];
            } else {
                 $where_str2="(obj.text like '%".trim($find_str_arr2[$i])."%' or obj.subj like '%".trim($find_str_arr2[$i])."%')";
            }
		} else {
            if ($find_id_arr[$i]!='') {
                $where_str2=$where_str2." and (obj.id_obj in (SELECT id_obj FROM Tags_Obj1 where id_t1=".$find_id_arr[$i];
            } else {
                $where_str2=$where_str2." and (obj.text like '%".trim($find_str_arr2[$i])."%' or obj.subj like '%".trim($find_str_arr2[$i])."%')";
            }
		}
        if ($find_id_arr[$i]!='') {
		 for ($j=-1; $j++<count($find_str_arr);) {
			if ($find_tp_arr[$i]==$find_tp_arr[$j] && $i!=$j) {
				$where_str2=$where_str2." or tg.id_t1=".$find_id_arr[$j];
				$find_str_arr2[$j]="";			
			}
		 }
		 $where_str2=$where_str2."))";
        }
	   }
	}
    setcookie('find', $where_str2, (time()+2592000), '/', '', 0);
    setcookie('findstr', $find_str, (time()+2592000), '/', '', 0);
	//echo trim($where_str2);
 } else {
	if ($_COOKIE['find'] && $_COOKIE['findstr']) {
		$where_str2 = $_COOKIE['find'];
        $find_str = $_COOKIE['findstr'];
	}
 }
}


$lang_ln="";
$query = "SELECT * from Lang where id_ln='".$lang."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
if ($row = mysql_fetch_object($result)) {
    $lang_ln=$row->nm;
}
$day1 = $name_ln[49]; //$_GET['day1'];
$day2 = $name_ln[50]; //$_GET['day2'];
$day3 = $name_ln[51]; //$_GET['day3'];
$day4 = $name_ln[52]; //$_GET['day4'];
$daysago = "";
if ($date_get!="") {
            $published = strtotime($date_get); //переводим в TIMESTAMP
            $today = time();
            $daysago=floor(($today - $published) / 86400);
            if ($daysago<1) { $daysago=$day4; }
            if ($daysago==1) { $daysago=$daysago." ".$day1; }
            if ($daysago>1) { $daysago=$daysago." ".$day2; }
}

$user_name = "";
$user_id = "";
$first_name = "";
        If (user_isloggedin()) {
            $user_name =$_COOKIE['user_name'];
            $user_id =$_COOKIE['id_hash_id'];
            $avatar_img =$_COOKIE['img'];
	    $first_name =$_COOKIE['first_name'];
	    if ($first_name=="") { $first_name=$user_name; }
        }
?>
<!DOCTYPE html>
<head>
<title><? if ($subj_get!="") { echo $subj_get; } else { echo "Lostey"; } ?></title>
<meta http-equiv="content-type" content=text/html; charset="utf-8">
<?
$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
if ($isiPad) {} else { 
if ($mob_get=="1") {
    //<meta name="viewport" content="width=device-width; initial-scale=0.55; maximum-scale=0.55; user-scalable=0;" />

if ($id_get!="") {
    ?>
    <meta name="viewport" content="width=device-width; initial-scale=1.1; maximum-scale=1.1; user-scalable=0;" />
    <?
} else {
    ?>
    <meta name="viewport" content="width=device-width; initial-scale=1.1; maximum-scale=1.1; user-scalable=0;" />
    <?
}
    
}
}
?>
<link rel="shortcut icon" href="../images/ico/corner.gif" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" href="css/styless.css"/><!-- media="screen, projection" -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.isotope.js"></script>
<script type="text/javascript" src="js/jquery.sticky.js"></script>
<script type="text/javascript" src="js/jquery.functions_pr.js"></script>
<script type="text/javascript" src="js/jquery.functions_objsw12.js"></script>
<script type="text/javascript" src="js/jquery.functions_mrks1.js"></script>
<script type="text/javascript" src="js/jquery.functions_inits29.js"></script>
<script type="text/javascript" src="js/jquery.functions_clicks1.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.5.js" ></script>
<script src="http://api-maps.yandex.ru/2.0/?load=package.standard&lang=<? echo $lang_ln; ?>-RU" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=<? echo $lang_ln; ?>"></script>

<script>
//alert ($(window).width());
<?
echo "var url_st='".$URL."'; ";
if ($isiPad) { 
?>
    //alert ('<? echo $isiPad; ?>');
<?
} else {
if ($mob_get!="1") {
    ?>
    var wdt=$(window).width();
    if (wdt<1000) {
        //alert (wdt);
        location=url_st+'/?m=1';
        //location.replace(url+'/?m=1');
    }
    <?
}
}
?>
</script>


</head>

<style type="text/css">

   .layer11 {
    background-color: #fc0; /* Цвет фона слоя */
    padding: 10px; /* Поля вокруг текста */
    float: left; /* Обтекание по правому краю */
    //width: 430px; /* Ширина слоя */
   }
   .layer12 {
    padding: 5px; /* Поля вокруг текста */
    float: left; /* Обтекание по правому краю */
   }
   .layer_srch {
    padding: 0px; /* Поля вокруг текста */
    float: left; /* Обтекание по правому краю */
    width:35%;
    text-align:center;
    min-width:260px;
   }
   .layer_auth {
    padding: 2px; /* Поля вокруг текста */
    float: left; /* Обтекание по правому краю */
    width:33%;
    text-align:center;
    min-width:270px;
   }

   #layer1 {width: 100px; height: 100px; position: absolute; left: 0px; top: 0px; display: none; z-index: 115;}

</style>

<body>

<div id="wrapper" style="width:100%">
<!-- Header -->
<div id="header" style="width:100%">





<div id="slogan0id">

<table width="100%">
<tr>
<td width="1%" valign="top">
</br>
<?
if ($lang == "1") {
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="ru/">[<b>RUS</b>]</a>
<?
} else {
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="en/">[<b>ENG</b>]</a>
<?
}
?>
</td>
<td width="99%" valign="top">
<div class="wrap" id="sloganid" style="align: center">
<?
if ($mob_get!="1") {
    ?>
<div class="slogan-wrap" style="width:100%">
<div class="slogan"><? echo $name_ln[9]; ?><span><b>Lostey</b></span><? echo $name_ln[10]; ?><br>
<? echo $name_ln[11]; ?></div>
</div>
    <?
}
?>
</div>
</td>
</tr>
</table>

<?
if ($mob_get=="1") {
    ?>
<table width="140px">
<tr>
<td width="40px">
</td>
<td width="100px" align="left">
<a class="logo" href="index.php" id="btnlost"><img src="images/bar/lost.png" alt=""></a>
</td>
</tr>
</table>
<table width="100%">
    <tr>
    <td width="40px">
    </td>
    <td align="left">
    <? echo $name_ln[9]; ?><span><b>Lostey</b></span><? echo $name_ln[10]; ?><br>
    <? echo $name_ln[11]; ?>
    </td>
    </tr>
</table>
    <?
}
?>

<?
if ($mob_get!="1") {
?>
    </div>
    <div class="auth-wrap">
<?
}
?>


<div class="layer_srch">

<div id="layer1">
<img src="images/ajax-loader6.gif" alt="">
</div> 

<?
if ($mob_get!="1") {
?>
<table width="270px">
<tr>
<td width="170px">
<a class="logo" href="index.php" id="btnlost"><img src="images/bar/lost.png" alt=""></a>
</td>
<td width="100px" align="right">
<ul class="nav">
<li><a href="#"><? echo $name_ln[19]; ?></a></li>
<li><a href="#"><? echo $name_ln[18]; ?></a></li>
</ul>
</td>
</tr>
</table>

<table width="270px">
<tr>
<td width="50px">
<img src="images/pin.gif" alt="">
</td>
<td width="220px">
<div class="search-wrap">
</div>
<div class="search" style="align: left">
<input style="width:210px;" type="text" id="placeID" placeholder="<? echo $name_ln[22]; ?>" vertical-align="bottom" name="inputPlace">
</div>
</td>
</tr>
</table>
<?
}
?>

</div>

<?
if ($mob_get=="1") {
    ?>
    <table width="270px">
    <tr>
    <td width="50px">
    <img src="images/pin.gif" alt="">
    </td>
    <td width="220px">
    <div class="search-wrap">
    </div>
    <div class="search" style="align: left">
    <input style="width:210px;" type="text" id="placeID" placeholder="<? echo $name_ln[22]; ?>" vertical-align="bottom" name="inputPlace">
    </div>
    </td>
    </tr>
    </table>
    </div>
    <?
}
?>




<div class="layer_auth" id="layer_auth" style="<? if ($mob_get=="12") { echo "display: none;"; } ?>">

<div class="sign-in">

<div class="fb-wrap" style="display: none;">
</div>

<a class="email" href="registration/?o=new"><? if ($first_name == "") { echo $name_ln[13]; } else { echo $name_ln[60]; } ?></a>

</div>
<?
if ($first_name == "") {
?>
<div style="width:100%;" class="log clear"><? echo $name_ln[20]; ?> &nbsp;<a href="registration/?o=login"><? echo $name_ln[21]; ?></a></div>
<?
} else {
?>
<a href="?uname=<? echo $first_name; ?>"><div style="width:100%;" class="log clear"><U><? echo $first_name; ?></U>&nbsp;<a href="registration/?o=logout"><? echo $name_ln[45]; ?></a></div>
</a>
<?
}
?>
</div>

    <div class="search-wrap" style="width:270px; <? if ($mob_get=="1") { echo "display: none;"; } ?>">

<div class="search">
<input id="findid" style="width:180px;" type="text" placeholder="<? echo $name_ln[17]; ?>" value="<? echo $find_str; ?>">
<a class="find-btn" href="#" id="findbtn"></a>
</div>
</div>

<div class="search-wrap" id="selectfid" style="display: none;">
<table cellspacing="5" border="1" bgcolor="#FF6666">
<tr><td>
<div class="status" id="selectfid1" align="left" style="width: 100%;">
<?
$curtype=$name_tag[1][3];
$j=0;
for ($i = 1; $i <= $itag; $i++) {
    $j=$j+1;
    if ($curtype == $name_tag[$i][3] && $j<4) { 
    } else {
        echo "</div></td></tr><tr><td><div class=\"status\" id=\"selectfid".$i."\" align=\"left\" style=\"width: 100%;\">";
        $j=1;
        $curtype = $name_tag[$i][3];
    }
    ?>
    <a href="javascript:void(0)" onclick="selTagF(<? echo $i-1; ?>)" class="log clear" id="f<? echo $name_tag[$i][2]; ?>" onMouseOver="upm(this)" onMouseOut="downm(this)" id="selectftxtid<? echo $i; ?>">
    <b><? echo $name_tag[$i][1]; ?></b>
    </a>&nbsp;
    <?
    
}
?>
</div>
</td></tr>
</table>

</div>

<div class="clear">

<?
if ($status_filtr!="") {
    echo "<div class=\"description\">";
    //echo "<div>[ X Filter]</div>";
    if ($date_get!="") { echo "<div class=\"time\"><a href=\"?date=no\">Х <U>".$daysago."</U></a></div>"; }
    if ($uname_get!="") { echo "<div class=\"time\"><a href=\"?uname=no\">Х <U>".$uname_get."</U></a></div>"; }
    if ($loc_get!="") { echo "<div class=\"time\"><a href=\"?loc=no\">Х <U>".$loc_get."</U></a></div>"; }
    echo "</div>";
}
?>
</div>


<?
if ($mob_get=="1") {
    ?>
    <div class="auth-wrap" style="min-width:280px">
    <?
}
?>


<div class="tabs">
<a href="#" data-tab-selector="#tab_list" id="btnlst"><? echo $name_ln[14]; ?></a>
<a href="#" data-tab-selector="#tab_map2" id="btnmap"><? echo $name_ln[15]; ?></a>
    <?
    if ($mob_get!="1") {
        ?>
<a href="#" data-tab-selector="#tab_add" id="btnadd2">+ <? echo $name_ln[16]; ?></a>
        <?
    }
    ?>
</div>

    <?
    if ($mob_get!="1") {
        ?>
<div class="tabs2">
<a href="#" data-tab-selector="#tab_add" id="btnadd">+ <? echo $name_ln[16]; ?></a>
</div>
        <?
    }
    ?>

</div>
<div class="clear">
</div>







</div>
<!-- Header #END /-->


<!-- content -->
<div id="content" style="width:100%;">
<div id="tab_list" class="super-list variable-sizes clearfix" style="width:100%">


<?
$content_list = "";
$cur_params = "";
$query_dop = "";
$param_id1 = "15";     
?>

<script type="text/javascript">

var markers_obj = [];
var map;
var long2;
var lat2;
var input_temp;
var cur_win=1;
var change_city=1;
var target_top=0;
var map_add;
var marker;
var cur_addr='';
var geocoder;
var status_str='lost';
var strtg='';
var flagtag=0;
var cur_id='';
var oper_obj='n';
var mapimg=0;
var new_sum=100;
var cur_sum=0;
var proc=0;
var my_obj='0';
var filter2_str = '';
var fl_resize=0;
var mob_get=0;
maxnumPages = 100;
animpixels = 30;
numPages = 1;
nextPage= 1;
var kf=0;
var kfstart=1;
var kf_sel='';
var namef=[];
new_lat='';
new_lng='';
cur_obj_id='';
<?

$tag1 = "";
$tag2 = "";
$tag3 = "";
$tag4 = "";
for ($i = 1; $i <= $itag; $i++) {
	if ($tag1 == "") { } else { $tag1 = $tag1.","; }
	if ($tag2 == "") { } else { $tag2 = $tag2.","; }
	if ($tag3 == "") { } else { $tag3 = $tag3.","; }
	if ($tag4 == "") { } else { $tag4 = $tag4.","; }
	$tag1 = $tag1."'".$name_tag[$i][1]."'";
	$tag2 = $tag2."'".$name_tag[$i][2]."'";
	$tag3 = $tag3."'".$name_tag[$i][3]."'";
	$tag4 = $tag4."0";
}
echo "var tags1 = [".$tag1."]; ";
echo "var tags2 = [".$tag2."]; ";
echo "var tags3 = [".$tag3."]; ";
echo "var tags4 = [".$tag4."]; ";
echo "var tags = ".$itag."; ";
echo "var cur_user_name = '".$user_name."'; ";
echo "var cur_user_id = '".$user_id."'; ";

echo "var filter_str = '&date=".$date_get."&uname=".$uname_get."&loc=".$loc_get."&lat2=".$lat_get."&long2=".$long_get."'; ";
echo "var filter_lat = '".$lat_get."'; ";
echo "var filter_long = '".$long_get."'; ";
echo "var url='".$URL."'; ";
echo "var where_find=\"".$where_str2."\"; ";
echo "var id_get=\"".$id_get."\"; ";
echo "var name_get52=\"".$name_ln[52]."\"; ";
echo "var name_get49=\"".$name_ln[49]."\"; ";
echo "var name_get50=\"".$name_ln[50]."\"; ";
echo "var name_get47=\"".$name_ln[47]."\"; ";
echo "var first_name=\"".$first_name."\"; ";
echo "mob_get1=\"".$mob_get."\"; ";
?>

$(document).ready(function(){
                  
                  $("#findid").focus(function(){
						findid_focus();
                                      });
                  $("#findid").blur(function(){
                                      });
                  $("#addrID").focus(function(){
						addrID_focus();
                                      });
                  $("#text_add").focus(function(){
                                      });
                  $("#titleID").focus(function(){
                                      });
                  $("#placeID").focus(function(){
						placeID_focus();
                                      });
                  $("#findbtn").click(function(){
						findbtn_click();
                                     });
                  $("#btnadd").click(function(){
						document.getElementById('slogan0id').style.display = 'none';
						document.getElementById('commentaddid').style.display = 'none';
           					document.getElementById('commentid').innerHTML='';
						cur_obj_id='';
						btnadd_click();
                                     });
                  $("#mapimg").click(function(){
						mapimg_click();
                                     });
                  $("#btnsave").click(function(){
						btnsave_click();
                                     });
                  $("#btncomm").click(function(){
						save_comment();
                                      $('#comm_add').text('');
                                      //$('#text_add').text('');
                                     });
                  $("#btnmap").click(function(){
                        <?
                        if ($mob_get=="1") {
                        ?>
                        document.getElementById('layer_auth').style.display = 'none';
                        <?
                        }
                        ?>
						document.getElementById('slogan0id').style.display = 'none';
						heighthd = $('#header').height();
						heightsl = $('#slogan0id').height();
						heightlst = $(window).height()-heighthd; //+heightsl;
						if (heightlst>0) {document.getElementById('tab_map2').style.height=heightlst+"px";} //
						btnmap_click();
                                     });
                  $("#btnlst").click(function(){
                        document.getElementById('layer_auth').style.display = 'block';
						document.getElementById('slogan0id').style.display = 'block';
						btnlst_click();
                                     });                  
                  $("#btnlost").click(function(){
						btnlost_click();
                                     });    
});

$(window).on('resize', function(){
	fl_resize=1;
});

$('#tab_list').isotope({
                       itemSelector : '.animal-block',
                       layoutMode : 'masonry'
});

  $(function() {
    var btnUpload=$('#upload');
    var status=$('#status');
    new AjaxUpload(btnUpload, {
                 action: 'upload-file.php',
                 name: 'uploadfile',
                 onSubmit: function(file, ext){
		 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                 	status.text('Only JPG, PNG or GIF files are allowed');
                 	return false;
                 }
                 status.text(' Uploading...');
                 },
                 onComplete: function(file, response){
                 status.text('');
                 kf=kf+1;
                 namef[kf]=file;
		 kf_sel=file;
                 if(response.substring(0,7)==="success"){
			var img = document.getElementById('bigimg');  
         		img.src='uploads/'+file;
                 	$('#smallimg').append('<a onClick="javascript:sel_tr(\''+file+'\')"><img src="uploads/'+file+'" alt="" width="100px"></a>');
                 } else{
                 	status.text('Error!');
                 }
                 
                 }
  });

  var $container = $('#tab_list');
  $(window).scroll(function(){
                   if (cur_win==1) {
                        target_top=(window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
			if (target_top==0) {
				var html = document.body;
				target_top=html.scrollTop;
			} 
                   }
                   if ($(window).scrollTop() - ($(document).height() - $(window).height())+50>=0 && nextPage<=numPages && cur_win==1){
                   show_progress();
                   upd();
                   $('#tab_list')
                   .toggleClass('variable-sizes')
                   .isotope('reLayout');
                   }
  });
  
  $('#tab_list').isotope({
                         itemSelector : '.animal-block',
                         layoutMode : 'masonry'
                         });  
  $(".auth-wrap").sticky({ topSpacing: 0 });
  $('.tabs').children().click(function(){
                              var id = $(this).data('tab-selector');
                              if (id){
                              $(this).siblings().each(function(){
                                                      var id = $(this).data('tab-selector');
                                                      $(this).removeClass('active');
                                                      $(id).hide();
                                                      });
                              $(this).addClass('active');
                              $(id).fadeIn(400);
                              }
                              return false;
                              }).first().trigger('click');
  });

ymaps.ready(init);

google.maps.event.addDomListener(window, 'load', initialize);

</script>

</div>

<div id="tab_map2" style="width: 100%; height: 600px; display: none;">
</div>

<div id="personal-tab" style="display: none;">
<div class="title-block">
<div class="title">
<? echo $name_ln[46]; ?>
</div>
</div>
</div>



<div id="personaladd-tab" style="display:none; margin:<? if ($mob_get!="1") { echo "20px"; } else { echo "5px"; } ?>;">



<div>

<div class="title">
&nbsp;<div class="time" id="daysid">2013-11-11</div>
<span id="titleIDdiv">
</span>

<div class="position">
<table width="<? if ($mob_get!="1") { echo "450px"; } else { echo "250px"; } ?>">
<tr>
<td width="<? if ($mob_get!="1") { echo "425px"; } else { echo "250px"; } ?>">
<div class="search-wrap">
&nbsp;
</div>

<div class="search" id="titleIDinput">
<input style="width:<? if ($mob_get!="1") { echo "425px"; } else { echo "250px"; } ?>;" type="text" id="titleID" placeholder="<? echo $name_ln[54]; ?>" vertical-align="bottom" name="inputTitle">
</div>
</td>
</tr>
</table>
</div>
</div>



<table width="<? if ($mob_get!="1") { echo "450px"; } else { echo "280px"; } ?>"  cellspacing="5">
<tr><td>
<?
$curtype=$name_tag[1][3];
for ($i = 1; $i <= $itag; $i++) {

if ($curtype == $name_tag[$i][3]) { 
} else {
	echo "</td></tr><tr><td>";
	$curtype = $name_tag[$i][3];
}
?>
<div class="status" id="s<? echo $name_tag[$i][2]; ?>">
<a href="javascript:void(0)" onclick="selTag(<? echo $i-1; ?>)" class="log clear" id="t<? echo $name_tag[$i][2]; ?>">
<? echo $name_tag[$i][1]; ?></a>
</div>
<?
if ($name_tag[$i][2]==8) {
?>
<div id="titleIDsum" style="display: none;">
<table width="<? if ($mob_get!="1") { echo "200px"; } else { echo "140px"; } ?>"  cellspacing="5">
<tr><td>
<? echo $name_ln[47]; ?>
</td>
<td>
<div class="search" id="titleIDsum0">
<input style="width:<? if ($mob_get!="1") { echo "70px"; } else { echo "40px"; } ?>;" maxlength="5" type="text" id="sumID" placeholder="summ" vertical-align="bottom" name="inputSum">
</div>
</td></tr>
<tr><td>
<? echo $name_ln[48]; ?>
</td>
<td>

<div class="data">
<div class="fl-l" id="cursumid">$100 USD</div>
<div class="fl-r" id="curprocid">50%</div>
</div>
<div class="progress-bar clear"><div style="width: 50%;" id="progressid"></div></div>

</td></tr>
</table>
</div>
<?
}
}

?>
</td></tr>
</table>


<div class="position">

<table width="<? if ($mob_get!="1") { echo "450px"; } else { echo "280px"; } ?>">
<tr>
<td width="50px">
<img src="images/pin.gif" alt="" id="mapimg">
</td>
<td width="<? if ($mob_get!="1") { echo "400px"; } else { echo "230px"; } ?>">

<span id="addrIDdiv">
</span>

<div class="search-wrap">
&nbsp;
</div>
<div class="search" id="addrIDinput">
<input style="width: <? if ($mob_get!="1") { echo "390px"; } else { echo "220px"; } ?>" type="text" id="addrID" placeholder="<? echo $name_ln[22]; ?>" vertical-align="center" name="inputPlace">
</div>
</td>
</tr>
</table>

</div>


<div id="add_map" style="width: <? if ($mob_get!="1") { echo "450px"; } else { echo "280px"; } ?>; height: 270px; display: none;">
</div>


<HR SIZE=1>

<div class="layer11" style="width: <? if ($mob_get!="1") { echo "470px"; } else { echo "260px"; } ?>">

<div class="status" id="upload">
<a href="#" class="log clear" id="btnupload">
<U><? echo $name_ln[35]; ?></U>
</a>
<span id="status" ></span>
</div>

<table width="<? if ($mob_get!="1") { echo "470px"; } else { echo "260px"; } ?>">
<tr>
<td width="<? if ($mob_get!="1") { echo "330px"; } else { echo "80%"; } ?>" valign="top">
<img src="<? if ($img_get!="") { echo $img_get; } else { echo "images/blanc_new.png"; } ?>" alt="descr1" id="bigimg" width="<? if ($mob_get!="1") { echo "320px"; } else { echo "100%"; } ?>">
</td>
<td valign="top">
						<div id="smallimg">
						</div>

</td>
</tr>
</table>

</div>









<div class="layer1200">

<table width="200px">
<tr>
<td>
<a href="#" class="log clear"></a>
</td>
<td>

<div class="content0">




<table>
<tr>
<td width="<? if ($mob_get!="1") { echo "450px"; } else { echo "230px"; } ?>">
<iframe HEIGHT="60" id="preview-frame" src="social.php" name="preview-frame" frameborder="0" noresize="noresize" SCROLLING="NO" width="<? if ($mob_get!="1") { echo "445px"; } else { echo "230px"; } ?>"></iframe>
</td>
</tr>
<tr>
<td width="<? if ($mob_get!="1") { echo "450px"; } else { echo "230px"; } ?>">
<p>
<span id="text_adddiv">
</span>
<textarea style="width: <? if ($mob_get!="1") { echo "450px"; } else { echo "230px"; } ?>;" name="comment" id="text_add" class="content" placeholder="<? echo $name_ln[55]; ?>" cols="10" rows="10">
<?
echo $text;
?>
</textarea>
</p>
</td>
</tr>
</table>





<table style="margin-top: 5px">
<tr><td>
<div class="tabs3" id="btnsaveid">
<a data-tab-selector="#tab_save" id="btnsave"><? echo $name_ln[32]; ?></a>
</div>
</td></tr>
</table>

</div>

</td>
</tr>
</table>

</div>

<div class="coments-wrap clear" align="left" id="commentaddid">
<div class="title"><? echo $name_ln[58]; ?></div>
<?
if ($first_name!="") {
    ?>
    <div id="div_add">
    <div class="time">
    <? echo $name_ln[52]; ?>
    </div>
    <div class="comment"><div class="info"><a href="?uname=
    <? echo $first_name; ?>
    "><U>
    <? echo $first_name; ?>
    </U></a></div>
    <div>
    <textarea style="width: <? if ($mob_get!="1") { echo "400px"; } else { echo "230px"; } ?>;" name="commadd" id="comm_add" cols="10" rows="5">
    </textarea>
    <div class="clear">
	</div>
	<div class="tabs3" id="btncommid">
	<a data-tab-selector="#tab_comm" id="btncomm"><? echo $name_ln[59]; ?></a>
	</div>
    </div>
    
    </div>
    </div>
    <?
} else {
    ?>					<div id="div_add">
    <div class="time">
    <? echo $name_ln[46]; ?>
    </div><br>
    <?
}
?>
<div id="commentid"></div>
</div>
<div class="coments-wrap clear">
</div>

<div class="other-wrap">
</div>

</div>









</div>
<!-- content #END /-->

</div>

</body>
</html>

<script>

<? if ($mob_get!="") {
    //echo "document.getElementById('personaladd-tab').style.display = 'block';";
} ?>

$('#tab_list')
.toggleClass('variable-sizes')
.isotope('reLayout');
</script>
