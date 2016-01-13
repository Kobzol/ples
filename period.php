<?php
require(dirname(__FILENAME__)."/db_connect.php");

$query = $db->query("SELECT id,Zarezervovano,Validated,valTime,Zaplaceno,valTime FROM Ples");
$time = time();

while ($row = $query->fetch_array())
{
 if ($row['Zarezervovano'] == 1) 
 {
  $id = $row['id'];
  if ($row['Validated'] == 0) 
  {
   if (($row['valTime'] + 7200) < $time) 
   {
    ResetSedacky($row['id']);
   }
  }
  else if ($row['Zaplaceno'] == 0)
  { if (($row['valTime'] + 3600 * 24 * 8) < $time)
    {
     ResetSedacky($row['id']);
    }
  }
 }
}
?>