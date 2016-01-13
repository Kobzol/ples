<?php session_start();
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
xmlns:fb="http://www.facebook.com/2008/fbml"
xml:lang="cs" lang="cs">
 <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Language" content="cs" />
  <meta name="robots" content="index,follow" />
  <meta name="description" content="Czech-it školní ples" />
  <title>PLES 2014</title>
  <link rel="stylesheet" type="text/css" href="../reset.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="../dropkick.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="../ples.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="../zidle.css" media="screen" />
  <link rel="shortcut icon" href="../../../style/img/favicon.ico" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script src="../jquery.dropkick-1.0.0.js" type="text/javascript"></script>
  <script src="../ples.js" type="text/javascript"></script>
 </head>
 <body class="web_default validace-obsah" id="body">
 <h1 style="color:#000000;">Potvrzení rezervace</h1>
 <div id="page">

  <div id="content" style="color:#000000; text-align:center;">
  <?php
  require "../validate.php";
  ?>
  </div>
 </div> 
</body>
</html>							