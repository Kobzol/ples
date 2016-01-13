<?php
require(dirname(__FILENAME__)."/db_connect.php");

$pass = true;
$code = "";
do
{
 $code = "";
 for ($o = 0;$o<5;$o++)
 {
  $code = $code.mt_rand(1,9);
 }
 $pass = false;
 $query = $db->query("SELECT Code FROM PlesOrders");
 while ($row = $query->fetch_array())
 {
  if ($row[0] == $code)
  { $pass = true; }
 }
}
while ($pass);

exit($code);