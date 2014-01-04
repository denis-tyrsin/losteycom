<?php
	header('Content-type: text/html; charset=utf-8');
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
	function smtpmail($to, $subject, $content, $attach=false)
	{
//$subject = iconv("utf-8", "Windows-1251", $subject);
require_once('config.php');
require_once('class.phpmailer.php');
$mail = new PHPMailer(true);

$mail->IsSMTP();
//echo $to."<br />".$subject."<br />".$content."<br />";
try {
  $mail->Host       = $__smtp['host']; 
  $mail->SMTPDebug  = $__smtp['debug']; 
  $mail->SMTPAuth   = $__smtp['auth'];
  $mail->Mailer = "smtp";
  $mail->Host       = $__smtp['host'];
  $mail->Port       = $__smtp['port']; 
  $mail->Username   = $__smtp['username'];
  $mail->Password   = $__smtp['password'];
  $mail->SMTPSecure   = $__smtp['try_ssl'];
  $mail->AddReplyTo($__smtp['addreply'], $__smtp['username']);
  $mail->AddAddress($to);
  $mail->SetFrom($__smtp['addreply'], $__smtp['username']);
  $mail->AddReplyTo($__smtp['addreply'], $__smtp['username']);
  //$mail->CharSet = "Windows-1251";
    $mail->CharSet = "utf-8";
  $mail->Subject = htmlspecialchars($subject);
  //$mail->Subject = $subject;
  $mail->MsgHTML($content);
  if($attach)  $mail->AddAttachment($attach);
  $mail->Send();
  echo "Message sent8!</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); 
} catch (Exception $e) {
  echo $e->getMessage(); 
}
	}
    $subj=$_GET['subject'];
		//$subj = iconv("utf-8", "cp1251", $subj);
    $cont=$_GET['content'];
    if (!empty($_GET['email'])) {
        $cont=$cont.'&email='.$_GET['email'];
    }
		//$cont = iconv("utf-8", "cp1251", $cont);
	smtpmail($_GET['to'], $subj, $cont);
?>
