<?php
    include_once('db_vars.php'); 
    include('functions_pr.php');
    
    if ($_COOKIE['lang']) { 
        $lang = $_COOKIE['lang'];
    } else {
        setcookie('lang', '1', (time()+2592000), '/', '', 0);
    }
    
    $itag=0;
    $query = "SELECT * from Names_ln where id_ln='".$lang."' and id_s>48 and id_s<53";
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
    $day1 = $name_ln[49]; //$_GET['day1'];
    $day2 = $name_ln[50]; //$_GET['day2'];
    $day3 = $name_ln[51]; //$_GET['day3'];
    $day4 = $name_ln[52]; //$_GET['day4'];
    
    $content_list = "";
    $cur_params = "";
    $query_dop = "";
    //$param_id1 = "15";
    $param_id1 = "50";
    $sum0 = 0;
    $sum1 = 0;
    $proc = 0;
    $daysago="";
    //$param_id0 = $page*15;
    $param_id0 = "0";
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
    
    $query = "SELECT distinct obj.*, u.*, 1000 * 6372.797 * 2 * atan2(sqrt(power(sin((".$lat."*3.14159/180 - lat_adr*3.14159/180) / 2), 2) + cos(lat_adr*3.14159/180) * cos(".$lat."*3.14159/180) * power(sin((".$long."*3.14159/180 - long_adr*3.14159/180) / 2), 2)), sqrt(1 - power(sin((".$lat."*3.14159/180 - lat_adr*3.14159/180) / 2), 2) + cos(lat_adr*3.14159/180) * cos(".$lat."*3.14159/180) * power(sin((".$long."*3.14159/180 - long_adr*3.14159/180) / 2), 2))) as distance from Objects obj left join Tags_Obj1 tg on (tg.id_obj=obj.id_obj) left join user u on (u.user_id=obj.id_user) ".$query_dop." order by distance limit ".$param_id0.",".$param_id1;
    //$query = "SELECT distinct obj.*, tg.id_t1, u.*, 1000 * 6372.797 * 2 * atan2(sqrt(power(sin((".$lat."*3.14159/180 - lat_adr*3.14159/180) / 2), 2) + cos(lat_adr*3.14159/180) * cos(".$lat."*3.14159/180) * power(sin((".$long."*3.14159/180 - long_adr*3.14159/180) / 2), 2)), sqrt(1 - power(sin((".$lat."*3.14159/180 - lat_adr*3.14159/180) / 2), 2) + cos(lat_adr*3.14159/180) * cos(".$lat."*3.14159/180) * power(sin((".$long."*3.14159/180 - long_adr*3.14159/180) / 2), 2))) as distance from Objects obj left join Tags_Obj1 tg on (tg.id_obj=obj.id_obj) left join user u on (u.user_id=obj.id_user) ".$query_dop." order by distance limit ".$param_id0.",".$param_id1;
    $feedback="";
    
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_object($result)) {
        
        if ($row->id_obj>0) { $feedback=$feedback."|".$row->id_p."|".$row->lat_adr."|".$row->long_adr."|".$row->id_obj; }
        
        
    }
    
    echo $feedback;
    
?>