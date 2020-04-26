<?php include("includes/config.php");
if (isset($_POST["form-connexion"])) {
	include("includes/function.php");
	$mail = htmlspecialchars($_POST["mail"]);
	$mdp = htmlspecialchars($_POST["mdp"]);

	if (!empty($mail) && !empty($mdp)) {

		if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

			if (verifConnexion($db, $mail, $mdp) != False) {
				$tableau = verifConnexion($db, $mail, $mdp);
				if ($tableau[12] == 1) {
					$_SESSION["id"] = $tableau[0];
					$_SESSION["nom"] = $tableau[1];
					$_SESSION["prenom"] = $tableau[2];
					$_SESSION["mail"] = $tableau[3];
					$_SESSION["numero"] = $tableau[4];
					$_SESSION["filiere"] = $tableau[5];
					$_SESSION["groupe"] = $tableau[6];
					$_SESSION["avatar"] = $tableau[8];
					$_SESSION["date"] = $tableau[9];
					$_SESSION["anniv"] = $tableau[10];
					writeLogs($generalLog, "$tableau[1] $tableau[2];s'est connecté");
					header("Location: profil");
				}else{
					$erreur = "Vous devez vérifier votre compte en cliquant sur le lien sur votre email pour pouvoir vous connecter.";}
			}else{
				$erreur = "L'email ou le mot de passe est incorrect !";}
		}else{
			$erreur = "L'adresse email n'est pas valide.";}
	}else{
		$erreur = "Tous les champs doivent être complétés.";}
}
?>
<?php
if (!isset($_SESSION["nom"])) {?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Connexion</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>
<header>
  <?php include "includes/menunav.php" ?>
</header>
<div class="formulaire">
	<form method="post">
		<table class="table-connexion">
			<tr>
				<td>
					<input title="Votre email" type="email" placeholder="Email" id="mail" name="mail" required="required" aria-required="true" value="<?php if(isset($mail)){echo($mail);} ?>"/>
				</td>
			</tr>
			<tr>
				<td>
					<input title="Votre mot de passe doit contenir au minimum 8 caractères" type="password" placeholder="Mot de passe" id="mdp" name="mdp" required="required" aria-required="true" minlength="8"/>
				</td>
				<td>
				<img id="eyes_mdp" style="padding-left: 18px;height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('mdp'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
				</td>
			</tr>
		</table>
		<input class="submit-form" type="submit" value="Se connecter" name="form-connexion" />
	</form>
<?php
if (isset($erreur)) {
	echo "<div class='error'>\n";
	echo "<font color='#dc3545' style=\"font-weight: bold;margin-left: 24%; font-size: 18px;\">". $erreur . "</font>\n";
	echo "</div>\n";
}
?>
</div>
<?php
}else{
	header("Location: profil");
}
?>
<script src="js/script.js"></script>
</body>
</html>