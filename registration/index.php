<?
$oper = "new";
if (!empty($_GET['o'])) 
{
        $oper = $_GET['o'];
}

include_once('../db_vars.php');
require_once('checkuser.php');

$lang = '1';
if ($_COOKIE['lang']) { 
    $lang = $_COOKIE['lang'];
} else {
    setcookie('lang', '1', (time()+2592000), '/', '', 0);
}

$itag=0;
$query = "SELECT * from Names_ln where id_ln='".$lang."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_object($result)) {
    $name_ln[$row->id_s]=$row->name;
	if ($row->type>0) {
		$itag=$itag+1;
    		$name_tag[$itag][1]=$row->name;
    		$name_tag[$itag][2]=$row->id_s;
    		$name_tag[$itag][3]=$row->type;
	}
}
$lang_ln="";
$query = "SELECT * from Lang where id_ln='".$lang."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
if ($row = mysql_fetch_object($result)) {
    $lang_ln=$row->nm;
}

if ($oper == "logout") {
    	user_logout();
	$oper = "login";
}

$user_name = "";
$user_id = "";
$first_name = "";
$email = "";
$lat_adr="0";
$long_adr="0";
$img="";
$imgfile="";
$org="0";
If (user_isloggedin()) {
            $user_name =$_COOKIE['user_name'];
            $user_id =$_COOKIE['id_hash_id'];
            $avatar_img =$_COOKIE['img'];
	    $first_name =$_COOKIE['first_name'];
	    if ($first_name=="") { $first_name=$user_name; }

	$query = "SELECT * from user where user_id='".$user_id."'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	if ($row = mysql_fetch_object($result)) {
	    $user_name=$row->user_name;
	    $first_name=$row->first_name;
	    $last_name=$row->last_name;
	    $email=$row->email;
	    $phone=$row->phone;
	    $web=$row->web;
	    $location=$row->location;
	    $descr=$row->descr;
	    $lat_adr=$row->ulat_adr;
	    $long_adr=$row->ulong_adr;
	    $img=$row->img;
	    $org=$row->org;
	}
}
if ($img > 0) { $imgfile="../images/users/".$img.".jpg"; }
if ($oper == "login") { $email = ""; }
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width; initial-scale=0.8; maximum-scale=0.8; user-scalable=0;" />

<title>Lostey - registration</title>
<style type="text/css">
* {
	margin: 0;
	padding: 0;
}
html,
body {
	width: 100%;
	height: 100%;
}
#container {
	position: absolute;
	top: 5%;
	left: 15%;
	width:25%;
	height:25%;
	margin-top: 0px;
	margin-left: 0px;
}
table {
	width: 100%;
	height: 100%;
}
</style>

<link rel="shortcut icon" href="../../images/ico/corner.gif" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" href="../css/style.css"/><!-- media="screen, projection" -->
<!--[if IE 7]><link rel="stylesheet" href="../css/ie.css"/><![endif]-->
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.isotope.js"></script>
<script type="text/javascript" src="../js/jquery.sticky.js"></script>
<script type="text/javascript" src="../js/ajaxupload.3.5.js" ></script>
<script type="text/javascript" src="../js/jquery.functions_reginits5.js"></script>
<script src="//api-maps.yandex.ru/2.0/?load=package.standard&lang=<? echo $lang_ln; ?>-RU" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=<? echo $lang_ln; ?>"></script>

<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>



<script type="text/javascript">

<?
echo "var email='".$email."';";
echo "var subject='".$name_ln[38]."';";
if ($oper == "reset") {
    echo "var msg1='".$name_ln[67]."';";
} else {
    echo "var msg1='".$name_ln[39]."';";
}
if ($oper == "reset") {
    echo "var msg2='".$name_ln[68]."';";
} else {
    echo "var msg2='".$name_ln[40]."';";
}
if ($oper == "reset") {
    echo "var msg3='';";
} else {
    echo "var msg3='".$name_ln[41]."';";
}
echo "var msg4='".$name_ln[42]."';";
echo "var url='".$URL."';";
echo "var oper='".$oper."';";
$date_today = date("m.d.y");
$today = date("H:i:s");
echo "var kol='".$date_today."-".$today."';";
echo "var long2='".$long_adr."';";
echo "var lat2='".$lat_adr."';";
echo "var org='".$org."';";
?>
var map_add;
var marker;
var kf_sel='';

