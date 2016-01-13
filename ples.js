$(function() {
 sedacky = 0;
 price = 0;
 klik = true;
 selected = new Array("","","","","","","","");
 stoly = "";
 code = "";
 Spawn('both');
 timerUpdate = setInterval("Update();",10000);
 if (window.location.pathname.indexOf('rezervace') != -1)
 { stopTimer = setTimeout("stop()",(60000*15)); }
 fixedPrice = 280;

 platbaVar = "ucet";
 info = true;
 acceptVar = false;

 $(document).mousemove(function(e) {
    if (info) { 
    if (e.clientY + parseInt($("#podminkyDiv").css('height')) + 17> $(window).height()) {
     $("#podminkyDiv").css('top',e.pageY - (parseInt($("#podminkyDiv").css('height')) + 17));
     $("#podminkyDiv").css('left',e.pageX + 25); }
    else {
     $("#podminkyDiv").css('top',e.pageY + 5);
     $("#podminkyDiv").css('left',e.pageX + 25); }
    }});

 $('#platba').dropkick({
  change: function (value) {
    platbaVar = value;
  }
});

 $("#jmeno").focus(function() {
  if ( $("#jmeno").val() == "Vaše jméno ...")
  { $("#jmeno").val(''); }
 });
 $("#prijmeni").focus(function() {
  if ( $("#prijmeni").val() == "Vaše příjmení ...")
  { $("#prijmeni").val(''); }
 });
 $("#email").focus(function() {
  if ( $("#email").val() == "Váš e-mail ...")
  { $("#email").val(''); }
 });

 $("#jmeno").blur(function() {
  if ( $("#jmeno").val() == "")
  { $("#jmeno").val('Vaše jméno ...'); }
 });
 $("#prijmeni").blur(function() {
  if ( $("#prijmeni").val() == "")
  { $("#prijmeni").val('Vaše příjmení ...'); }
 });
 $("#email").blur(function() {
  if ( $("#email").val() == "")
  { $("#email").val('Váš e-mail ...'); }
 });
 
 $(window).resize(function() {
  if ($(window).width() < 1000)
  {
   $(".plesTitle").hide();
   $("#infoContainer").css('position','absolute');
   $("#infoContainer").css('right','-70px');
   $("#infoContainer").css('top','-220px'); 
  }
  else { 
   $(".plesTitle").show();
   $("#infoContainer").css('position','fixed');
   $("#infoContainer").css('right','0');
   $("#infoContainer").css('top','35%');
  }
  if ($(window).width() < 1279)
  {
   $("#footer").hide();
   $(".home").hide();
  }
  else 
  {
   $("#footer").show();
   $(".home").show();
  }
 });

});

function accept() {
  if ($("#acceptConfirm").attr('class') == "p1A")
  {acceptVar = true; $("#acceptConfirm").attr('class','p2A'); $("#acceptConfirm").attr('src','p2.png'); }
  else
  {acceptVar = false; $("#acceptConfirm").attr('class','p1A'); $("#acceptConfirm").attr('src','p1.png'); }
}

function Order() {
if (acceptVar)
{
 $.post("finalize.php",
 "jmeno="+$("#jmeno").val()+"&prijmeni="+$("#prijmeni").val()+"&email="+$("#email").val()+"&price="+price+"&selected="+selected+"&platba="+platbaVar+"&stoly="+stoly+"&code="+code,
  function(text) {
   $("#details").html('<div class="order-final">' + text + '</div>');
 });
}
else alert('Pro dokončení rezervace musíte souhlasit s podmínkami rezervace.');
}

function Klik(co) {
if (klik) {
klik = false;
clearTimeout(stopTimer);
stopTimer = setTimeout("stop()",(60000*15));
if ( $("#s"+co).hasClass("p1") ) 
{ $("#loading").show(); 
  if (sedacky < 8)
  { 
   $.post("check.php","action=select&klik="+co,function(text) 
   {
   if (text=="true")
   {
    Select(co);
   }
   else if (text=="false")
   {
    Fail(co);
   }
  });}
  else { $("#loading").hide(); showError('full'); }
}

else if ( ($("#s"+co).hasClass("p2")) || ($("#s"+co).hasClass("p4") ))
 {
  Fail(co);
 }

else if ($("#s"+co).hasClass("p3"))
 {$("#loading").show(); 
 $.post("check.php","action=deselect&klik="+co,function(text) 
 {
  if (text=="true")
  {
   Deselect(co);
  }
  else if (text=="false")
  {
   Fail(co);
  }
 });
 }setTimeout("klik = true",300);}
}

function Deselect(co) {
Spocti(0,co);
for (i = 0;i < selected.length;i++)
 {
  if (selected[i]==co)
  { selected[i]=""; break; }
 }
Spawn(co);
$("#loading").hide();
klik = true;
}

function Select(co) {
Spocti(1,co);
var space = false;
for (i = 0;i < selected.length;i++)
 {
  if (selected[i]=="")
  { selected[i]=co; space = true; break; }
 }
Spawn(co);
$("#loading").hide();
if (!space)
{showError('full');}
}

