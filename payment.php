<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Czech-it Ples 2015 - potvrzování rezervací</title>
</head>
<body>
<?php

require(dirname(__FILENAME__)."/db_connect.php");

$order = $_POST['order'];
if ($_POST['pass'] === "mameseradi")
{
$db->query("UPDATE PlesOrders SET Zaplaceno='1' WHERE Code='$order'");
$query = $db->query("SELECT Email,Platba FROM PlesOrders WHERE Code='$order'");
$db->query("UPDATE Ples SET Zaplaceno='1' WHERE Code='$order'");

if ($row[2] == "ucet")
{
$code = $row[0];
$email = $row[1];
$zprava = "<i>Toto je automaticky vygenerovaný e-mail, neodpovídejte na něj.</i><br /><br/>";
$zprava .= "Děkujeme vám za provedení platby. Zakoupené lístky si můžete vyzvednout <b>v atriu Gymnázia Ostrava-Hrabůvka </b>od pondělí do pátku v čase <b>10:20 - 10:40</b>
 nebo přímo v DK Akord 28. 3. před začátkem plesu. Pro svou identifikaci si připravte číslo své objednávky <b>(".$order.")</b>.";

$headers = "From: czech-it@czech-it.eu\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=utf-8\r\n";
  $subjec = "Rezervace byla zaplacena";
  $subject="=?UTF-8?B?".base64_encode($subjec)."?=\n";

$zprava .= "<br /><br /> <b>Czech-it, z. s.</b><br />
  Františka Hajdy 1429/34, Ostrava-Hrabùvka 700 30, Česká republika<br />
  IČO: 03604799<br />
  +420 724 157 364<br />
  <b>czech-it@czech-it.eu</b><br />
  <a href='http://www.czech-it.eu/'>www.czech-it.eu</a>";

mail($email,$subject,$zprava,$headers);
exit("Platba zaznamenána - účet.");
}
else exit("Platba zaznamenána - hotově.");
}
else if (isset($_POST['pass']))
{
  exit("Zadali jste špatné heslo.<br />");
}

?>

<form action="" method="POST">
Číslo rezervace: <input type="text" name="order" /><br />
Heslo: <input type="password" name="pass" /><br />
<input type="submit" value="Potvrdit zaplacenou rezervaci" />
</form>

</body>
</html>