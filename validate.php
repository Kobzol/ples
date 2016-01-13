<?php
require(dirname(__FILENAME__)."/../db_connect.php");

if (isset($_GET['code']) && isset($_GET['verify']))
{
$code = $_GET['code'];
$verify = $_GET['verify'];
if ( ($verify == $code-7) || ($verify == $code+8) || ($verify == $code-15) || ($verify == $code+27) )
{
$pocetlistku = "";
$pocetspatnych = "";
$query = $db->query("SELECT Validated,id,Zarezervovano FROM Ples WHERE Code='$code'");

while ($row = $query->fetch_array())
{
 if ($row[0]==0 && $row[2]==1)
 { $pocetlistku .= $row[1].", "; }
 else if ($row[0]==1)
 { $pocetspatnych .= $row[1].", "; }
}

$db->query("UPDATE Ples SET Validated='1' WHERE Code='$code' AND Zarezervovano='1'");
$db->query("UPDATE PlesOrders SET Validated='1' WHERE Code='$code'");

if ($pocetlistku != "")
{
echo "Byly potvrzeny tyto sedačky: ".(substr($pocetlistku,0,strlen($pocetlistku)-2))."<br />";
echo "Na váš e-mail byly zaslány instrukce, jak rezervaci zaplatit.<br />
Pokud by Vám tento e-mail do hodiny nepřišel, kontaktuje prosím naši technickou podporu na <b>czech-it@czech-it.eu</b><br /><br />
Váš Czech-it.";
$query2 = $db->query("SELECT * FROM PlesOrders WHERE Code='$code'");
$row = $query2->fetch_array();
$email = $row['Email'];
$cena = $row['Cena'];
$platba = $row['Platba'];
$stoly = $row['Stoly'];

$datum_zaplaceni = "27. 3. 2014";//date('j.n.', strtotime('+ 7 days'));

if ($platba == "cash")
{
 $zpusob = "<b>hotově</b>. Částku <b>".$cena." Kč</b> musíte zaplatit do <b>" . $datum_zaplaceni . " v atriu Gymnázia Ostrava-Hrabůvka p.o., Františka Hajdy 1429/34.</b><br />
 Zaplatit je možné od pondělí do pátku v čase <b>10:20 - 10:40</b> u Anny Hruzíkové nebo Gabriely Holubové (7B8), případně u záskoku. Pro svou identifikaci si připravte číslo své objednávky <b>(".$code.").</b>";
}
else if ($platba == "ucet")
{
 $zpusob = "<b>převodem</b>. Částku <b>".$cena." Kč</b> musíte zaplatit do <b>" . $datum_zaplaceni . "</b> na účet číslo <b>1284747046/3030</b>.<br />
 Pozdější platby nebudou akceptovány a budou navráceny na Váš účet. Variabilní symbol pro Vaši platbu je <b>".$code."</b>, do poznámky k platbě napište Vaše jméno a příjmení.<br />
 Bez zadání těchto údajů nebudeme schopni Vaši platbu identifikovat. ";
}
$zprava = "<i>Toto je automaticky vygenerovaný e-mail, neodpovídejte na něj.</i><br /><br/>";
$zprava .= "Děkujeme Vám za provedení rezervace.<br />";
$zprava .= "Vybrali jste si platbu ".$zpusob."<br /><br />";
$zprava .= "Zarezervované stoly: <b>".$stoly."</b>";

$zprava .= "<br /><br /> <b>Czech-it, z. s.</b><br />
  Františka Hajdy 1429/34, Ostrava-Hrabùvka 700 30, Česká republika<br />
  IČO: 03604799<br />
  +420 724 157 364<br />
  <b>czech-it@czech-it.eu</b><br />
  <a href='http://www.czech-it.eu/'>www.czech-it.eu</a>";

$headers = "From: czech-it@czech-it.eu\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=utf-8\r\n";
  $subjec = "Detaily Vaší rezervace";
  $subject="=?UTF-8?B?".base64_encode($subjec)."?=\n";

mail($email, $subject, $zprava, $headers);
}
if ($pocetspatnych != "")
{echo "Tyto lístky již byly jednou potvrzeny: ".(substr($pocetspatnych,0,strlen($pocetspatnych)-2))."<br /><br />";}
if ($pocetlistku == "" && $pocetspatnych == "")
{ echo "Zadaný kód nenalezen"; }

}
else echo "Špatně zadaný kód.";
}
else exit("Nezadali jste požadované údaje.");