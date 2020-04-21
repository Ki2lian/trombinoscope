<?php setlocale(LC_TIME, 'fr', 'fr_FR'); session_start();
if (isset($_POST["form-inscription"])) {
	$nom = htmlspecialchars($_POST["nom"]);
	$prenom = htmlspecialchars($_POST["prenom"]);
	$mail = htmlspecialchars($_POST["mail"]);
	$mail2 = htmlspecialchars($_POST["mail2"]);
	$telephone = $_POST["numero"];
	$filiere = $_POST["filiere"];
	$groupe = $_POST["groupe"];
	$mdp = hash("sha256", $_POST["mdp"]);
	$mdp2 = hash("sha256", $_POST["mdp2"]);
	$continueToMail = True;
	$continueToNum = True;
	$continueToMdp = True;
	$contactAdmin = "si vous pensez que c'est une erreur, merci de contacter l'administrateur";


	function verifName($lname, $fname){
		$lignes = file("bd.csv");
		for ($i=0; $i < sizeof($lignes) ; $i++) {
			$ligne = $lignes[$i];
			$ligne = str_replace("\n", "", $ligne);

			$tableau = explode(";", $ligne);


			if ($tableau[1] == $lname && $tableau[2] == $fname) {
				$erreur = "Le nom a déjà été utilisé.";
				$continueToMail = False;
			}
		}
	}

	if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["mail"]) && !empty($_POST["mail2"]) && !empty($_POST["numero"]) && !empty($_POST["mdp"]) && !empty($_POST["mdp2"]) && !empty($_POST["filiere"]) && !empty($_POST["groupe"]))  {

		if (preg_match('#^[a-zA-Z0-9]*$#', $_POST['nom'])) {


			if (preg_match('#^[a-zA-Z0-9]*$#', $_POST['prenom'])) {


				$lignes = file("bd.csv");
				for ($i=0; $i < sizeof($lignes) ; $i++) {
					$ligne = $lignes[$i];
					$ligne = str_replace("\n", "", $ligne);

					$tableau = explode(";", $ligne);


					if ($tableau[1] == $nom && $tableau[2] == $prenom) {
						$erreur = "Le nom et prénom ont déjà été utilisé, $contactAdmin.";
						$continueToMail = False;
						break;
					}
				}

				if ($continueToMail == True) {

					if ($mail == $mail2) {

						if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

							$nbrLignes = file("bd.csv");
							$fichier = fopen("bd.csv", "r+");

							for ($i=0; $i < sizeof($nbrLignes) ; $i++) { 
								$ligne = fgets($fichier);
								$tableau = explode(";", $ligne);


								if ($tableau[3] == $mail) {
									$erreur = "L'adresse email a déjà été utilisée, $contactAdmin.";
									$continueToNum = False;
									break;
								}
							}

							fclose($fichier);


							if ($continueToNum == True) {

								// https://www.1formatik.com/1760/comment-verifier-un-numero-de-telephone-portable-html5-php
								if (preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $telephone)){
									$meta_carac = array("-", ".", " ");
									$telephone = str_replace($meta_carac, "", $telephone);

									if ($continueToMdp == True) {

										if (strlen($_POST["mdp"]) >= 8 || strlen($_POST["mdp2"]) >= 8 && $continueToMdp = True) {

											if ($mdp == $mdp2) {

												$fichier = fopen("bd.csv", "a+");
												fputs($fichier, $i+1 . ";" . $nom . ";" . $prenom . ";" . $mail . ";" . $numero . ";" . $filiere . ";" . $groupe . ";" . $mdp . "\n");
												fclose($fichier);
												$inscriptionOK = "Votre compte a été crée, vous allez être redirigé à la page de connexion dans 5 secondes";
												header('refresh:5;url=connexion.php');
								}else{
									$erreur = "Les mots de passe ne correspondent pas.";}
							}else{
								$erreur = "Votre mot de passe doit contenir au moins 8 caractères.";}}
						}else{
							$erreur = "$telephone n'est pas un numéro valide."; $continueToMdp = False;}}
					}else{
						$erreur = "L'adresse email n'est pas valide.";}
				}else{
					$erreur = "Les adresses email ne correspondent pas.";}}
			}else{
				$erreur = "Le prénom ne peut pas avoir de caractères spéciaux.";}
		}else{
			$erreur = "Le nom ne peut pas avoir de caractères spéciaux.";}
	}else{
		$erreur = "Tous les champs doivent être complétés.";}
}
?>

