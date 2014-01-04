<?php
function user_confirm() { 
    global $supersecret_hash_padding; 
    $new_hash = md5($_GET['email'].$supersecret_hash_padding); 
    if ($new_hash && ($new_hash == $_GET['hash'])) { 
        $query = "SELECT user_name 
        FROM user 
        WHERE confirm_hash = '$new_hash' and state=0"; 
        $result = mysql_query($query); 
        if (!$result || mysql_num_rows($result) < 1) { 
            $feedback = 'ERROR - Hash not found'; 
            return $feedback; 
        } else { 
            $email = $_GET['email']; 
            $hash = $_GET['hash']; 
            $query = "UPDATE user SET email='$email', is_confirmed=1 WHERE confirm_hash='$hash'"; 
            $result = mysql_query($query); 
            return 1; 
        } 
    } else { 
        $feedback = 'ERROR - Hash'; 
        return $feedback; 
    } 
} 
function user_isloggedin() { 
    global $supersecret_hash_padding, $LOGGED_IN;
    if ($_COOKIE['user_name'] && $_COOKIE['id_hash']) {
        $hash = md5($_COOKIE['user_name'].$supersecret_hash_padding);
        if ($hash == $_COOKIE['id_hash']) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function user_login() { 
    if (!$_POST['user_name'] || !$_POST['password']) { 
        $feedback = 'Not found user or incorrect password!'; 
        return $feedback; 
    } else { 
        $user_name = strtolower($_POST['user_name']); 
        $password = $_POST['password']; 
        $user_id="";
	$first_name="";
        $img="0";
        $crypt_pwd = md5($password); 
        $query = "SELECT user_name, first_name, is_confirmed, state, user_id  
        FROM user 
        WHERE user_name = '$user_name'";
        $result = mysql_query($query);
        if ($row=mysql_fetch_object($result)) {
            if ($row->state==0) {
                $query = "UPDATE user SET state=1, password='$crypt_pwd' WHERE user_name = '$user_name'"; 
                $result = mysql_query($query);                 
            }
            $user_id = $row->user_id;
            $first_name = $row->first_name;
        }
        
        $query = "SELECT user_name, first_name, is_confirmed, state, img 
        FROM user 
        WHERE user_name = '$user_name' 
        AND password = '$crypt_pwd'";
        $result = mysql_query($query);
        if (!$result || mysql_num_rows($result) < 1){
            $feedback = 'Not found user or incorrect password!';
            return $feedback;
        } else { 
            if (mysql_result($result, 0, 'is_confirmed') == '1') { 
                if ($row=mysql_fetch_object($result)) { $img=$row->img; }
                    
                user_set_tokens($user_name, $user_id, $img, $first_name);
                
                return 1; 
            } else { 
                $feedback = 'No registration!'; 
                return $feedback; 
            } 
        } 
    } 
}
function user_logout() {
    setcookie('first_name', '', (time()+2592000), '/', '', 0); 
    setcookie('user_name', '', (time()+2592000), '/', '', 0); 
    setcookie('id_hash', '', (time()+2592000), '/', '', 0); 
    setcookie('id_hash_id', '', (time()+2592000), '/', '', 0); 
    setcookie('img', '', (time()+2592000), '/', '', 0); 
}
function user_set_tokens($user_name_in, $user_id_in, $img0, $first_name) { 
    global $supersecret_hash_padding; 
    if (!$user_name_in) { 
        $feedback =  'No user name!'; 
        return false; 
    } 
    $user_name = strtolower($user_name_in); 
    $id_hash = md5($user_name.$supersecret_hash_padding); 
    $id_hash_id = $user_id_in; 
    $img=$img0;
    setcookie('first_name', $first_name, (time()+2592000), '/', '', 0); 
    setcookie('last_user_name', $user_name, (time()+2592000), '/', '', 0); 
    setcookie('user_name', $user_name, (time()+2592000), '/', '', 0); 
    setcookie('id_hash', $id_hash, (time()+2592000), '/', '', 0); 
    setcookie('id_hash_id', $id_hash_id, (time()+2592000), '/', '', 0); 
    setcookie('img', $img, (time()+2592000), '/', '', 0); 
} 
?>