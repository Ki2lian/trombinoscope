<?php setlocale(LC_TIME, 'fr', 'fr_FR'); session_start();
if (isset($_POST["form-connexion"])) {
	$mail = htmlspecialchars($_POST["mail"]);
	$mdp = hash("sha256", $_POST["mdp"]);


	// VERIFIER SI LE COMPTE EST VALIDE POUR POUVOIR POURSUIVRE !
	
	if (!empty($mail) && !empty($mdp)) {

		if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {


			$lignes = file("db.csv");

			for ($i=0; $i < sizeof($lignes) ; $i++) {
				$ligne = $lignes[$i];
				$ligne = str_replace("\n", "", $ligne);

				$tableau = explode(";", $ligne);


				if ($tableau[3] == $mail) {
					$continueToMdp = True;
					break;
				}else{
					$erreur = "L'email ou le mot de passe est incorrect !";
					$continueToMdp = False;
				}
			}


			if ($continueToMdp == True) {

				for ($i=0; $i < sizeof($lignes) ; $i++) {

					if ($tableau[7] == $mdp) {
						$continueToLogin = True;
						break;
					}else{
						$erreur = "L'email ou le mot de passe est incorrect.";
						$continueToLogin = False;
					}
				}
				if ($continueToLogin == True) {
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
					header("Location: index.php");
				}
			}
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
	echo "<font color='#eb2f06' style=\"font-weight: bold;margin-left: 24%; font-size: 18px;\">". $erreur . "</font>\n";
	echo "</div>\n";
}
?>
</div>
<?php
}else{
	header("Location: index.php");
}
?>
<script src="js/script.js"></script>
</body>
</html>