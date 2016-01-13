<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
 <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Language" content="cs" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <style>
    table {
      border-collapse: collapse;
      font-size: 14px;
    }
    table td {
      padding: 1px;
      text-align: center;
    }
    table th {
        min-width: 50px;
    }
    form {
      margin-bottom: 10px;
    }
  </style>
  <script type="text/javascript">
     function nastavObjednavky()
     {
        var payment = [];
     
        $(".payment-confirm:checked").each(function()
        {
           payment.push($(this).parent().siblings(".code").text());
        });
        
        $("input[name=payment]").val(payment.join(","));
        
        var received = [];
     
        $(".received-confirm:checked").each(function()
        {
           received.push($(this).parent().siblings(".code").text());
        });
        
        $("input[name=received]").val(received.join(","));
     }
  </script>
</head>

<?php
require(dirname(__FILENAME__)."/db_connect.php");

if ($_POST['pass'] === 'czechit123')
{ 
  ?>
    <form action="" method="POST">
    Heslo: <input type="password" name="pass" required="required" /><br />
    <input type="hidden" name="payment" />
    <input type="hidden" name="received" />
    <input type="submit" value="Odeslat změny" onclick="return nastavObjednavky();" />
    </form>
  <?php
  
  if (isset($_POST['payment']) && $_POST['payment'] !== '')
  { 
    $codes = $db->real_escape_string($_POST['payment']);

    $payments = $db->query("SELECT Code,Email,Platba FROM PlesOrders WHERE Code IN (".$codes.") AND Zaplaceno='0'");

    while ($payment = $payments->fetch_assoc())
    {
      if ($payment['Platba'] === 'ucet')
      {
        posliEmail($payment['Email'], $payment['Code']);
      }
    }
     
    $db->query("UPDATE Ples SET Zaplaceno='1' WHERE Code IN (".$codes.") AND Zaplaceno='0'");
    $db->query("UPDATE PlesOrders SET Zaplaceno='1' WHERE Code IN (".$codes.") AND Zaplaceno='0'");
     
    if ($db->affected_rows)
    {
      echo 'Platby byly zaznamenány.<br />';
    }
  }
  
  if (isset($_POST['received']) && $_POST['received'] !== '')
  { 
    $codes = $db->real_escape_string($_POST['received']);
    $db->query("UPDATE PlesOrders SET Vyzvednuto='1' WHERE Code IN (".$codes.") AND Vyzvednuto='0'");
     
    if ($db->affected_rows)
    {
      echo 'Vyzvednutí bylo zaznamenáno.<br />';
    }
  }
 
  echo '<div class="objednavky-nadpis"><b>Objednávky</b></div>';
  
  $query = $db->query("SELECT Sedacky,Stoly,Code,Jmeno,Prijmeni,Email,Validated,Zaplaceno,Vyzvednuto,Cena,Platba,valDatum FROM PlesOrders");
  
  $time = time();
  $id = 1;
  
  echo '<table border="1">';
  echo '<tr><th>ID</th><th>Sedačky</th><th>Stoly</th><th>Jméno a příjmení</th><th>E-mail</th><th>Potvrzeno e-mailem</th><th>Zaplaceno</th><th>Vyzvednuto</th><th>Cena</th><th>Platba</th><th>Kód</th><th>Datum rezervace - datum vypršení</th><th>Vypršela platnost</th><th>Potvrdit zaplacení</th><th>Potvrdit vyzvednutí</th></tr>';
  
  while ($row = $query->fetch_assoc())
  {
    $invalid = (strtotime($row['valDatum']) + 3600 * 24 * 8) < $time;
  
    echo '<tr>';
    echo '<td>'.($id++).'</td>';
    echo '<td>'.$row['Sedacky'].'</td>';
    echo '<td>'.$row['Stoly'].'</td>';
    echo '<td>'.$row['Jmeno'].' '.$row['Prijmeni'].'</td>';
    echo '<td>'.$row['Email'].'</td>';
    echo '<td>'.($row['Validated'] === '0' ? 'Ne' : 'Ano') .'</td>';
    echo '<td>'.($row['Zaplaceno'] === '0' ? 'Ne' : 'Ano').'</td>';
    echo '<td>'.($row['Vyzvednuto'] === '0' ? 'Ne' : 'Ano').'</td>';
    echo '<td>'.$row['Cena'].' Kč</td>';
    echo '<td>'.($row['Platba'] === "cash" ? "hotově" : "převodem").'</td>';
    echo '<td class="code">'.$row['Code'].'</td>';
    echo '<td>'.$row['valDatum'].' - '.(date("H:i:s d.m.Y", strtotime($row['valDatum']) + 3600 * 24 * 8)).'.</td>';
    echo '<td>'.($invalid ? "Ano" : "Ne").'</td>';
    echo '<td>'.(($row['Zaplaceno'] === '0' && !$invalid) ? '<input type="checkbox" class="payment-confirm" />' : '').'</td>';
    echo '<td>'.($row['Vyzvednuto'] === '0' ? '<input type="checkbox" class="received-confirm" />' : '').'</td>';
    echo '</tr>';
  }
  
  echo '</table>';
  
  if (isset($_POST['ord']) && $_POST['ord'] === "on")
  {
    echo '<br /><b>Sedačky</b><br />';
    echo 'ID sedačky - Stůl - Zarezervováno - Potvrzeno - Jméno - Příjmení - E-mail - Datum rezervace - Potvrzeno e-mailem - Cena - Platba - Zaplaceno - Kód<br />';
    
    $query = $db->query("SELECT * FROM Ples");
    while ($row = $query->fetch_array())
    {
      echo $row[0].". &nbsp&nbsp".$row[1]." ".$row[2]." ".$row[3]." ".$row[4]." ".$row[5]." ".$row[6]." ".$row[7]." ".$row[10]." ".$row[11]." ".$row[12]." ".$row[13]." ".$row[14]."<br>";
    }   
  }
}
else
{
  ?>
    <form action="" method="POST">
    Heslo: <input type="password" name="pass" required="required" /><br />
    Zobrazit i jednotlivé sedačky: <input type="checkbox" name="ord" /><br />
    <input type="submit" value="Zobrazit údaje o objednávkách" />
    </form>
  <?php
}

function posliEmail($email, $code)
{
    $zprava = "<i>Toto je automaticky vygenerovaný e-mail, neodpovídejte na něj.</i><br /><br/>";
    $zprava .= "Děkujeme vám za provedení platby. Zakoupené lístky si můžete vyzvednout <b>v atriu Gymnázia Ostrava-Hrabůvka </b>od pondělí do pátku v čase <b>10:20 - 10:40</b>
 nebo přímo v DK Akord 28. 3. před začátkem plesu. Pro svou identifikaci si připravte číslo své objednávky <b>(".$code.")</b>.";

    $headers = "From: czech-it@czech-it.eu\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $subjec = "Rezervace byla zaplacena";
    $subject="=?UTF-8?B?".base64_encode($subjec)."?=\n";

    $zprava .= "<br /><br /> <b>Czech-it, z. s.</b><br />
  Františka Hajdy 1429/34, Ostrava-Hrabùvka 700 30, Česká republika<br />
  IČO: 03604799<br />
  +420 724 157 364<br />
  <b>czech-it@czech-it.eu</b><br />
  <a href='http://www.czech-it.eu/'>www.czech-it.eu</a>";
    
    mail($email,$subject,$zprava,$headers);
}
?>
</html>