function usl(d, e)
{
 	if(d != "" && e.keyCode == 13) {
		loginto();
	}
}

$(document).ready(function(){
                  $("#reset").click(function(){
                                   var block = document.getElementById('layer1');
                                   block.style.display = 'block';
                                    reset_p();
                                    //alert ('reset!');
                                   });
                  $("#sent").click(function(){
                                   var block = document.getElementById('layer1');
                                   block.style.display = 'block';
                                   save_new();
                                   });
                  $("#login").click(function(){
  var block = document.getElementById('layer1');
  block.style.display = 'block';
                                      loginto();
                                      });


                  });

  $(function() {
    var btnUpload=$('#upload');
    var status=$('#status');
    new AjaxUpload(btnUpload, {
                 action: '../upload-file.php',
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
                 //kf=kf+1;
                 //namef[kf]=file;
		 kf_sel=file;
                 if(response.substring(0,7)==="success"){
			var img = document.getElementById('bigimg');  
         		img.src='../uploads/'+file;
                 	//$('#smallimg').append('<a onClick="javascript:sel_tr(\''+file+'\')"><img src="uploads/'+file+'" alt="" width="100px"></a>');
                 } else{
                 	status.text('Error!');
                 }
                 
                 }
  	});
  });

function getXmlHttp(){
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function loginto() {

    var req = getXmlHttp();
    var reqtext = '';
    var nm1=$('#mailID').val();
    var nm2=$('#passID').val();
    req.onreadystatechange = function() { 
        if (req.readyState == 4) {
            //statusElem.innerHTML = req.statusText 
            if(req.status == 200) {
		reqtext=req.responseText;
		//alert(reqtext);
		if (reqtext=='ok') {
	    		//document.getElementById('layer1').style.display = 'none';
			//location.reload(url);
			//window.location.reload(url);
            location.replace(url);
		} else {
			document.getElementById('layer1').style.display = 'none';
	                alert(reqtext);
		}
            }
        }
    }
    	str_sent='user_name='+nm1;
    	str_sent=str_sent+'&password='+nm2;
    	str_sent=str_sent+'&kol='+kol;
    if (nm1!="" && nm2!="") {
        //alert(str_sent);
        req.open('POST', 'login.php', true);
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send(str_sent); 
    }
}
function reset_p() {
    
    var req = getXmlHttp();
    var reqtext = '';
    var nm1=$('#mailID').val();
    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            //statusElem.innerHTML = req.statusText
            if(req.status == 200) {
                reqtext=req.responseText;
                if(reqtext.indexOf('/?hash=')==0) {
	                //alert(reqtext+'!!!!!!!!!!');
                    if (email=="") {
                        hash = reqtext;
                        sent_new();
                    } else {
                        location.replace(url);
                    }
                } else {
                    document.getElementById('layer1').style.display = 'none';
	                alert(reqtext);
                }
            }
        }
    }
    str_sent='&mail='+nm1;
    //alert(str_sent);
    if (nm1!="") {
        req.open('GET', 'reset.php?act=m'+str_sent, true);
        req.send(null);
    }
}

