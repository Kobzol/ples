<?php
require(dirname(__FILENAME__)."/db_connect.php");

$shallpass = true;
$time = time();
$rezervace = 0;
$ip = $_SERVER['REMOTE_ADDR'];

if ($shallpass)
{
$query = $db->query("SELECT id,Stul,Potvrzeno,Zarezervovano,selTime,Validated,IP From Ples");
$idimg = 309;
$vybrano = false;
$vybraneSedacky = explode(',',$_GET['selected']);
$query->data_seek(308);
if (isset($_GET['selected']))
{
 while ($row = $query->fetch_row())
 {
  $id = $row[0];
  if ($row[2] == "1") // POTVRZENO
   {
    if ($row[5] == "1") // VALIDATED
    { $src = "p2"; $rezervace++; }
    else { $src = "p4"; }   
   }
  else if (($row[3] == "1") && ((intval($row[4])+15)<$time)) // ZAREZERVOVANO, SELTIME
   {
    ResetSedacky($id);
    if ($row[0] == $sed1 || $row[0] == $sed2)
    { $src = "p5"; }
    else
    $src = "p1";
   }
  else if ($row[3] == "1") // ZAREZERVOVANO
   {
    for ($i=0;$i<count($vybraneSedacky);$i++)
    {
     if ($vybraneSedacky[$i] != "")
     {
      if ( ($vybraneSedacky[$i] == intval($id)) && ($row[6] == $ip) ) // IP
      { $vybrano = true; break; }
     } 
    }
    if ($vybrano)
    { $src = "p3"; }
    else if (!$vybrano)
    { $src = "p4"; }
    $vybrano = false;
   }
  else if ($row[3]== "0") // ZAREZERVOVANO
   {
    if ($row[0] == $sed1 || $row[0] == $sed2)
    { $src = "p5"; }
    else
    $src = "p1";
   }
  echo "<img src='".$src.".png' class='".$src." sedacka' id='s".$idimg."' alt='' title='Sedačka ".$idimg.", Stůl ".$row[1]."' onclick=\"Klik(".$idimg.");\" style='z-index:20;' />"; // STUL
  $idimg++;
 } echo "<div id='klubCount' class='blue'>Klub<br /><span class='grey'>".(103-$rezervace)." volných míst</span></div>";
}
}