<?php
    include_once('../db_vars.php'); 
    require_once('checkuser.php');
    $feedback=0;
    if ($_POST['user_name'] && $_POST['password']) {
        if (strlen($_POST['username']) <= 25 && strlen($_POST['password']) <=25) {
            $feedback = user_login(); 
        } else { 
            $feedback = 'Error - user name or password too long!'; 
        } 
        if ($feedback == 1) { 
            $feedback_str = 'ok';
        } else { 
            $feedback_str = $feedback; 
        } 
    } else { 
        $feedback_str = ''; 
    } 
    echo $feedback_str;
?>