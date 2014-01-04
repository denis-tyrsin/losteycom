<?
$oper = "new";
if (!empty($_GET['o'])) 
{
        $oper = $_GET['o'];
}


include_once('db_vars.php');
//echo $_COOKIE['lang'];
$lang = '1';
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
//echo $name_tag[3][1];

header("Content-type: text/plain; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title></title>
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
	left: 38%;
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

<link rel="shortcut icon" href="../images/ico/corner.gif" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/><!-- media="screen, projection" -->
<!--[if IE 7]><link rel="stylesheet" href="css/ie.css"/><![endif]-->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.isotope.js"></script>
<script type="text/javascript" src="js/jquery.sticky.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.5.js" ></script>
<script src="//api-maps.yandex.ru/2.0/?load=package.standard&lang=<? echo $lang_ln; ?>-RU" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=<? echo $lang_ln; ?>"></script>

<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>



<script type="text/javascript">

var kol = 0;
<?
echo "var subject='".$name_ln[38]."';";
echo "var msg1='".$name_ln[39]."';";
echo "var msg2='".$name_ln[40]."';";
echo "var msg3='".$name_ln[41]."';";
echo "var url='".$URL."';";
?>

$(document).ready(function(){
                  $("#sent").click(function(){                         
                                      save_new();
                                      })
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



function save_new() {

    var req = getXmlHttp();
    var reqtext = '';
    var nm1=$('#mailID').val();
    var nm2=$('#passID').val();
    var nm3=$('#nameID').val();
    var nm4=$('#locID').val();
    var nm5=$('#descID').val();
    
    req.onreadystatechange = function() { 
        if (req.readyState == 4) {
            //statusElem.innerHTML = req.statusText 
            if(req.status == 200) {
		reqtext=req.responseText;
		if(reqtext.indexOf('/?hash=')==0) {
	                alert(reqtext+'!!!!!!!!!!');
			//sent_new();
		} else {
	                alert(reqtext);
		}
		location.reload();
            }
        }
    }
	kol=kol+1;
	document.getElementById('kol').value=kol;
    	str_sent='&mail='+nm1;
    	str_sent=str_sent+'&pass='+nm2;
    	str_sent=str_sent+'&name='+nm3;
    	str_sent=str_sent+'&desc='+nm4;
    	str_sent=str_sent+'&loc='+nm5;
    	str_sent=str_sent+'&kol='+kol;
        //alert(str_sent);
    if (nm1!="") {
        req.open('GET', 'save_mail1.php?act=m'+str_sent, false);
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
                alert(msg1);
                //location.reload()
                //$('#outward').fadeOut(0);
            }
        }
    }

    	str_sent='&to='+nm1;
	str_sent=str_sent+'&msgsent=1';
    	str_sent=str_sent+'&subject='+subject;
    	//str_sent=str_sent+'&subject=Lostey.com registration Confirmation';
	message = '<html> <head> <h3>'+msg1+'</h3> </head> <body> <p>';
	message = message+'</br><a href="'+url+'"><img src="'+url+'/images/bar/lost.png" alt=""></a>';
	message = message+'</br>'+msg2+' </br><a href="'+url+'">http://lostey.com</a></p> </body> </html> </br>'+msg3;
    	str_sent=str_sent+'&content='+message;

//$feedback = smtpmail($email, "Lostey.com registration Confirmation", $message);

    str_sent=str_sent+'&pass='+nm2;
    str_sent=str_sent+'&name='+nm3;
    str_sent=str_sent+'&desc='+nm4;
    str_sent=str_sent+'&loc='+nm5;
        //alert(str_sent);
    if (nm1!="") {
        req.open('GET', 'send8.php?act=m'+str_sent, true);
        req.send(null);
    }
}



</script>




</head>
<body>
<div id='container'>








	<table border='0'>

	<tr>
		<td align="left">
<a class="logo" href="<? echo $URL; ?>" id="btnlost"><img src="images/bar/lost.png" alt=""></a>

<div class="slogan-wrap" style="width:100%">
<div class="slogan">
<? 
if ($oper == "new") {
echo $name_ln[23]; 
}
if ($oper == "login") {
echo $name_ln[37]; 
}
?>
</div>
</div>
		</td>
	</tr>
	<tr>
		<td align="left">
<? echo $name_ln[26]; ?>
		</td>
	</tr>
	<tr>
		<td align="left">
<div class="search">
<input style="width:300px;" type="text" id="mailID" placeholder="" vertical-align="bottom" name="inputEmail">
</div>
		</td>
	</tr>
	<tr>
		<td align="left">
<? echo $name_ln[25]; ?>
		</td>
	</tr>
	<tr>
		<td align="left">
<div class="search">
<input style="width:300px;" type="text" id="passID" placeholder="" vertical-align="bottom" name="inputPwd">
</div>
		</td>
	</tr>
<? 
if ($oper == "new") {
?>
	<tr>
		<td align="left">
<? echo $name_ln[24]; ?>
		</td>
	</tr>
	<tr>
		<td align="left">
<div class="search">
<input style="width:300px;" type="text" id="nameID" placeholder="" vertical-align="bottom" name="inputName">
</div>
		</td>
	</tr>
	<tr>
		<td align="left">
<? echo $name_ln[27]; ?>
		</td>
	</tr>
	<tr>
		<td align="left">
<div class="search">
<input style="width:300px;" type="text" id="descID" placeholder="" vertical-align="bottom" name="inputDescr">
</div>
		</td>
	</tr>
	<tr>
		<td align="left">
<? echo $name_ln[28]; ?>
		</td>
	</tr>
	<tr>
		<td align="left">
<img src="images/pin.gif" alt="">
<div class="search">
<input style="width:300px;" type="text" id="locID" placeholder="<? echo $name_ln[22]; ?>" vertical-align="bottom" name="inputLoc">
</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="color:#ff0000">
<? echo $name_ln[29]; ?>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td align="center">
<div class="sign-wrap">
<div class="sign-in">
<a class="email" href="#" style="width:240px;" id="sent">
<?
if ($oper == "new") {
echo $name_ln[30];
}
if ($oper == "login") {
echo $name_ln[34]; 
}
?>
</a>

</div>
</div>
		</td>
	</tr>

	</table>

<input type = "hidden" id="kol" vertical-align="bottom" name="inputkol">








</div>
</body>
</html>