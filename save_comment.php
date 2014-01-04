<?php
    header('Content-type: text/html; charset=utf-8');
    include_once('db_vars.php'); 
    include('functions_pr.php');
    require_once('registration/checkuser.php');    
    
    if (!empty($_GET['act'])) 
    {
        $act = $_GET['act'];
        $objid1 = $_GET['objid'];
        $usrid1 = $_GET['usrid'];
        
        $text1 = $_GET['text'];
        $encode = detect_encoding($text1);
        if ($encode == "cp1251") {
            $text1 = iconv("cp1251", "utf-8", $text1);
        }
        $encoded_email="";
        //echo $name1." -> ";
        $flag=0;
        $res="";
        if ($act == 'm') {
            $feedback = "success";
            if ($usrid1!="" & $objid1!="" & $text1!="") {
                // Проверка имени пользователя
                $query = "SELECT obj.id_user, u.email FROM Objects obj left join user u on (u.user_id=obj.id_user) WHERE id_obj = '".$objid1."'";
                $result = mysql_query($query);
                if ($row = mysql_fetch_object($result)) {
                    $encoded_email=$row->email;
                    $query = "INSERT INTO comments (id_user, id_obj, text, date) VALUES ('".$usrid1."', '".$objid1."', '".$text1."', NOW())";
                    $result = mysql_query($query);
                    if (!$result) {
                        $feedback = 'Error of the data base!'; 
                        $feedback .= mysql_error(); 
                    } else {
                        $feedback = '&to='.$encoded_email;
                    }
                } else {
                    $feedback = 'The object does not exist!';
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