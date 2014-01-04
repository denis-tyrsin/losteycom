<?php
    include_once('../db_vars.php'); 
    require_once('checkuser.php');

    function validate_email($email) { 
        if(preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email))
        { 
            return $email;
        }
    }    

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


        $act = $_GET['act'];
        if (!empty($_GET['lat'])) { $lat = $_GET['lat']; } else { $lat = "0"; }
        if (!empty($_GET['long'])) { $long = $_GET['long']; } else { $long = "0"; }
        $name1 = $_GET['mail'];
        $image = $_GET['image'];
	$imagebd="0";
	if ($image!="") { $imagebd="1"; }
        $phone1 = $_GET['phone'];
        $web1 = $_GET['web'];
        $nameusr1 = $_GET['name'];
	$encode = detect_encoding($nameusr1);
	if ($encode == "cp1251") {
		$nameusr1 = iconv("cp1251", "utf-8", $nameusr1);
	}
        $descusr1 = $_GET['desc'];
	$encode = detect_encoding($descusr1);
	if ($encode == "cp1251") {
		$descusr1 = iconv("cp1251", "utf-8", $descusr1);
	}
        $locusr1 = $_GET['loc'];
	$encode = detect_encoding($locusr1);
	if ($encode == "cp1251") {
		$locusr1 = iconv("cp1251", "utf-8", $locusr1);
	}
        
//echo $name1." -> ";
        
        $flag=0;
        $res="";
        
        if ($act == 'm') {
            
            $i_sel = 0;
            $id_sel = 0;
            
            $feedback = "success";
            if ($name1!="") {

                if (strlen($name1) <= 50 && validate_email($name1)) { 
                    // Проверка имени пользователя и пароля 
                    
                    	$user_name = strtolower($name1); 
                    	$user_name = trim($user_name); 
			$encoded_email = urlencode($user_name);
                    	$email= $user_name;
                    // Сопоставление логина и email, заявленных новым пользователем, с уже имеющимися в БД 
                    $query = "SELECT user_id FROM user WHERE email = '".$email."'"; 
                    $result = mysql_query($query);
                    if ($row = mysql_fetch_object($result)) { 
                        $feedback = 'This e-mail address already exists!'; 
                    } else { 
                        $user_ip = $_SERVER['REMOTE_ADDR']; 
			if ($nameusr1=="") { $nameusr1=$name1; }
                        // Создайте новый хэш для вставки в БД и подтверждение по электронной почте 
                        $hash = md5($email.$supersecret_hash_padding);
                        $query = "INSERT INTO user (user_id, user_name, email, remote_addr, confirm_hash, is_confirmed, date_created, descr, location, first_name, phone, web, ulat_adr, ulong_adr, img) VALUES ('', '".$email."', '".$email."', '".$user_ip."', '".$hash."', 0, NOW(), '".$descusr1."', '".$locusr1."', '".$nameusr1."', '".$phone1."', '".$web1."', '".$lat."', '".$long."', '".$imagebd."')";
                        $result = mysql_query($query);
                        if (!$result) {
                            	$feedback = 'Error of the data base!'; 
                            	$feedback .= mysql_error(); 
                        } else {
				$feedback = '/?hash='.$hash.'&email='.$encoded_email;
			}
                    } 
                                        
                } else { 
                    $feedback = 'Please, type in the e-mail correctly!'; 
                }                 
            
            }
                        
        }
        
        if ($act == 'u') {
            
            $i_sel = 0;
            $id_sel = 0;
            
            $feedback = "success";
            if ($name1!="") {

                if (strlen($name1) <= 50 && validate_email($name1)) { 
                    // Проверка имени пользователя и пароля 
                    
                    	$user_name = strtolower($name1); 
                    	$user_name = trim($user_name); 
			$encoded_email = urlencode($user_name);
                    	$email= $user_name;
                    // Сопоставление логина и email, заявленных новым пользователем, с уже имеющимися в БД 
                    $query = "SELECT user_id FROM user WHERE email = '".$email."'"; 
                    $result = mysql_query($query);
                    if ($row = mysql_fetch_object($result)) {
			$user_id = $row->user_id;
                        $user_ip = $_SERVER['REMOTE_ADDR']; 
			if ($nameusr1=="") { $nameusr1=$name1; }
                        $hash = md5($email.$supersecret_hash_padding);

			$query = "UPDATE user SET user_name='".$email."', remote_addr='".$user_ip."', descr='".$descusr1."', location='".$locusr1."', first_name='".$nameusr1."', phone='".$phone1."', web='".$web1."', ulat_adr='".$lat."', ulong_adr='".$long."' WHERE user_id='".$user_id."'";
                        $result = mysql_query($query);
                        if (!$result) {
                            	$feedback = 'Error of the data base!'; 
                            	$feedback .= mysql_error(); 
                        } else {
				$feedback = '/?hash='.$hash.'&email='.$encoded_email;
				user_set_tokens($email, $user_id, '0', $nameusr1);
                        if ($image!="") {
                        	$query = "SELECT max(id_p) as idmax from Photos";
                        	$result = mysql_query($query) or die('Query id_p failed: ' . mysql_error());
                        	$row = mysql_fetch_object($result);
                        	$idpt = strval($row->idmax + 1);
	                        $query = "INSERT INTO Photos (id_p, id_obj) VALUES (".$idpt.", 0)";
                        	$result = mysql_query($query);
                          if (mysql_error()=='') {
                            $from0="../uploads/".$image;
                            $to0="../images/users/f".$idpt.".jpg";

                            $from01="../uploads/cr".$image;
                            $to01="../images/users/".$idpt.".jpg";
                            
                            $from012="../uploads/s".$image;
                            $to012="../images/users/s".$idpt.".jpg";
                            
                            //echo "-".$from0."-".$to0."-==";
                            
                            //include('resize_crop.php');
                            //resize($from0, $from01, 300, 0);
                            
                            rename($from01, $to01);
                            rename($from0, $to0);
                            rename($from012, $to012);

                            $query = "UPDATE user SET img=".$idpt." WHERE user_id='".$user_id."'";
                            $result = mysql_query($query) or die('Query update photo failed: ' . mysql_error());
//                            move_uploaded_file($from0, $to0);
			  }
                        }			}
                    } else { 
                        $feedback = 'This e-mail address does not exists!'; 
                    } 
                                        
                } else { 
                    $feedback = 'Please, type in the e-mail correctly!'; 
                }                 
            
            }
                        
        }
        
        if ($flag==0) {
            echo $feedback;
        }        
        
        //echo "success";
    } else {
        echo "error";
    }
?>
