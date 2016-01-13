<?php
if (isset($_POST['jmeno']) && isset($_POST['prijmeni']) && isset($_POST['email']) && $_POST['price'] && $_POST['platba'] && $_POST['selected'] && $_POST['code'] && $_POST['stoly'])
{
require(dirname(__FILENAME__)."/db_connect.php");

$jmeno = $_POST['jmeno'];
$prijmeni = $_POST['prijmeni'];
$cena = $_POST['price'];
$platba = $_POST['platba'];
$email = $_POST['email'];
$vybraneSedacky = explode(',',$_POST['selected']);
$check = 0;
$register = true;
$ip = $_SERVER['REMOTE_ADDR'];
$pocetSedacek = 0;
$stoly = $_POST['stoly'];
$sedacky = "";
$code = $_POST['code'];

for ($i = 0;$i < count($vybraneSedacky);$i++)
{
if ($vybraneSedacky[$i] != "")
{ $pocetSedacek++; }
}

if ( ($jmeno!="") && (strlen($jmeno) < 35) )
{ $check++; }
if ( ($prijmeni!="") && (strlen($prijmeni) < 35) )
{ $check++; }
if ( (intval($cena) > 0) && (intval($cena) < 2601) )
{ $check++; }
if ( ($platba == "cash") || ($platba == "ucet") )
{ $check++; }
if ( ($email!="") && (strlen($email) < 45) )
{ $check++; }
if ( ($pocetSedacek < 9) && ($pocetSedacek > 0) )
{ $check++; }
if (strlen($code) == 5)
{ $check++; }

if ($check == 7)
{
 $time = time();
 $datum = date("H:i:s d.m.Y", $time);
 for ($i = 0;$i<count($vybraneSedacky);$i++)
 {
  if ($vybraneSedacky[$i] != "")
  {
   $sedacka = $vybraneSedacky[$i];
   $query = $db->query("SELECT Potvrzeno,IP,Stul FROM Ples WHERE id='$sedacka'");
   $row = $query->fetch_array();
   if ($row[0] == 1)
   { $register = false; break; }
   if ($row[1] != $ip)
   { $register = false; break;}
  }
 }

 if ($register == true)
 { 
   for ($u = 0;$u<count($vybraneSedacky);$u++)
   {
    if ($vybraneSedacky[$u] != "")
    {
     $sedacka = $vybraneSedacky[$u];
     $sedacky .= $sedacka.", ";
     $db->query("UPDATE Ples SET Potvrzeno=1,Zarezervovano=1,Jmeno='$jmeno',Prijmeni='$prijmeni',Cena='$cena',Email='$email',Code='$code',valDatum='$datum',valTime='$time',Platba='$platba',IP='$ip' WHERE id='$sedacka'");
    }
   }

   $sedackyTrim = substr($sedacky,0,strlen($sedacky)-2);
   $db->query("INSERT INTO PlesOrders(Sedacky,Stoly,Code,Jmeno,Prijmeni,Email,Cena,Platba,valDatum,IP) 
   VALUES('$sedackyTrim','$stoly','$code','$jmeno','$prijmeni','$email','$cena','$platba','$datum','$ip')");  
   $ipCheck = $db->query("SELECT * FROM PlesVisitors");
   $zapsat = true;
   while ($row = $ipCheck->fetch_array())
   {
    if ($row['IP'] == $ip)
    {
     $zapsat = false;
     $db->query("UPDATE PlesVisitors SET Time='$time' WHERE IP='$ip'");
     break; 
    }
   }
   if ($zapsat)
   {
    $db->query("INSERT INTO PlesVisitors(IP,Time) VALUES('$ip','$time')");
   }
   
   sendMail($email,$code);
 }
 else Fail();
 }
else { Fail(); }
}

function Fail()
{
  exit("Vyskytl se problém s rezervací. Zkuste to prosím později.");
}

function sendMail($email,$code)
{
$verify = $code;
$random = mt_rand(0,3);
switch($random){
case 0:
$verify = $verify - 7;
break;
case 1:
$verify = $verify + 8;
break;
case 2:
$verify = $verify - 15;
break;
case 3:
$verify = $verify + 27;
break;
default:
break;
}
echo "Děkujeme Vám za Vaši rezervaci. Na Váš e-mail jsme odeslali instrukce, jak Vaši rezervaci potvrdit.<br />
<b>Rezervaci musíte potvrdit do 2 hodin, jinak bude zrušena.</b><br />
Podívejte se prosím i do spamu, pokud vám e-mail nedojde do 30 minut, kontaktujte nás prosím na <b>czech-it@czech-it.eu</b><br /><br />
Váš Czech-it.<br /><br />";

$zprava = "<i>Toto je automaticky vygenerovaný e-mail, neodpovídejte na něj.</i><br /><br/>";
$zprava .= "Pro potvrzení Vaší rezervace prosím klikněte na <a href='http://ples.czech-it.eu/rezervace/validate/?code=".$code."&verify=".$verify."'>tento odkaz</a>.<br />";
$zprava .= "Pokud Vám nejde kliknout na odkaz, navštivte tuto adresu: http://ples.czech-it.eu/rezervace/validate/?code=".$code."&verify=".$verify;
$zprava .= "<br /><br /> <b>Czech-it, z. s.</b><br />
  Františka Hajdy 1429/34, Ostrava-Hrabùvka 700 30, Česká republika<br />
  IČO: 03604799<br />
  +420 724 157 364<br />
  <b>czech-it@czech-it.eu</b><br />
  <a href='http://www.czech-it.eu/'>www.czech-it.eu</a>";

$headers = "From: czech-it@czech-it.eu\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=utf-8\r\n";
  $subjec = "Potvrzení Vaší rezervace";
  $subject="=?UTF-8?B?".base64_encode($subjec)."?=\n";

mail($email, $subject, $zprava, $headers);
}