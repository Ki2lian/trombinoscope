<?php

if (!isset($_SESSION["id"]) && isset($_GET["prenom"], $_GET["nom"], $_GET["key"]) && !empty($_GET["prenom"]) && !empty($_GET["nom"]) && !empty($_GET["key"]) ) {
	include("config/function.php");
	$prenom = htmlspecialchars(urldecode($_GET["prenom"]));
	$nom = htmlspecialchars(urldecode($_GET["nom"]));
	$key = htmlspecialchars(urldecode($_GET["key"]));

	if (VerifConfirmation($db, $nom, $prenom, $key) == 1) {
		$cValidate = "CONFIRMATION VALIDÉE";
		writeLogs("logs/general.log", "$nom $prenom;confirmation du compte");
	}elseif (VerifConfirmation($db, $nom, $prenom, $key) == 3) {
		$cEchec = "Le compte a déjà été validé. Vous allez être redirigé à la page de connexion dans 5 secondes.";
		header('refresh:5;url=connexion.php');
	}else{
		header("location: index.php");}
}else{
	header("location: index.php");}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Confirmation d'inscription</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>
	<header>
	  <?php include "includes/menunav.php" ?>
	</header>

	<?php
	if (isset($cValidate)) {
		echo "<font color='green' style=\"font-weight: bold;\">". $cValidate . "</font>\n";
	}elseif (isset($cEchec)) {
		echo "<font id='temps' color='#eb2f06' style=\"font-weight: bold; font-size: 16px;\">". $cEchec . "</font>\n";
		?>
			<script>
			// https://www.developpez.net/forums/d343653/javascript/general-javascript/poo-compte-rebours/
			var tps = 5 ;
			var s=0;
			var disp="";
			var idtimer =setInterval('affichetemps()',1000);
			 
			function affichetemps(){
			  tps-- ;
			  s = parseInt((tps%3600)%60) ;
			  disp = "Le compte a déjà été validé. Vous allez être redirigé à la page de connexion dans " + (s<10 ? s : s) + " secondes." ;
			  document.getElementById('temps').innerHTML= disp;
			 
			  if ((s = 0)) {
			   clearInterval(idtimer);
			   return;
			   }
			}
			</script>
		<?php
	}
	?>
</body>
</html>