<?php
if (!isset($_SESSION["nom"])) {
	?>

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
  <?php include "includes/menunav.php" ?>
</header>

<!-- Nom, prénom, email,
téléphone, adresse, filière, groupe et photo -->

<div class="formulaire">
<form method="post">
	<table>
		<tr>
			<td>
				<input title="Votre nom" type="text" maxlength="20" placeholder="Nom" id="nom" name="nom" required="required" aria-required="true" value="<?php if(isset($nom)){echo($nom);} ?>"/>
			</td>
			<td>
				<td>
				<input title="Votre prénom" type="text" maxlength="20" placeholder="Prénom" id="prenom" name="prenom" required="required" aria-required="true" value="<?php if(isset($prenom)){echo($prenom);} ?>"/>
				</td>
			</td>
		</tr>
		<tr>
			<td>
				<input title="Votre email" type="email" placeholder="Email" id="mail" name="mail" required="required" aria-required="true" value="<?php if(isset($mail)){echo($mail);} ?>"/>
			</td>
			<td>
				<td>
				<input title="Votre email de confirmation" type="email" placeholder="Email de confirmation" id="mail2" name="mail2" required="required" aria-required="true" value="<?php if(isset($mail2)){echo($mail2);} ?>"/>
				</td>
			</td>
		</tr>
		<tr>
			<td>
				<input title="Votre numéro de téléphone portable" type="text" placeholder="Numéro de téléphone portable" id="numero" name="numero" required="required" aria-required="true" value="<?php if(isset($_POST["numero"])){echo($_POST["numero"]);} ?>" pattern="0[1-68]([-. ]?[0-9]{2}){4}"/>
			</td>
		</tr>
		<tr>
			<td>
				<select name="filiere">

					<?php
					if (isset($_POST["filiere"])) {
						switch ($_POST["filiere"]) {
							case "L1-MIPI":
							?>
								<option selected="" value="L1-MIPI">L1-MIPI</option>
								<option value="L2-MIPI">L2-MIPI</option>
								<option value="L3-I">L3-I</option>
								<option value="LP RS">LP RS</option>
								<option value="LPI-RIWS">LPI-RIWS</option>
							<?php
								break;

							case "L2-MIPI":
							?>
								<option value="L1-MIPI">L1-MIPI</option>
								<option selected="" value="L2-MIPI">L2-MIPI</option>
								<option value="L3-I">L3-I</option>
								<option value="LP RS">LP RS</option>
								<option value="LPI-RIWS">LPI-RIWS</option>
							<?php
								break;

							case "L3-I":
							?>
								<option value="L1-MIPI">L1-MIPI</option>
								<option value="L2-MIPI">L2-MIPI</option>
								<option selected="" value="L3-I">L3-I</option>
								<option value="LP RS">LP RS</option>
								<option value="LPI-RIWS">LPI-RIWS</option>
							<?php
								break;

							case "LP RS":
							?>
								<option value="L1-MIPI">L1-MIPI</option>
								<option value="L2-MIPI">L2-MIPI</option>
								<option value="L3-I">L3-I</option>
								<option selected="" value="LP RS">LP RS</option>
								<option value="LPI-RIWS">LPI-RIWS</option>
							<?php
								break;

							case "LPI-RIWS":
							?>
								<option value="L1-MIPI">L1-MIPI</option>
								<option value="L2-MIPI">L2-MIPI</option>
								<option value="L3-I">L3-I</option>
								<option value="LP RS">LP RS</option>
								<option selected="" value="LPI-RIWS">LPI-RIWS</option>
							<?php
								break;
						}
					}


					else{
					?>

					<option selected="" disabled="">Choisir une filière</option>
					<option value="L1-MIPI">L1-MIPI</option>
					<option value="L2-MIPI">L2-MIPI</option>
					<option value="L3-I">L3-I</option>
					<option value="LP RS">LP RS</option>
					<option value="LPI-RIWS">LPI-RIWS</option>

				<?php } ?>
				</select>
			</td>
			<td>
				<td>
				<select name="groupe">

					<?php
						if (isset($_POST["groupe"])) {
							switch ($_POST["groupe"]) {
								case "A1":
								?>
									<option selected="" value="A1">A1</option>
									<option value="B2">B2</option>
									<option value="LPI-1">LPI-1</option>
									<option value="LPI-2">LPI-2</option>
									<option value="LPI-3">LPI-3</option>
								<?php
									break;

								case "B2":
								?>
									<option value="A1">A1</option>
									<option selected="" value="B2">B2</option>
									<option value="LPI-1">LPI-1</option>
									<option value="LPI-2">LPI-2</option>
									<option value="LPI-3">LPI-3</option>
								<?php
									break;

								case "LPI-1":
								?>
									<option value="A1">A1</option>
									<option value="B2">B2</option>
									<option selected="" value="LPI-1">LPI-1</option>
									<option value="LPI-2">LPI-2</option>
									<option value="LPI-3">LPI-3</option>
								<?php
									break;

								case "LPI-2":
								?>
									<option value="A1">A1</option>
									<option value="B2">B2</option>
									<option value="LPI-1">LPI-1</option>
									<option selected="" value="LPI-2">LPI-2</option>
									<option value="LPI-3">LPI-3</option>
								<?php
									break;

								case "LPI-3":
								?>
								<option value="A1">A1</option>
								<option value="B2">B2</option>
								<option value="LPI-1">LPI-1</option>
								<option value="LPI-2">LPI-2</option>
								<option selected="" value="LPI-3">LPI-3</option>
								<?php
									break;
							}
						}else{
					?>

					<option selected="" disabled="">Choisir un groupe</option>
					<option value="A1">A1</option>
					<option value="B2">B2</option>
					<option value="LPI-1">LPI-1</option>
					<option value="LPI-2">LPI-2</option>
					<option value="LPI-3">LPI-3</option>

				<?php } ?>
				</select>
				</td>
			</td>
		</tr>
		<tr>
			<td>
				<input title="Votre mot de passe doit contenir au minimum 8 caractères" type="password" placeholder="Mot de passe" id="mdp" name="mdp" required="required" aria-required="true" minlength="8"/>
			</td>
			<td>
				<img id="eyes_mdp1" style="padding-right: 5px;height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('mdp'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
				<!-- <input title="Voir le mot de passe" onclick="showPassword('mdp')" type="button" name="showpassword" value="Voir le mot de passe"/> -->
			</td>
			<td>
				<input title="Votre mot de passe de confirmation doit contenir au minimum 8 caractères" type="password" placeholder="Mot de passe de confirmation" id="mdp2" name="mdp2" required="required" aria-required="true" minlength="8" />
			</td>
			<td>
				<img id="eyes_mdp2" style="padding-left: 18px;height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('mdp2'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
				<!-- <input title="Voir le mot de passe" onclick="showPassword('mdp2')" type="button" name="showpassword" value="Voir le mot de passe"/> -->
			</td>
		</tr>
	</table>

	<input class="submit-form" type="submit" value="S'inscrire" name="form-inscription" />
</form>
<?php
if (isset($erreur)) {
	echo "<font color='#eb2f06' style=\"font-weight: bold;\">". $erreur . "</font>";
}
?>
<?php
if (isset($inscriptionOK)) {
	echo "<font color='green' style=\"font-weight: bold;\">". $inscriptionOK . "</font>";
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