<?
$id_get = "";
$text_get = "Lostey!";
$id_get = $_GET['n'];
//$text_get = $_GET['text'];
?>
<!DOCTYPE html>
<head>
<title><? echo $text_get; ?></title>
<meta http-equiv="content-type" content=text/html; charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/style.css"/><!-- media="screen, projection" -->
</head>
<body>
<script type="text/javascript">
(function() {
  if (window.pluso)if (typeof window.pluso.start == "function") return;
  if (window.ifpluso==undefined) { window.ifpluso = 1;
    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
    var h=d[g]('head')[0] || d[g]('body')[0];
    h.appendChild(s);
  }
})();</script>
<div id="plusoid" class="pluso" data-lang="en" data-background="transparent" data-options="big,square,line,horizontal,counter,theme=04" data-services="facebook,twitter,google" data-url="http://gurista-ru.1gb.ru/lostie/?n=<? echo $id_get; ?>" data-title="Lostey" data-description="<? echo $text_get; ?>"></div>
</body>
</html>