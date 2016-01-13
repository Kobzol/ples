<?php
require(dirname(__FILENAME__)."/db_connect.php");

$selected = explode(',',$_GET['selected']);
$stoly = array();
for ($i = 0; $i<count($selected); $i++)
{
 if ($selected[$i] != "")
 {
  $row = $selected[$i];
  $query = $db->query("SELECT Stul FROM Ples WHERE id='$row'");
  $stul = $query->fetch_array();
  if (!in_array($stul[0],$stoly))
  { $stoly[] = $stul[0]; }
 }
}
$output = "";
sort($stoly);
for ($i = 0; $i < count($stoly); $i++)
{
$output .= $stoly[$i].", ";
}
echo substr($output,0,strlen($output)-2);
?>