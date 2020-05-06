<?php include("includes/config.inc.php");
if (isset($_POST["form-connexion"])) {
	include("includes/function.inc.php");
	$mail = htmlspecialchars($_POST["mail"]);
	$mdp = htmlspecialchars($_POST["mdp"]);


	if (!empty($mail) && !empty($mdp)) {

		if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

			if (verifConnexion($db, $mail, $mdp) != False) {
				$tableau = verifConnexion($db, $mail, $mdp);
				$_SESSION["id"] = $tableau[0];
				$_SESSION["nom"] = $tableau[1];
				$_SESSION["prenom"] = $tableau[2];
				$_SESSION["mail"] = $tableau[3];
				writeLogs($generalLog, "$tableau[1] $tableau[2];s'est connecté");
				header("Location: api");
			}else{
				$erreur = "L'email ou le mot de passe est incorrect !"; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$mail");}
		}else{
			$erreur = "L'adresse email n'est pas valide."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$mail");}
	}else{
		$erreur = "Tous les champs doivent être complétés."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;none");}
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
  <?php include "includes/menunav.inc.php" ?>
</header>
<div class="formulaire">
	<form method="post">
		<table class="table-connexion">
			<tr>
				<td>
					<input title="Votre email" type="email" placeholder="Email" id="mail" name="mail" required="required" aria-required="true" value="<?php if(isset($mail)){echo($mail);} ?>"/>
				</td>
				<td> <!-- Pour initialiser à 2 cellules, sinon problème dans la vérification html -->
					
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
	header("Location: api");
}
?>
<script src="js/script.js"></script>
</body>
</html>