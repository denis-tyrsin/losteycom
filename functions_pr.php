<?php
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

    function mobile_detect()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $ipod = strpos($user_agent,"iPod");
        $iphone = strpos($user_agent,"iPhone");
        $android = strpos($user_agent,"Android");
        $symb = strpos($user_agent,"Symbian");
        $winphone = strpos($user_agent,"WindowsPhone");
        $wp7 = strpos($user_agent,"WP7");
        $wp8 = strpos($user_agent,"WP8");
        $operam = strpos($user_agent,"Opera M");
        $palm = strpos($user_agent,"webOS");
        $berry = strpos($user_agent,"BlackBerry");
        $mobile = strpos($user_agent,"Mobile");
        $htc = strpos($user_agent,"HTC_");
        $fennec = strpos($user_agent,"Fennec/");
        
        if ($ipod || $iphone || $android || $symb || $winphone || $wp7 || $wp8 || $operam || $palm || $berry || $mobile || $htc || $fennec) 
        {
            return true; 
        } 
        else
        {
            return false; 
        }
    }
    
?>