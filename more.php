<?
include_once('db_vars.php');
include('functions_pr.php');

if ($_COOKIE['lang']) { 
    $lang = $_COOKIE['lang'];
} else {
    setcookie('lang', '1', (time()+2592000), '/', '', 0);
}
//$langNM="RU";
$langNM="EN";
//$valNM="RUR";
$valNM="USD";
//$valNM="UAH";
if ($lang==2) { $langNM="RU"; $valNM="RUR"; }
$itag=0;
$query = "SELECT * from Names_ln where id_ln='".$lang."' and id_s>48 and id_s<70";
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

$date_get = "";
$uname_get = "";
$loc_get = "";
$lat_get = "";
$long_get = "";
$date_get = $_GET['date'];
$uname_get = $_GET['uname'];
	$encode = detect_encoding($uname_get);
	if ($encode == "cp1251") {
		$uname_get = iconv("cp1251", "utf-8", $uname_get);
	}
$loc_get = $_GET['loc'];
$lat_get = $_GET['lat2'];
$long_get = $_GET['long2'];
$find = $_GET['find'];
	$encode = detect_encoding($find);
	if ($encode == "cp1251") {
		$find = iconv("cp1251", "utf-8", $find);
	}

$page = $_GET['page'];
$lat = $_GET['lat'];
$long = $_GET['long'];
$mob_get = $_GET['mob_get'];
$day1 = $name_ln[49]; //$_GET['day1'];
$day2 = $name_ln[50]; //$_GET['day2'];
$day3 = $name_ln[51]; //$_GET['day3'];
$day4 = $name_ln[52]; //$_GET['day4'];

        $content_list = "";
        $cur_params = "";
        $query_dop = "";
        $param_id1 = "15";
        $sum0 = 0;
        $sum1 = 0;
        $proc = 0;
        $daysago="";
        $param_id0 = $page*15;
	$lat1="";
	$long1="";
	if ($uname_get!="") {
		if ($query_dop!="") { $query_dop=$query_dop." and "; }
		$query_dop=$query_dop."first_name='".$uname_get."'";
	}
	if ($date_get!="") {
		if ($query_dop!="") { $query_dop=$query_dop." and "; }
		$query_dop=$query_dop."date1>=CAST('".$date_get."' AS DATE)";
	}
	if ($lat_get!="" && $long_get!="") {
		$lat = $lat_get;
		$long = $long_get;
	}
	if ($find!="") { if ($query_dop!="") { $query_dop=$query_dop." and ".$find; } else { $query_dop=$find; } }
	if ($query_dop!="") { $query_dop="where ".$query_dop; }

