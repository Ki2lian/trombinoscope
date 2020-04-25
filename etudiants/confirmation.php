<?php

if (!isset($_SESSION["id"]) && isset($_GET["prenom"], $_GET["nom"], $_GET["key"]) && !empty($_GET["prenom"]) && !empty($_GET["nom"]) && !empty($_GET["key"]) ) {
	include("includes/function.php");
	$prenom = htmlspecialchars(urldecode($_GET["prenom"]));
	$nom = htmlspecialchars(urldecode($_GET["nom"]));
	$key = htmlspecialchars(urldecode($_GET["key"]));

	if (VerifConfirmation($db, $nom, $prenom, $key) == 1) {
		$cValidate = "CONFIRMATION VALIDÉE";
		writeLogs("general.log", "$nom $prenom;confirmation du compte");
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
		echo "<font color='#28a745' style=\"font-weight: bold;\">". $cValidate . "</font>\n";
	}elseif (isset($cEchec)) {
		echo "<font id='temps-confirmation' color='#dc3545' style=\"font-weight: bold; font-size: 16px;\">". $cEchec . "</font>\n";
	}
	?>
<script src="js/script.js"></script>
</body>
</html>