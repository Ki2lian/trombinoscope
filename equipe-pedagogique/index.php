<?php include("includes/config.inc.php");
if (isset($_POST["form-inscription"])) {
	include("includes/function.inc.php");
	$key = randomKey(32);
	$id = getID($db);

	$nom = htmlspecialchars($_POST["nom"]);
	$prenom = htmlspecialchars($_POST["prenom"]);
	$mail = htmlspecialchars($_POST["mail"]);
	$mail2 = htmlspecialchars($_POST["mail2"]);
	$mdp = hash("sha256", $_POST["mdp"] . $key);
	$mdp2 = hash("sha256", $_POST["mdp2"] . $key);


	if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["mail"]) && !empty($_POST["mail2"]) && !empty($_POST["mdp"]) && !empty($_POST["mdp2"]))  {

		if (preg_match('#^[a-zA-Z]*$#', $nom)) {

			if (preg_match('#^[a-zA-Z]*$#', $prenom)) {

				if (verifName($db, $nom, $prenom) == True) {

					if ($mail == $mail2) {

						if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

							if (verifMail($db, $mail) == True) {


								if (strlen($_POST["mdp"]) >= 8 || strlen($_POST["mdp2"]) >= 8) {

									if ($mdp == $mdp2) {
										$fichier = fopen($db, "a+");
										fputs($fichier, $id+1 . ";" . $nom . ";" . $prenom . ";" . $mail . ";"  . $mdp . ";" . mktime() . ";" . $key . "\n");
										fclose($fichier);
										writeLogs($generalLog, "$nom $prenom;s'est inscrit");
										$inscriptionOK = "Votre compte a été créé avec succès.";
									}else{
										$erreur = "Les mots de passe ne correspondent pas."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;none");}
								}else{
									$erreur = "Votre mot de passe doit contenir au moins 8 caractères."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;none");}
							}else{
								$erreur = "L'adresse email a déjà été utilisée."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$mail");}
						}else{
							$erreur = "L'adresse email n'est pas valide."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$mail");}
					}else{
						$erreur = "Les adresses email ne correspondent pas.";writeLogs($erreurLog, "anonyme;$pageLog;$erreur;none");}
				}else{
					$erreur = "Le nom et prénom ont déjà été utilisés."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$nom $prenom");}
			}else{
				$erreur = "Le prénom ne peut pas avoir de caractères spéciaux ou de chiffres."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$prenom");}
		}else{
			$erreur = "Le nom ne peut pas avoir de caractères spéciaux ou de chiffres."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$nom");}
	}else{
		$erreur = "Tous les champs doivent être complétés."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;none");}
}
?>
<?php
if (!isset($_SESSION["nom"])) {?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Inscription</title>
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
	<table>
		<tr>
			<td>
				<input title="Votre nom" type="text" maxlength="20" placeholder="Nom" id="nom" name="nom" required="required" aria-required="true" value="<?php if(isset($nom)){echo($nom);} ?>"/>
			</td>

			<td>
				<input title="Votre prénom" type="text" maxlength="20" placeholder="Prénom" id="prenom" name="prenom" required="required" aria-required="true" value="<?php if(isset($prenom)){echo($prenom);} ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<input title="Votre email" type="email" placeholder="Email" id="mail" name="mail" required="required" aria-required="true" value="<?php if(isset($mail)){echo($mail);} ?>"/>
			</td>

			<td>
				<input title="Votre email de confirmation" type="email" placeholder="Email de confirmation" id="mail2" name="mail2" required="required" aria-required="true" value="<?php if(isset($mail2)){echo($mail2);} ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<input title="Votre mot de passe doit contenir au minimum 8 caractères" type="password" placeholder="Mot de passe" id="mdp" name="mdp" required="required" aria-required="true" minlength="8"/>
			</td>
			<td>
				<img id="eyes_mdp1" style="padding-right: 5px;height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('mdp'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
			</td>
		</tr>
		<tr>
			<td>
				<input title="Votre mot de passe de confirmation doit contenir au minimum 8 caractères" type="password" placeholder="Mot de passe de confirmation" id="mdp2" name="mdp2" required="required" aria-required="true" minlength="8" />
			</td>
			<td>
				<img id="eyes_mdp2" style="padding-left: 18px;height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('mdp2'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
			</td>
		</tr>
	</table>

	<input class="submit-form" type="submit" value="S'inscrire" name="form-inscription" />
</form>
<?php
if (isset($erreur)) {
	echo "<div class='error'>\n";
	echo "<font color='#dc3545' style=\"font-weight: bold; font-size: 16px;\">". $erreur . "</font>\n";
	echo "</div>\n";
}elseif (isset($inscriptionOK)) {
	echo "<div class='inscriptionOK'>\n";
	echo "<font color='#28a745' style=\"font-weight: bold;\">". $inscriptionOK . "</font>\n";
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