function Fail(co) {
 $("#loading").hide();
 showError('reserved');
 Spawn(co);
}

function Spocti(kolik,co) {
if (kolik==0)
 {
  sedacky--;
  if (sedacky < 0)
  { sedacky = 0; }
  price = sedacky * fixedPrice;
  $("#cena").html(price+" Kč");
  $("#sedadla").html(sedacky); 
 }
else if (kolik==1)
 {
  sedacky++;
  price = sedacky * fixedPrice;
  $("#cena").html(price+" Kč");
  $("#sedadla").html(sedacky);
 }
}

function stop() {
  clearInterval(timerUpdate);
  $("#content").html('');  
  alert('Platnost stránky vypršela a nyní bude obnovena.');
  location.reload();
}

function showDetails() {
  if (sedacky < 1)
  { showError('empty'); return; }
  $("#loading").show();
  klik = false;
  $.get("tables.php","selected="+selected,function(text) {
   stoly = text;
   $("#spanStoly").html(stoly);
  });
  if (code == "") 
  {
   $.get("makecode.php","action=getcode",function(text) {
    code = text;
    $("#spanCode").html(code);
    $("#spanPocet").html(sedacky);
    $("#spanCena").html(price+" ,- Kč");  
    $("#plesContainer").hide();
    $("#details").show();
   });
  }
  else
  {
   $("#spanPocet").html(sedacky);
   $("#spanCena").html(price+" ,- Kč");  
   $("#plesContainer").hide();
   $("#details").show();
  }
}

function hideDetails() {
  klik = true;
  $("#loading").hide();
  $("#details").hide();
  Spawn('both');
  $("#plesContainer").show();
}

function errorDetails(co) {
  $("#errorJmeno").hide();
  $("#errorPrijmeni").hide();
  $("#errorEmail").hide();
  $(".sbInput").css('border','1px solid #330000');
  if (co == 'jmeno')
  { $("#errorJmeno").show(); $("#jmeno").focus(); $("#jmeno").css('border','1px solid #FF0000'); }
  else if (co == 'prijmeni')
  { $("#errorPrijmeni").show(); $("#prijmeni").focus(); $("#prijmeni").css('border','1px solid #FF0000'); }
  else if (co == 'email')
  { $("#errorEmail").show(); $("#email").focus(); $("#email").css('border','1px solid #FF0000'); }
}

function showError(co) {
  if (co == 'reserved')
  { 
   $("#errorDiv").html('Tato sedačka již byla zarezervována.');
  }
  else if (co == 'full')
  { 
   $("#errorDiv").html('Najednou lze zarezervovat maximálně 8 sedaček.'); 
  }
  else if (co == 'empty')
  { 
   $("#errorDiv").html('Vyberte si prosím alespoň jednu sedačku.'); 
  }
  if ($("#errorDiv").css('display') == 'none')
    { $("#errorDiv").fadeIn(500); }
  else { clearTimeout(hideError); }
  hideError = setTimeout("fadeError();",1500);
}

function fadeError() {
  $("#errorDiv").fadeOut();
}

function Update() {
  $.get("update.php","selected="+selected,function(text) {});
}

function Spawn(co) {
  if (co = "both")
  { $.get("spawn.php","selected="+selected,function(text) {
     $("#ples").html(text);
     $.get("spawnKlub.php","selected="+selected,function(text) {
      $("#klub").html(text);
     });
    });
  }
  else if (co < 265)
  {
   $.get("spawn.php","selected="+selected,function(text) {
    $("#ples").html(text);
   });
  }
  else
  {
   $.get("spawnKlub.php","selected="+selected,function(text) {
    $("#klub").html(text);
   });
  } 
}

function infoChange(co)
{
if (co == "on")
{info = true; $("#podminkyDiv").show();}
else { info = false; $("#podminkyDiv").hide(); };
}

function validate() {
if ( ($("#jmeno").val()=="") || ($("#jmeno").val() == "Vaše jméno ...") ) // Kontrola vyplnění jména
{ errorDetails('jmeno'); return false; }
else if ( ($("#prijmeni").val()=="") || ($("#prijmeni").val() == "Vaše příjmení ...") ) // Kontrola vyplnění příjmení
{ errorDetails('prijmeni'); return false; }
else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($("#email").val()))) // Kontrola vyplnění emailu
{ errorDetails('email'); return false; }
else Order();
}

$(function() {
    var menupos = wheight = window.innerWidth;
    $('.logo').css("left", (menupos - 556) / 2 - 200);
    $('#infoContainer').css("right", (menupos - 556) / 2 - 260);

    var orderButton = $('.orderbuton');

    orderButton.mouseenter(function(){
    $(this).animate({opacity: 1}, 250, "swing");
  });
    orderButton.mouseleave(function(){
    $(this).animate({opacity: 0.8}, 250, "swing");
  });
});

$(window).scroll(function(){
  var menupos = wheight = window.innerWidth;
  $('.logo').css("left", (menupos-556)/2 - 200);
  $('#infoContainer').css("right", (menupos-556)/2 - 260);
});