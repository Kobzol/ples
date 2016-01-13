<?php
require(dirname(__FILENAME__)."/db_connect.php");

$vybraneSedacky = explode(',',$_GET['selected']);
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];
for ($i=0;$i < count($vybraneSedacky);$i++)
{
 if ($vybraneSedacky[$i] != "")
  {  
   $id = $vybraneSedacky[$i];
   $query = $db->query("SELECT IP From Ples WHERE id='$id'");
   $row = $query->fetch_array();
   if ($ip == $row[0]) 
   {
    $db->query("UPDATE Ples Set selTime='$time' WHERE id='$id'"); 
   }
  }
}

?>