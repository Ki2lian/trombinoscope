<?php

if (!isset($_SESSION["id"]) && isset($_GET["prenom"], $_GET["nom"], $_GET["key"]) && !empty($_GET["prenom"]) && !empty($_GET["nom"]) && !empty($_GET["key"]) ) {
	$prenom = htmlspecialchars(urldecode($_GET["prenom"]));
	$nom = htmlspecialchars(urldecode($_GET["nom"]));
	$key = htmlspecialchars(urldecode($_GET["key"]));
	$continueToVerif = True;


	$fichier = fopen("db.csv", "r");
	$valeur = 1;
	$tableauStock = array();
    while ($lignes = fgets($fichier)){
	 	$lignes = explode(';', $lignes);
		if ($lignes[1] == $nom && $lignes[2] == $prenom && $lignes[11] == $key && $lignes[12] != 1){
			$validate = 1;

			$stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $lignes[5] . ";" . $lignes[6] . ";" . $lignes[7] . ";" . $lignes[8] . ";" . $lignes[9] . ";" . $lignes[10] . ";" . $lignes[11] . ";" . $valeur . "\n";

		}else{
		    $stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $lignes[5] . ";" . $lignes[6] . ";" . $lignes[7] . ";" . $lignes[8] . ";" . $lignes[9] . ";" . $lignes[10] . ";" . $lignes[11] . ";" . $lignes[12];
		}
		array_push($tableauStock, $stockerLigne);
	}

	fclose($fichier);
	if ($validate == 1) {
		$fichier = fopen("db.csv", "w");

		for ($i = 0; $i < sizeof($tableauStock); $i++){
			 fputs($fichier, $tableauStock[$i]);
		}
		$cValidate = "CONFIRMATION VALIDÉE";
	}else{
		header("location: index.php");
	}
}else{
	header("location: index.php");
}

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
		echo "<font color='#eb2f06' style=\"font-weight: bold; font-size: 16px;\">". $cEchec . "</font>\n";
	}

	?>
</body>
</html>