function save_new() {

    var req = getXmlHttp();
    var reqtext = '';
    var nm1=$('#mailID').val();
    var nm2=$('#passID').val();
    var nm3=$('#nameID').val();
    var nm4=$('#locID').val();
    var nm5=$('#descID').val();
    var nm6=$('#phoneID').val();
    var nm7=$('#webID').val();
    //document.getElementById('layer1').style.display = 'block';
    
    req.onreadystatechange = function() { 
        if (req.readyState == 4) {
            //statusElem.innerHTML = req.statusText 
            if(req.status == 200) {
		reqtext=req.responseText;
		if(reqtext.indexOf('/?hash=')==0) {
	                //alert(reqtext+'!!!!!!!!!!');
			if (email=="") {
				hash = reqtext;
				sent_new();
			} else {
				location.replace(url);
			}
		} else {
			document.getElementById('layer1').style.display = 'none';
	                alert(reqtext);
		}
            }
        }
    }
    	str_sent='&mail='+nm1;
    	str_sent=str_sent+'&lat='+lat2;
    	str_sent=str_sent+'&long='+long2;
    	str_sent=str_sent+'&org='+org;
    	str_sent=str_sent+'&image='+kf_sel;
    	str_sent=str_sent+'&pass='+nm2;
    	str_sent=str_sent+'&name='+nm3;
    	str_sent=str_sent+'&desc='+nm5;
        str_sent=str_sent+'&loc='+nm4;
        str_sent=str_sent+'&phone='+nm6;
        str_sent=str_sent+'&web='+nm7;
    	str_sent=str_sent+'&kol='+kol;
        //alert(str_sent);
    if (nm1!="") {
	if (email=="") {
        	req.open('GET', 'save_mail.php?act=m'+str_sent, true);
	} else {
        	req.open('GET', 'save_mail.php?act=u'+str_sent, true);
	}
        req.send(null);
    }
}

function sent_new() {

    var req = getXmlHttp();
    var nm1=$('#mailID').val();
    var nm2=$('#passID').val();
    var nm3=$('#nameID').val();
    var nm4=$('#locID').val();
    var nm5=$('#descID').val();
    
    req.onreadystatechange = function() { 
        if (req.readyState == 4) {
            //statusElem.innerHTML = req.statusText 
            if(req.status == 200) {
                
                //reqtext=req.responseText;
                //alert(reqtext);
                
		document.getElementById('layer1').style.display = 'none';
                if (oper!='reset') {
                    alert(msg1+'! '+msg4+'.');
                } else {
                    alert(msg4+'.');
                }
                //location.replace(url);
                location.replace(url+'/registration'+hash);
            }
        }
    }

    	str_sent='&to='+nm1;
	str_sent=str_sent+'&msgsent=1';
    	str_sent=str_sent+'&subject='+subject;
    	//str_sent=str_sent+'&subject=Lostey.com registration Confirmation';
	message = '<html> <head> <h3>'+msg1+'</h3> </head> <body> <p>';
	message = message+'</br><a href="'+url+'"><img src="'+url+'/images/bar/lost.png" alt=""></a>';
	message = message+'</br>'+msg2+' </br><a href="'+url+'/registration'+hash+'">'+url+'/registration</a></p> </body> </html> </br>'+msg3;
    	str_sent=str_sent+'&content='+message;

//$feedback = smtpmail($email, "Lostey.com registration Confirmation", $message);

    str_sent=str_sent+'&pass='+nm2;
    str_sent=str_sent+'&name='+nm3;
    str_sent=str_sent+'&desc='+nm4;
    str_sent=str_sent+'&loc='+nm5;
    
    //alert(url+'/registration'+hash);

    if (nm1!="") {
        req.open('GET', 'send.php?act=m'+str_sent, true);
        req.send(null);
    }
}

ymaps.ready(init);
google.maps.event.addDomListener(window, 'load', initialize);

</script>




</head>
<meta http-equiv="content-type" content=text/html; charset="utf-8">
<body>
<div id='container'>








	<table border='0' style="width: 330px;">

	<tr>
		<td align="left">
<a class="logo" href="<? echo $URL; ?>" id="btnlost"><img src="../images/bar/lost.png" alt=""></a>

<div id="layer1">
<img src="../images/ajax-loader6.gif" alt="">
</div>

<div class="wrap" id="sloganid" style="align: center">
<div class="slogan-wrap" style="width:100%">
<div class="slogan">
<?

$lastun="";
if ($_GET['hash'] && $_GET['email']) { 
    $worked = user_confirm();

    $flag_view=1;
    if ($worked != 1) {
	echo $name_ln[44];
	$oper = "new";
    } else {
	echo $name_ln[43];
        $oper = "login";
    }

} else {

if ($oper == "new") {
	if ($email=="") { echo $name_ln[23]; } else { echo $name_ln[60]; echo "<br>".$email; }
}
if ($oper == "login") {
	echo $name_ln[37];
	if ($_GET['email']) { } else {
		if ($_COOKIE['last_user_name']) {
			$lastun=$_COOKIE['last_user_name'];
		}
	}
}

}

