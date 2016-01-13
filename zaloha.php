<?php
require(dirname(__FILENAME__)."/db_connect.php");

$db->query("DROP TABLE Zaloha");
$db->query("CREATE TABLE Zaloha LIKE Ples");
$db->query("INSERT INTO Zaloha SELECT * FROM Ples");

$db->query("DROP TABLE ZalohaOrders");
$db->query("CREATE TABLE ZalohaOrders LIKE PlesOrders");
$db->query("INSERT INTO ZalohaOrders SELECT * FROM PlesOrders");