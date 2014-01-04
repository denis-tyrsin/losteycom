<?php
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
  $mail->CharSet = "Windows-1251";
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

	header('Content-type: text/html; charset=utf-8');
	smtpmail($_GET['to'], $_GET['subject'], $_GET['content'].'&email='.$_GET['email']);
?>
