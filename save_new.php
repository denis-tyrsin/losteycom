<head>
<meta http-equiv="content-type" content=text/html; charset="utf-8">
</head>
<?php
header('Content-type: text/html; charset=utf-8');
$uploaddir = './uploads/';

function detect_encoding($string, $pattern_size = 50)
{
    $list = array('cp1251', 'utf-8', 'ascii', '855', 'KOI8R', 'ISO-IR-111', 'CP866', 'KOI8U');
    $c = strlen($string);
    if ($c > $pattern_size)
    {
        $string = substr($string, floor(($c - $pattern_size) /2), $pattern_size);
        $c = $pattern_size;
    }

    $reg1 = '/(\xE0|\xE5|\xE8|\xEE|\xF3|\xFB|\xFD|\xFE|\xFF)/i';
    $reg2 = '/(\xE1|\xE2|\xE3|\xE4|\xE6|\xE7|\xE9|\xEA|\xEB|\xEC|\xED|\xEF|\xF0|\xF1|\xF2|\xF4|\xF5|\xF6|\xF7|\xF8|\xF9|\xFA|\xFC)/i';

    $mk = 10000;
    $enc = 'ascii';
    foreach ($list as $item)
    {
        $sample1 = @iconv($item, 'cp1251', $string);
        $gl = @preg_match_all($reg1, $sample1, $arr);
        $sl = @preg_match_all($reg2, $sample1, $arr);
        if (!$gl || !$sl) continue;
        $k = abs(3 - ($sl / $gl));
        $k += $c - $gl - $sl;
        if ($k < $mk)
        {
            $enc = $item;
            $mk = $k;
        }
    }
    return $enc;
}


    if (!empty($_GET['act'])) 
    {
        $user_id ="-11";
        If ($_COOKIE['id_hash_id']) {
            //$user_name =$_COOKIE['user_name'];
            $user_id =$_COOKIE['id_hash_id'];
        }
	$cur_user_id = $_GET['cur_user_id'];
      if ($cur_user_id==$user_id) {
        //print_r($_POST);
        
        $act = $_GET['act'];
        
        $type_o = $_GET['type_o'];
        $summa0 = $_GET['sum'];
        $sum_v=0;
        if ($summa0>0) { $sum_v=$summa0; }
        $sum_s="";
        $sum_s=$sum_v;
        $new_lat = $_GET['new_lat'];
        $new_lng = $_GET['new_lng'];
        
        $kf_sel = $_GET['kf_sel'];
        $sum1 = $_GET['sum1'];
        
	$name1 = $_GET['name_1'];
	$encode = detect_encoding($name1);
	if ($encode == "cp1251") {
		$name1 = iconv("cp1251", "utf-8", $name1);
	}
        
	$subj1 = $_GET['subj1'];
	$encode = detect_encoding($subj1);
	if ($encode == "cp1251") {
		$subj1 = iconv("cp1251", "utf-8", $subj1);
	}
        
	$text1 = $_GET['text1'];
//echo "-".$text1."-".detect_encoding($text1)."+";
	$encode = detect_encoding($text1);
	if ($encode == "cp1251") {
		$text1 = iconv("cp1251", "utf-8", $text1);
	}
//echo "-".$text1."-".detect_encoding($text1)."+";
//echo "-".$name1."-".$subj1."-".$text1."-";











$encode0 = 0;

if ($encode0 == 0) {














        $cur_id = $_GET['cur_id'];
        $kf = $_GET['kf'];
        $kfstart = $_GET['kfstart'];
        
        $flag=0;
        $res="";
        

        if ($act == 'n' || ($act == 'e' && $cur_id != '')) {

            $i_sel = 0;
            $id_sel = 0;
            
            //echo "=".$name1."=";
            
            for ($i=1; $i<=$kf; $i++) {
                $istr=strval($i);
                $name_p = 'namef'.$istr;
                $namef[$i] = $_GET[$name_p];
                if ($kf_sel==$namef[$i]) { 
                    $i_sel = $i; 
                }
                //echo $namef[i];
            }
	$kf_sub = "";
	$lenkf=strlen($kf_sel);
	if ($lenkf>10) {
		$kf_sub0=substr($kf_sel, 0, 10);
		if ($kf_sub0 == "images/obj") {
			$kf_sub=str_replace("images/obj/", "", $kf_sel);
			$kf_sub = str_replace(".jpg", "", $kf_sub);
		}
	}


            include_once('db_vars.php'); 
            
            $id_name_itog="0";
            $id_country0="0";
            $country0="";
            
            
            
            $flag=1;
            $res="Can't connect to the database (Obj). Try later.";
            for ($i=1; $i<=4; $i++) {
                //mb_convert_encoding($text1, "UTF-8")
		//iconv("utf-8", "cp1251", $text1)
		//mb_detect_encoding($text1)
	        if ($act == 'e') {
			$idobj = $cur_id;
			//$query = "UPDATE Objects SET map1='".$name1."', subj='".$subj1."', text='".$text1."', status=0, map2='".$country0."', lat_adr='".$new_lat."', long_adr='".$new_lng."' WHERE id_obj='".$idobj."'";
			$query = "UPDATE Objects SET map1='".$name1."', subj='".$subj1."', text='".$text1."', status=0, map2='".$country0."', lat_adr='".$new_lat."', long_adr='".$new_lng."', sum='".$sum1."' WHERE id_obj='".$idobj."'";
		} else {
                	$query = "SELECT max(id_obj) as idmax from Objects";
                	$result = mysql_query($query) or die('Query id_o failed: ' . mysql_error());
        	        $row = mysql_fetch_object($result);
	                $idobj = strval($row->idmax+1);
	                $query = "INSERT INTO Objects (id_obj, map1, subj, text, date1, status, map2, lat_adr, long_adr, id_user, sum) VALUES (".$idobj.", '".$name1."', '".$subj1."', '".$text1."', CURDATE(), 0, '".$country0."', '".$new_lat."', '".$new_lng."', '".$cur_user_id."', '".$sum1."')";
		}
                $result = mysql_query($query);
                if (mysql_error()=='') { 
                    $i=5; 
                    $flag=0;
                    $res="";
                }
            }
            
            if ($flag==0) {
	        if ($act == 'e') {
	                $query = "DELETE FROM Tags_Obj1 WHERE id_obj='".$idobj."'";
			$result = mysql_query($query);
		}
                $query = "INSERT INTO Tags_Obj1 (id_obj, id_t1) VALUES ";
                if ($type_o != "") {
			$query = $query.str_replace("idobj",$idobj,$type_o);
		}
            	$result = mysql_query($query);

		if($kf_sub != "") {
                        $query = "UPDATE Objects SET id_p=".$kf_sub." WHERE id_obj=".$idobj;
            //echo "-".$query."-"; 
                        $result = mysql_query($query) or die('Query update photo failed: ' . mysql_error());
		}
            }
            
            if ($flag==0 && $kf>0) {
                //Query failed
                    $flag=1;
                    $res="Can't connect to the database (Photo). Try later.";
                    
                    for ($i=1; $i<=4; $i++) {
                        //echo "-".strval($i)."-"; 
                        $query = "SELECT max(id_p) as idmax from Photos";
                        $result = mysql_query($query) or die('Query id_p failed: ' . mysql_error());
                        $row = mysql_fetch_object($result);
                        $idpt = strval($row->idmax + 1);
                        //echo "-".$idpt."-";
                        if ($namef[1]=="") {$idobj=0;}
                        $query = "INSERT INTO Photos (id_p, id_obj) VALUES (".$idpt.", ".$idobj.")";

                        if ($kf>0) {
                            $id_sel = $idpt; 
                            if ($namef[1]!="") {$namef_new[1] = $idpt;}
                        if ($kf>1) {
                        for ($j=2; $j<=$kf; $j++) {
                            $idpt = strval($row->idmax + $j);
                            $namef_new[$j] = $idpt;
                            if ($namef[$j]!="") {
                            $query = $query.", (".$idpt.", ".$idobj.")";
                            if ($i_sel==$j) { $id_sel = $idpt; }
                            }
                        }
                        }
                        
                        $result = mysql_query($query);
                        }
                        if (mysql_error()=='') { 
                            //echo "-".strval($i)."-"; 
                            $i=5; 
                            $flag=0;
                            $res="";
                        }
                    }
                    
                    
                    
                    
                    if ($flag==0) {
                        
                        for ($j=1; $j<=$kf; $j++) {
                            
                            $from0="uploads/".$namef[$j];
                            $to0="images/obj/f".$namef_new[$j].".jpg";
                            
                            $from01="uploads/cr".$namef[$j];
                            $to01="images/obj/".$namef_new[$j].".jpg";
                            
                            $from012="uploads/s".$namef[$j];
                            $to012="images/obj/s".$namef_new[$j].".jpg";
                            
                            echo "-".$from0."-".$to0."-==";
                            
                            //include('resize_crop.php');
                            //resize($from0, $from01, 300, 0);
                            
                            rename($from01, $to01);
                            rename($from0, $to0);
                            rename($from012, $to012);
//                            move_uploaded_file($from0, $to0);
                        }
                        
                        $query = "UPDATE Objects SET id_p=".$id_sel." WHERE id_obj=".$idobj;
                        $result = mysql_query($query) or die('Query update photo failed: ' . mysql_error());
                        
                    }

                
                
            }            
            
            
            
            
        }
        
        if ($flag==0) {
            echo "success";
        }        
        








}





      } else {
        echo "no user";
      }
        //echo "success";
    } else {
        echo "error";
    }
    





?>
