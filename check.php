<?php
require(dirname(__FILENAME__)."/db_connect.php");

$sedacky = explode(',',$_POST['selected']);
$klik = $_POST['klik'];
$action = $_POST['action'];
$ip = $_SERVER['REMOTE_ADDR'];
  $query = $db->query("SELECT Zarezervovano,selTime,Potvrzeno FROM Ples WHERE id='$klik'");
  $row = $query->fetch_array();
  $time = time();
if ($action == "select")
 { 
  if ($row[2]==1)
  { echo "false"; }
  else if ($row[0]==0)
  {
   $db->query("UPDATE Ples SET Zarezervovano=1,selTime='$time',IP='$ip' WHERE id='$klik'");
   echo "true";
  }
  else if (($row[0]==1) && (($row[1]+20)<$time))
  {
   ResetSedacky($klik);
   $db->query("UPDATE Ples SET Zarezervovano=1,selTime='$time',IP='$ip' WHERE id='$klik'");
   echo "true";
  }
  else if ($row[0]==1)
  { echo "false"; }
 }
else if ($action=="deselect")
 {
  if ($row[2]==1)
  { echo "false"; }
  else 
  { $db->query("UPDATE Ples SET Zarezervovano=0,selTime=0 WHERE id='$klik'"); echo "true"; }
 }
else if ($action=="delete")
 {
  for ($i = 0; $i < count($sedacky); $i++)
  {
   if ($sedacky[$i] != "")
   {
    $sedacka = $sedacky[$i];
    $queryDel = $db->query("SELECT Validated,IP FROM Ples WHERE id='$sedacka'");
    $rowDel = $queryDel->fetch_array();
    if ($rowDel[0] == 0 && $rowDel[1] == $ip)
    {
     ResetSedacky($sedacka);
    }
   }
  }
 }
?>