if ($page==0 && $uname_get!="") {
    
    $query = "SELECT * from user where first_name='".$uname_get."'";
    //$query = "SELECT * from user where first_name='".$uname_get."' and org='1'";
    
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_object($result)) {
        
		$date1=$row->date_created;
		$lat1=$row->ulat_adr;
		$long1=$row->ulong_adr;
        $published = strtotime($date1); //переводим в TIMESTAMP
        $today = time();
        $daysago=floor(($today - $published) / 86400);
        if ($daysago<1) { $daysago=$day4; }
        if ($daysago==1) { $daysago=$daysago." ".$day1; }
        if ($daysago>1) { $daysago=$daysago." ".$day2; }
        
        $addr1 = "";
        if ($row->location <> "")
        {    $addr1 = $row->location;}        
        
        $content_list=$content_list."<div class=\"animal-block\">";
        $content_list=$content_list."<div class=\"animal-foto\"><a href=\"javascript:void(0)\" ><img src=\"images/users/";
        $content_list=$content_list.$row->img;
        $content_list=$content_list.".jpg\" alt=\"\"></a>";
        $content_list=$content_list."</div>";
        $content_list=$content_list."<div class=\"description\">";
                
	    echo $content_list;
	    $content_list = "";

        $content_list=$content_list."<div><b><font size=\"4\">";
        if ($row->org==1) { $content_list=$content_list.$name_ln[61].": </br>"; }
        $content_list=$content_list.$row->first_name."</font></b></div>";
        $content_list=$content_list."<div class=\"location\"><a href=\"?loc=".$addr1."&lat=".$lat1."&long=".$long1."\"><U>".$addr1."</U></a></div>";
        $content_list=$content_list."<div>".$row->descr."</div>";
        
        if ($row->phone!="") {$content_list=$content_list."<table width=\"100%\"><tr><td></td><td><b>".$name_ln[62]."</b></td></tr><tr><td width=\"20%\" align=\"right\"><img src=\"images/p.png\" width=\"30px\"></td><td width=\"80%\"><div class=\"status\"><a href=\"tel:".$row->phone."\">".$row->phone."</a></div><div class=\"clear\"></div></td></tr></table>";} //callto:
        if ($row->web!="") {$content_list=$content_list."<table width=\"100%\"><tr><td></td><td><b>".$name_ln[63]."</b></td></tr><tr><td width=\"20%\" align=\"right\"><img src=\"images/i.png\" width=\"30px\"></td><td width=\"80%\"><div><a href=\"".$row->web."\" target=\"_blank\">&nbsp;&nbsp;&nbsp;".$row->web."</a></div></td></tr></table>";}
        
        if ($sum0 > 0) {
            $proc = floor(($sum1/$sum0)*100);
            $content_list=$content_list."<div class=\"data\">";
            $content_list=$content_list."</div>";
        }
        $content_list=$content_list."<div class=\"time\"><a href=\"?date=".$date1."\"><U>".$daysago."</U></a></div>";
        $content_list=$content_list."</div>";
	    $content_list=$content_list."</div>";
        
	    echo $content_list;
	    $content_list = "";
        
    }        
}

