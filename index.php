<?php session_start();
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
xmlns:fb="http://www.facebook.com/2008/fbml"
xml:lang="cs" lang="cs">
 <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Language" content="cs" />
  <meta name="robots" content="index,follow" />
  <meta name="description" content="Czech-it školní ples" />
  <title>PLES 2014</title>
  <link rel="stylesheet" type="text/css" href="reset.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="font/font.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="dropkick.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="ples.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="zidle.css" media="screen" />
  <link rel="shortcut icon" href="../../style/img/favicon.ico" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script src="jquery.dropkick-1.0.0.js" type="text/javascript"></script>
  <script src="ples.js" type="text/javascript"></script>
 <noscript>
     <div id="wrapper"></div>
     <div id="nojs">Pro zobrazení stránky je nutné mít povolený JavaScript.<a href="https://www.google.com/support/adsense/bin/answer.py?answer=12654">Zapněte si ho prosím.</a></div>
 </noscript>
 </head>
 <body class="web_default" id="body">
 <div class="logo"></div>
 <div id="page">
  <div id="podminkyDiv">
      1. Pro provedení rezervace a vstup na ples musíte <b>být starší 15 let</b>.<br /><br />
      2. Rezervace musí být zaplacena <b>nejpozději do 7 dnů</b>, buď platbou převodem na účet <b>1284747046/3030</b> nebo hotově v atriu Gymnázia Ostrava Hrabůvka p.o., Františka Hajdy 1429/34
      od pondělí do pátku, <b>vždy o velké přestávce (10:20-10:40) u Anny Hruzíkové nebo Gabriely Holubové</b> (7B8), případně u záskoku. <b>Pokud neobdržíme Vaši platbu do 7 dnů ode dne rezervace,
          Vaše rezervace bude zrušena!</b><br /><br />
      3. Pokud si zvolíte platbu převodem, jako variabilní symbol použijte <b>specifické číslo rezervace</b>,
      který Vám přijde e-mailem po potvrzení rezervace. Dále do poznámky k transakci napište své jméno a příjmení.
      Po zaplacení si své lístky můžete vyzvednout 28. 3. 2015 od 18:00 v DK Akord nebo v atriu Gymnázia Ostrava Hrabůvka p.o., Františka Hajdy 1429/34 od pondělí do pátku,
      vždy o <b>velké přestávce (10:20-10:40) u Anny Hruzíkové nebo Gabriely Holubové</b> (7B8), případně u záskoku.<br /><br />
      4. Plesu se lze zúčastnit <b>pouze ve společenském oděvu.</b><br /><br />
      5. Czech-it si vyhrazuje právo zrušit rezervace, jejichž údaje mají rasistický, vulgární nebo sexuální podtext, jsou smyšlené nebo byly zadány podvodným způsobem.
  </div>
  <div id="content">

  <div id="details">
  <img src="images/vybrat_dalsi_mista.png" class='orderbuton' title="Vybrat další místa" id="dalsiMista" alt="" onclick="hideDetails();" />
  <img src="images/dokoncit_rezervaci.png" class='orderbuton' title="Dokončit rezervaci" id="finishOrder" alt="" onclick="validate();" />
  <div class="detailCode grey">Číslo rezervace: <span class="bold" id="spanCode"></span></div><br />
  <div class="detailStoly grey">Vybrané stoly: <span class="bold" id="spanStoly"></span></div><br />
  <div class="detailPocet grey">Počet sedadel: <span class="bold" id="spanPocet"></span></div><br />
  <div class="detailCena grey">Konečná cena: <span class="bold" id="spanCena"></span></div><br />
  <input class="sbInput" id="jmeno" maxlength="30" type="text" value="Vaše jméno ..." /><span id="errorJmeno" class="error">Zadejte prosím své jméno.</span><br />
  <input class="sbInput" id="prijmeni" maxlength="30" type="text" value="Vaše příjmení ..." /><span id="errorPrijmeni" class="error">Zadejte prosím své příjmení.</span><br />
  <input class="sbInput" id="email" maxlength="40" type="text" value="Váš e-mail ..." /><span id="errorEmail" class="error">Zadejte prosím správný e-mail.</span><br />
  <select id="platba"><option value="ucet">Platba převodem</option><option value="cash">Hotově</option></select><br />
  <img id="acceptConfirm" src="p1.png" class="p1A" onclick="accept();" />
  <div id="podminkyAccept" class="grey"> Souhlasím s <span style="text-decoration: underline;cursor: pointer;" onmouseover="infoChange('on');" onmouseout="infoChange();">podmínkami rezervace</span>.</div>
  </div>  
  
  <div id="plesContainer">
  <img src="images/stoly-hlsal.png" id="hlavniSal-stoly" />
  <img src="images/klub_novy.png" id="klub-stoly" />
  <div id="hlavniSal-podium" class="ples-popisek grey">(pódium)</div>
  <div id="hlavniSal-parket" class="ples-popisek grey">(parket)</div>
  <div id="ples"></div>
  <div id="klub"></div>
  <div id="infoContainer">
   <div id="infoDiv">
    <div id="errorDiv" style="color:#FF0000; top: -25px; width:450px; position:absolute; left:0; display:none;"></div>
    <img id="loading" style="display:none;position: absolute;top:61px;left:120px;" src="images/load3.gif" alt="Načítání..." title="Načítání..." width="32" height="32" />   
    <div style="position: absolute;left:0;top: 50px; line-height: 20px; font-size: 14px;" class="grey">
    Celková cena: <span id="cena" class="blue">0 Kč</span><br />
    Počet sedadel: <span id="sedadla" class="blue">0</span>
    </div>
    
    <img src="images/pokracovat_v_rezervaci.png" class='orderbuton' id="startOrder" onclick="showDetails();" title="Pokračovat v rezervaci" alt="Objednat" />
    
    <img src="p1.png" id="sed1" alt="" title="Volná sedačka" />
    <img src="p3.png" id="sed2" alt="" title="Vámi vybraná sedačka" />
    <img src="p4.png" id="sed3" alt="" title="Dočasně zarezervovaná sedačka" />
    <img src="p2.png" id="sed4" alt="" title="Zarezervovaná sedačka" />       
    <div class="sedLabel grey" id="sed1Label">volná sedačka</div>
    <div class="sedLabel grey" id="sed2Label">Vámi vybraná sedačka</div>
    <div class="sedLabel grey" id="sed3Label">dočasně zarezervovaná sedačka</div>
    <div class="sedLabel grey" id="sed4Label">zarezervovaná sedačka</div>
   </div>
  </div>
  </div>
  </div>
 </div>
 <div class='copyright'>© Czech-it - 2015 <span>|</span> Jakub Beránek <span>|<span> grafika Patrik Holešovský</span></div>
</body>
</html>								