?>
</div>
</div>
</div>
		</td>
	</tr>
	<tr <? if ($email!="") { echo "style=\"display:none;\""; } ?>>
		<td align="left">
<? echo $name_ln[26]; ?>
		</td>
	</tr>
	<tr <? if ($email!="") { echo "style=\"display:none;\""; } ?>>
		<td align="left">
<div class="search">
<input style="width:300px;" type="text" id="mailID" placeholder="" vertical-align="bottom" name="inputEmail" value="<? if ($oper == "new" && $email!="") { echo $email; } else { echo $_GET['email'].$lastun; } ?>">
</div>
		</td>
	</tr>
<?
if ($oper == "login" || $oper == "reset") {
?>
	<tr>
		<td align="left">
    <? if ($oper != "reset") { echo $name_ln[25]; } ?>
		</td>
	</tr>
	<tr>
		<td align="left">
    <div class="search" <? if ($oper == "reset") { ?>style="display: none;"<? } ?>>
<input style="width:300px;" type="password" id="passID" placeholder="" vertical-align="bottom" name="inputPwd" <? if ($oper == "login") { echo "onkeypress=\"usl(this.value, event)\""; } ?>>
</div>
		</td>
	</tr>
    </table>
    <table <? if ($oper == "login" || $oper == "reset") { ?>style="display: none;"<? } ?>>
<?
} 
?>
<div <? if ($oper != "new") { ?>style="display: none;"<? } ?>>
	<tr>
		<td align="left">
<? echo $name_ln[24]; ?>
		</td>
	</tr>
	<tr>
		<td align="left">
<div class="search" align="left">
<input style="width:300px;" type="text" id="nameID" placeholder="" vertical-align="bottom" name="inputName" value="<? if ($email!="") { echo $first_name; } ?>">
</div>
		</td>
	</tr>

	<tr <? if ($email=="") { ?>style="display: none;"<? } ?>>
    <td align="left">
<div class="status000" id="upload">
<a href="#" class="log clear" id="btnupload">
<b><U><? echo $name_ln[35]; ?></U></b>
</a>
<span id="status" ></span>
</div>
    </td>
	</tr>
	<tr <? if ($email=="") { ?>style="display: none;"<? } ?>>
    <td align="right">
<img src="<? if ($imgfile!="") { echo $imgfile; } else { echo "../images/blanc_new.png"; } ?>" alt="descr1" id="bigimg" width="320px">
    </td>
	</tr>
    
	<tr>
    <td align="left">
    <? echo $name_ln[56]; ?>
    </td>
	</tr>
	<tr>
    <td align="left">
    <div class="search" align="left">
    <input style="width:300px;" type="text" id="phoneID" placeholder="" vertical-align="bottom" name="inputPhone" value="<? if ($email!="") { echo $phone; } ?>">
    </div>
    </td>
	</tr>    
	<tr>
    <td align="left">
<input type="checkbox" name="option2" value="a2" <? if ($org=="1") { echo "checked"; } 
if ($email!="") { echo " disabled"; }
 ?>> <? echo $name_ln[61]; ?>&nbsp;</br>&nbsp;

<script>

$(":checkbox").change(function(){
var val0;
    if(this.checked){
	//document.getElementById('shelter').style.display = 'block';
    //document.getElementById('shelter').style.backgroundColor = '#ffffff';
        org=1;
        document.getElementById('shelter').style.opacity = '1.0';
                      //opacity: 0.7; /* Полупрозрачный фон */
        document.getElementById('shelter').style.filter = 'alpha(Opacity=100)';
                      document.getElementById('descID').readOnly=false;
                      document.getElementById('webID').readOnly=false;
                      document.getElementById('locID').readOnly=false;
                      document.getElementById('add_map').readOnly=false;
    }else{
	//document.getElementById('shelter').style.display = 'none';
    //document.getElementById('shelter').style.backgroundColor = '#f2f2f2';
        org=0;
        document.getElementById('shelter').style.opacity = '0.2';
        document.getElementById('shelter').style.filter = 'alpha(Opacity=20)';
                      document.getElementById('descID').readOnly=true;
                      document.getElementById('webID').readOnly=true;
                      document.getElementById('locID').readOnly=true;
                      document.getElementById('add_map').readOnly=true;
    }
}); 

