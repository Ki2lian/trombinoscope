<?php session_start();
?>
<?php
if (isset($_SESSION["id"]) && $_SESSION["id"] == 1) {
	include("config/function.php");
	$nom = $_SESSION["nom"];
	$prenom = $_SESSION["prenom"];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Administration</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>
<header>
  <?php include "includes/menunav.php" ?>
</header>
<form method="post">
	<input type="number" name="nombre" required="required" aria-required="true" placeholder="Nombre de comptes à générer" style="width: 12%;" />
	<input type="submit" name="form-generate-account" value="Générer"/>
</form>
<?php
if (isset($_POST["form-generate-account"])) {
	$nombre = intval($_POST["nombre"]);
	writeLogs("logs/general.log", "$nom $prenom;a généré $nombre compte(s).");
	genereAccount("db.csv", $nombre);
}
if (isset($erreur)) {
	echo "<font color='#eb2f06' style=\"font-weight: bold; font-size: 16px;\">". $erreur . "</font>\n";
}
?>






<script language="JavaScript1.2">
 
 
function setcountdown(theyear,themonth,theday,thehour,themin,thesec){
yr=theyear;mo=themonth;da=theday;hr=thehour;min=themin;sec=thesec
}
 
////////// CONFIGUREZ LE COMPTEUR CI-DESSOUS //////////////////
 
// 1°) Configurez la date dans le futur dans le format ANNEE, MOIS, JOUR, HEURES sur 24h (0=minuit,23=11pm), MINUTES, SECONDES
setcountdown(2020,05,10,01,23,59)
 
// 2°) Changez les deux textes ci-dessous. Le premier pour annoncer l'évènement, le second qui s'affichera à la fin du compte à rebours.
var occasion=" la fin du projet"
var message_on_occasion="C'est aujourd'hui la date limite !"
 
// 3°) Configurez ci-dessous 5 variables pour la largeur, hauteur, la couleur de l'arrière plan, et le style du texte du champ
var countdownwidth='640px' // ou une valeur en % comme var countdownwidth='95%'
var countdownheight='35px'
var countdownbgcolor='#FFEBCD' // ou une couleur en texte comme : lightyellow
var opentags='<font face="Verdana"><small>'
var closetags='</small></font>'
 
////////// NE RIEN EDITER CI-DESSOUS //////////////////
 
var montharray=new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec")
var crosscount=''
 
function start_countdown(){
if (document.layers)
document.countdownnsmain.visibility="show"
else if (document.all||document.getElementById)
crosscount=document.getElementById&&!document.all?document.getElementById("countdownie") : countdownie
countdown()
}
 
if (document.all||document.getElementById)
document.write('<span id="countdownie" style="width:'+countdownwidth+'; background-color:'+countdownbgcolor+'"></span>')
 
window.onload=start_countdown
 
 
function countdown(){
var today=new Date()
var todayy=today.getYear()
if (todayy < 1000)
todayy+=1900
var todaym=today.getMonth()
var todayd=today.getDate()
var todayh=today.getHours()
var todaymin=today.getMinutes()
var todaysec=today.getSeconds()
var todaystring=montharray[todaym]+" "+todayd+", "+todayy+" "+todayh+":"+todaymin+":"+todaysec
futurestring=montharray[mo-1]+" "+da+", "+yr+" "+hr+":"+min+":"+sec
dd=Date.parse(futurestring)-Date.parse(todaystring)
dday=Math.floor(dd/(60*60*1000*24)*1)
dhour=Math.floor((dd%(60*60*1000*24))/(60*60*1000)*1)
dmin=Math.floor(((dd%(60*60*1000*24))%(60*60*1000))/(60*1000)*1)
dsec=Math.floor((((dd%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1)
//if on day of occasion
if(dday<=0&&dhour<=0&&dmin<=0&&dsec<=1&&todayd==da){
if (document.layers){
document.countdownnsmain.document.countdownnssub.document.write(opentags+message_on_occasion+closetags)
document.countdownnsmain.document.countdownnssub.document.close()
}
else if (document.all||document.getElementById)
crosscount.innerHTML=opentags+message_on_occasion+closetags
return
}
//if passed day of occasion
else if (dday<=-1){
if (document.layers){
document.countdownnsmain.document.countdownnssub.document.write(opentags+"L'évènement est déjà arrivé ! "+closetags)
document.countdownnsmain.document.countdownnssub.document.close()
}
else if (document.all||document.getElementById)
crosscount.innerHTML=opentags+"L'évènement est déjà arrivé ! "+closetags
return
}
//else, if not yet
else{
if (document.layers){
document.countdownnsmain.document.countdownnssub.document.write("Il reste "+opentags+dday+ " jours, "+dhour+" heures, "+dmin+" minutes, et "+dsec+" secondes avant "+occasion+closetags)
document.countdownnsmain.document.countdownnssub.document.close()
}
else if (document.all||document.getElementById)
crosscount.innerHTML="Il reste "+opentags+dday+ " jours, "+dhour+" heures, "+dmin+" minutes, et "+dsec+" secondes avant "+occasion+closetags
}
setTimeout("countdown()",1000)
}
</script><!-- FIN DU SCRIPT COMPTE A REBOURS -->
</body>
</html>
<?php
}else{
	header("location: index.php");
}


?>