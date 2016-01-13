<?php

$db = new mysqli("localhost", "czech-it.eu", "czechittomassindel123", "czech-it_eu");

function ResetSedacky($co)
{
  global $db;

  $db->query("UPDATE Ples Set Potvrzeno=0,Zarezervovano=0,Jmeno='',Prijmeni='',Email='',valTime=0,selTime=0,valDatum=0,Validated=0,Zaplaceno=0,Cena=0,Platba='',Code=0,IP=0 WHERE id='$co'");
}

function ResetVseho()
{
    global $db;

    $db->query("UPDATE Ples Set Potvrzeno=0,Zarezervovano=0,Jmeno='',Prijmeni='',Email='',valTime=0,selTime=0,valDatum=0,Validated=0,Zaplaceno=0,Cena=0,Platba='',Code=0,IP=0");
}