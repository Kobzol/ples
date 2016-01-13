<?php
require(dirname(__FILENAME__)."/db_connect.php");

/*$beginTable = 231;
$beginSeat = 399;
$seats = 8;
$tables = 6;
$direction = -1;

for ($i = 0; $i < $tables; $i++)
{
  $db->query("UPDATE Ples SET Stul='".($beginTable + $i * $direction)."' WHERE id >= '".$beginSeat."' AND id <= '".($beginSeat + $seats - 1)."'");
  $beginSeat += $seats;  
}

print_r($db);*/