</script>
    </td>
	</tr>

    </table>

<table id="shelter" style="border: 2px solid #f2f2f2; width: 330px; <? if ($oper == "new" && $email=="") { ?>opacity: 0.2;<? } else { if ($oper == "login" || $org=="0") { ?>display: none;<? } else { ?>opacity: 1.0;<? } } ?>">

	<tr>
    <td align="left">
    <? echo $name_ln[27]; ?>
    </td>
	</tr>
	<tr>
    <td align="left">
    <div class="search">
<input <? if ($oper == "new" && $email=="") { ?>readonly<? } ?> style="width:300px;" type="text" id="descID" placeholder="" vertical-align="bottom" name="inputDescr" value="<? if ($email!="") { echo $descr; } ?>">
    </div>
    </td>
	</tr>
    
	<tr>
    <td align="left">
    <? echo $name_ln[57]; ?>
    </td>
	</tr>
	<tr>
    <td align="left">
    <div class="search">
    <input <? if ($oper == "new" && $email=="") { ?>readonly<? } ?> style="width:300px;" type="text" id="webID" placeholder="" vertical-align="bottom" name="inputWeb" value="<? if ($email!="") { echo $web; } ?>">
    </div>
    </td>
	</tr>
    
	<tr>
		<td align="left">
<img src="../images/pin.gif" alt="">
<? echo $name_ln[28]; ?>
		</td>
	</tr>
	<tr>
		<td align="right">
<div class="search">
<input <? if ($oper == "new" && $email=="") { ?>readonly<? } ?> style="width:300px;" type="text" id="locID" placeholder="<? echo $name_ln[22]; ?>" vertical-align="bottom" name="inputLoc" value="<? if ($email!="") { echo $location; } ?>">
</div>
<div id="add_map" style="width: 320px; height: 270px; display: block;">
</div>
		</td>
	</tr>
    </table>
    <table>   
	<tr <? if ($oper == "login" || $email!="") { ?>style="display: none;"<? } ?>>
		<td align="left" style="color:#ff0000">
<? if ($email=="") { if ($oper == "reset") { echo $name_ln[66]; } else { echo $name_ln[29]; } } ?>
		</td>
	</tr>
	<tr>
		<td align="left">
<? if ($oper == "login") { ?>
<div style="width:300px; align:left;" class="log clear"><? echo $name_ln[65]; ?> &nbsp;<a href="?o=reset"><? echo $name_ln[64]; ?></a></div></br>
<? } ?>
<div class="sign-wrap">
<div class="sign-in">
<a class="email" href="#" style="width:240px;" id="<? if ($oper == "login") { echo "login"; } else { if ($oper == "reset") { echo "reset"; } else { echo "sent"; } } ?>">
<?
if ($oper == "new") {
	if ($email=="") { echo $name_ln[30]; } else { echo $name_ln[32]; }
}
if ($oper == "login") {
    echo $name_ln[34];
}
if ($oper == "reset") {
    echo $name_ln[64];
}
?>
</a>
</div>
</div>
		</td>
	</tr>

	</table>

<input type = "hidden" id="kol" vertical-align="bottom" name="inputkol" value="<? echo $date_today; echo $today; ?>">

</div>
</body>


<script type="text/javascript">
var mailval = $('#mailID').val();
document.getElementById('layer1').style.display = 'none';
//document.getElementById('mailID').readOnly=false;
if (oper=='new') {
	if (email=="") {
		document.getElementById("mailID").focus();
	} else {
		document.getElementById("nameID").focus();
		//document.getElementById('mailID').readOnly=true;
	}
} else {
    if (oper=='reset') {
		document.getElementById("mailID").focus();
    } else {
        document.getElementById("passID").focus();
    }
}
</script>


</html>