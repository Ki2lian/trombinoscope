<?php  setlocale(LC_TIME, 'fr', 'fr_FR'); session_start();
if(isset($_SESSION["id"])){
	$pseudo = "Bienvenue sur votre profil " . $_SESSION["nom"] . " " . $_SESSION["prenom"];
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

<h2><?php echo $pseudo; ?></h2>
<!-- <p>Votre compte a été crée le <?php echo strftime("%e %B %Y", $date) ?></p> -->
<!--<p>Date d'anniv: <?php echo strftime("%e/%m/%Y", $_SESSION["anniv"]); ?></p> -->
<?php

if ($img != "defaut.png") {
?>
<img class="img-profil" src="img/profil/<?php echo $img; ?>" />
<?php
}else{
?>
<img class="img-profil" src="img/profil/<?php echo $img; ?>">
<?php
}

?>


</body>
</html>
<?php
}else{
	header("Location: index.php");
}
?>