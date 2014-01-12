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
        $name1 = $_GET['mail'];
        
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
                        $user_ip = $_SERVER['REMOTE_ADDR'];
                        // Создайте новый хэш для вставки в БД и подтверждение по электронной почте
                        $hash = md5($email.$supersecret_hash_padding);
                        $query = "UPDATE user SET State='0', is_confirmed='0', remote_addr='".$user_ip."' WHERE email = '".$email."'";
                        $result = mysql_query($query);
                        if (!$result) {
                            $feedback = 'Error of the data base!';
                            $feedback .= mysql_error();
                        } else {
                            $feedback = '/?hash='.$hash.'&email='.$encoded_email;
                        }
                    } else {
                        $feedback = 'This e-mail does not exists!';
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