$query = "SELECT distinct obj.*, u.*, 1000 * 6372.797 * 2 * atan2(sqrt(power(sin((".$lat."*3.14159/180 - lat_adr*3.14159/180) / 2), 2) + cos(lat_adr*3.14159/180) * cos(".$lat."*3.14159/180) * power(sin((".$long."*3.14159/180 - long_adr*3.14159/180) / 2), 2)), sqrt(1 - power(sin((".$lat."*3.14159/180 - lat_adr*3.14159/180) / 2), 2) + cos(lat_adr*3.14159/180) * cos(".$lat."*3.14159/180) * power(sin((".$long."*3.14159/180 - long_adr*3.14159/180) / 2), 2))) as distance from Objects obj left join Tags_Obj1 tg on (tg.id_obj=obj.id_obj) left join user u on (u.user_id=obj.id_user) ".$query_dop." order by distance limit ".$param_id0.",".$param_id1;

        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($row = mysql_fetch_object($result)) {
            $type_k="";
            if ($row->id_t1==4) { $type_k="lost1"; }
            if ($row->id_t1==5) { $type_k="found1"; }
            if ($row->id_t1==6) { $type_k="needshome1"; }
        
		$date1=$row->date1;
		$lat1=$row->lat_adr;
		$long1=$row->long_adr;
            $published = strtotime($date1); //переводим в TIMESTAMP
            $today = time();
            $daysago=floor(($today - $published) / 86400);
            if ($daysago<1) { $daysago=$day4; }
            if ($daysago==1) { $daysago=$daysago." ".$day1; }
            if ($daysago>1) { $daysago=$daysago." ".$day2; }

		$sum0 = $row->sum;
		$sum1 = $row->sumcur;

            $addr1 = "";
            if ($row->map1 <> "")
            {    $addr1 = $addr1.$row->map1;}
            if ($row->map2 <> "")
            {    $addr1 = $addr1.", ".$row->map2;}
            if ($row->map3 <> "")
            {    $addr1 = $addr1.", ".$row->map3;}

            
			$content_list=$content_list."<div class=\"animal-block\" style=\"width: 265px;\">";
            if ($mob_get==1) {
                $content_list=$content_list."<div class=\"animal-foto\"><a href=\"?n=".$row->id_obj."\"><img src=\"images/obj/";
            } else {
                $content_list=$content_list."<div class=\"animal-foto\"><a href=\"javascript:void(0)\" onclick=\"selObj(".$row->id_obj.")\"><img src=\"images/obj/";
            }
            $content_list=$content_list.$row->id_p;
            $content_list=$content_list.".jpg\" alt=\"\"></a>";
            $content_list=$content_list."</div>";
            $content_list=$content_list."<div class=\"description\">";


	    echo $content_list;
	    $content_list = "";




	    //$content_list=$content_list."1<div class=\"pluso\" data-background=\"transparent\" data-options=\"small,square,line,horizontal,counter,theme=04\" data-services=\"vkontakte,odnoklassniki,facebook,twitter,google,moimir\" data-url=\"http://www.lostey.com\" data-title=\"Lostey\" data-description=\"Lostey\"></div>";



            $content_list=$content_list."<div>".$row->subj."</div>";
            //$content_list=$content_list."<div>".$query."</div>";
            $content_list=$content_list."<div class=\"location\"><a href=\"?loc=".$addr1."&lat=".$lat1."&long=".$long1."\"><U>".$addr1."</U></a></div>";
            $content_list=$content_list."<div><a href=\"?uname=".$row->first_name."\"><U>".$row->first_name."</U></a></div>";
            
            if ($row->phone!="") {$content_list=$content_list."<table width=\"100%\"><tr><td width=\"20%\" align=\"right\"><img src=\"images/p.png\" width=\"30px\"></td><td width=\"80%\"><div class=\"status\"><a href=\"tel:".$row->phone."\">".$row->phone."</a></div><div class=\"clear\"></div></td></tr></table>";} //callto:
            if ($row->web!="") {$content_list=$content_list."<table width=\"100%\"><tr><td width=\"20%\" align=\"right\"><img src=\"images/i.png\" width=\"30px\"></td><td width=\"80%\"><div><a href=\"".$row->web."\" target=\"_blank\">&nbsp;&nbsp;&nbsp;".$row->web."</a></div></td></tr></table>";}
            
	if ($sum0 > 0) {
		$proc = floor(($sum1/$sum0)*100);
            $content_list=$content_list."<div class=\"data\">";
            $content_list=$content_list."<div class=\"fl-l\">$".$sum0." USD</div>";
            $content_list=$content_list."<div class=\"fl-r\">".$proc."%</div>";
            $content_list=$content_list."</div>";
            $content_list=$content_list."<div class=\"progress-bar clear\"><div style=\"width: ".$proc."%;\"></div></div>";
	$Description=$name_ln[69].": ".$URL."/?n=".$row->id_obj;
        $ORDER=$row->id_obj."ORDER".$today;
        $URL2=$URL."/?a=donate|".$ORDER."|".$row->user_id."|".$row->id_obj;
	//	<kind>phone</kind>
	$xml="<request>      
		<version>1.2</version>
		<result_url>$URL2</result_url>
		<server_url>$URL2</server_url>
		<dcur>$valNM</dcur>
		<merchant_id>$merchant_id</merchant_id>
		<order_id>$ORDER</order_id>
		<amount>10</amount>
		<currency>$valNM</currency>
		<language>$langNM</language>
		<description>$Description</description>
		<default_phone>$phone</default_phone>
		<pay_way>$method</pay_way> 
		</request>
		";	
	$xml_encoded = base64_encode($xml); 
	$lqsignature = base64_encode(sha1($signature.$xml.$signature,1));

            $content_list=$content_list."<form action='$url' method='POST'><input type='hidden' name='operation_xml' value='$xml_encoded' /><input type='hidden' name='signature' value='$lqsignature' /><input class='status' type='submit' value='$name_ln[69]'/></form>";

            //$content_list=$content_list."<form style=\"display:inline\" method=POST action=\"https://www.liqpay.com/?do=clickNbuy\">";
            //$content_list=$content_list."<input type=hidden name=\"preorder\" value=\"39439d521b4cfc797347d580d075b0555f899e67\">";
            //$content_list=$content_list."<input type=submit value=\"Contribute\"></form>";

	}
            $content_list=$content_list."<div class=\"time\"><a href=\"?date=".$date1."\"><U>".$daysago."</U></a></div>";
            $content_list=$content_list."</div>";
	    $content_list=$content_list."</div>";
            //2 months ago

	    echo $content_list;
	    $content_list = "";

        }        
?>
