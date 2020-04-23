<?php  setlocale(LC_TIME, 'fr', 'fr_FR'); session_start();
if(isset($_SESSION["id"])){
	//$pseudo = "Bienvenue sur votre profil " . $_SESSION["nom"] . " " . $_SESSION["prenom"];
	$nom = $_SESSION["nom"];
	$prenom = $_SESSION["prenom"];
	$img = $_SESSION["avatar"];
	$date = $_SESSION["date"];
}

?>
<?php
if (isset($_SESSION["nom"])) {?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Profil</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>
<header>
  <?php include "includes/menunav.php" ?>
</header>
<!-- <p>Votre compte a été crée le <?php echo strftime("%e %B %Y", $date) ?></p> -->
<!--<p>Date d'anniv: <?php echo strftime("%e/%m/%Y", $_SESSION["anniv"]); ?></p> -->
<div class="row">
	<div class="box">
		<div class="box__body profil">
			<img class="img-profil" src="img/profil/<?php echo $img; ?>" />
		</div>
		<div class="profil_framed">
			<h2 class="profil_name"><?php echo $nom . " " . $prenom; ?></h2>
		</div>
<?php

/*if ($img != "defaut.png") {
?>
<img class="img-profil" src="img/profil/<?php echo $img; ?>" />
<?php
}else{
?>
<img class="img-profil" src="img/profil/<?php echo $img; ?>">
<?php
}*/

?>

	</div>
</div>

</body>
</html>
<?php
}else{
	header("Location: index.php");
}
?>