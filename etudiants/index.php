<?php include("includes/config.php");
if (isset($_POST["form-inscription"])) {
	include("includes/function.php");
	$key = randomKey(32);
	$id = getID($db);

	$nom = htmlspecialchars($_POST["nom"]);
	$prenom = htmlspecialchars($_POST["prenom"]);
	$mail = htmlspecialchars($_POST["mail"]);
	$mail2 = htmlspecialchars($_POST["mail2"]);
	$telephone = $_POST["numero"];
	$filiere = $_POST["filiere"];
	$groupe = $_POST["groupe"];
	$mdp = hash("sha256", $_POST["mdp"] . $key);
	$mdp2 = hash("sha256", $_POST["mdp2"] . $key);
	$img = "defaut.png";
	$anniv = $_POST["anniv"];
	$contactAdmin = "si vous pensez que c'est une erreur, merci de contacter l'administrateur";


	if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["mail"]) && !empty($_POST["mail2"]) && !empty($_POST["numero"]) && !empty($_POST["mdp"]) && !empty($_POST["mdp2"]) && !empty($_POST["filiere"]) && !empty($_POST["groupe"]) && !empty($_POST["anniv"]))  {

		if (preg_match('#^[a-zA-Z]*$#', $nom)) {

			if (preg_match('#^[a-zA-Z]*$#', $prenom)) {

				if (verifName($db, $nom, $prenom) == True) {

					if ($mail == $mail2) {

						if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

							if (verifMail($db, $mail) == True) {

								if (preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $telephone)){

									if (verifNum($db, $telephone) != False) {
										$telephone = verifNum($db, $telephone);

										if (verifDateAnniv($anniv) != False) {
											$anniv = verifDateAnniv($anniv);

											if (strlen($_POST["mdp"]) >= 8 || strlen($_POST["mdp2"]) >= 8) {

												if ($mdp == $mdp2) {
													$fichier = fopen($db, "a+");
													//fputs($fichier, $id+1 . ";" . $nom . ";" . $prenom . ";" . $mail . ";" . $telephone . ";" . $filiere . ";" . $groupe . ";" . $mdp . ";" . $img . ";" . mktime() . ";" . $anniv . ";" . $key . ";0" . "\n");
													fputs($fichier, $id+1 . ";" . $nom . ";" . $prenom . ";" . $mail . ";" . $telephone . ";" . $filiere . ";" . $groupe . ";" . $mdp . ";" . $img . ";" . mktime() . ";" . $anniv . ";" . $key . ";1" . "\n");
													fclose($fichier);
													$message = "Cliquez sur ce lien pour confirmer votre inscription: https://etudiants.alwaysdata.net/confirmation.php?prenom=". urlencode($prenom) . "&nom=". urlencode($nom) ."&key=". urlencode($key) ."'
													";
													writeLogs($generalLog, "$nom $prenom;s'est inscrit");
													/*mail($mail, "Confirmation d'inscription", $message);

													$inscriptionOK = "Votre compte a été crée, vous avez reçu un mail de confirmation, veuillez cliquer sur le lien dans le mail pour poursuivre votre inscription.";*/
													$inscriptionOK = "Votre compte a été crée, vous allez être redirigé à la page de connexion dans 3 secondes";
													header('refresh:3;url=connexion.php');

												}else{
													$erreur = "Les mots de passe ne correspondent pas.";}
											}else{
												$erreur = "Votre mot de passe doit contenir au moins 8 caractères.";}
										}else{
											$erreur = "Il y a un problème avec la date de naissance.";}
									}else{
										$erreur = "$telephone a déjà été utilisé, $contactAdmin.";}
								}else{
									$erreur = "$telephone n'est pas un numéro valide.";}
							}else{
								$erreur = "L'adresse email a déjà été utilisée, $contactAdmin.";}
						}else{
							$erreur = "L'adresse email n'est pas valide.";}
					}else{
						$erreur = "Les adresses email ne correspondent pas.";}
				}else{
					$erreur = "Le nom et prénom ont déjà été utilisé, $contactAdmin.";}
			}else{
				$erreur = "Le prénom ne peut pas avoir de caractères spéciaux ou de chiffres.";}
		}else{
			$erreur = "Le nom ne peut pas avoir de caractères spéciaux ou de chiffres.";}
	}else{
		$erreur = "Tous les champs doivent être complétés.";}
}

$jsonText = file_get_contents("filiere.json");
$jsonArray = json_decode($jsonText, True);

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
  <?php include "includes/menunav.php" ?>
</header>

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
				<input title="Votre numéro de téléphone portable" type="text" placeholder="Numéro de téléphone portable" id="numero" name="numero" required="required" aria-required="true" value="<?php if(isset($_POST["numero"]) && $telephone != ""){echo($_POST["numero"]);} ?>" pattern="0[1-68]([-. ]?[0-9]{2}){4}"/>
			</td>
			<td>
				<td>
				<input title="Date de naissance" type="date" value="<?php if(isset($_POST["anniv"])){ echo($_POST["anniv"]); } ?>" name="anniv" required="required" aria-required="true"/>
				</td>
			</td>
		</tr>
		<tr>
			<td>
				<select onchange="json(this.id);" id="filiere" name="filiere">
					<option selected="" disabled="">Choisir une filière</option>
					<?php 
						for ($i=0; $i < sizeof($jsonArray["filiere"]); $i++) { 
							$jsonNom = $jsonArray["filiere"][$i]["nom"];
							?>
							<option value="<?php echo $jsonNom ?>"><?php echo $jsonNom ?></option>
							<?php
						}
					?>
				</select>
			</td>
			<td>
				<td>
				<select name="groupe" id="opt-groupe">
					<option selected="" disabled="">Choisir un groupe</option>
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
			</td>
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
	header("Location: profil");
}
?>

<script type="text/javascript">

	function json(id){
		var option = document.getElementById("opt-groupe");
		var json = <?php echo $jsonText; ?>;
		var nomFiliere = document.getElementById(id).value;

		option.innerHTML = "<option selected=\"\" disabled=\"\">Choisir un groupe</option>";
		for (var i = 0; i < json["filiere"].length; i++) {
			if (nomFiliere == json["filiere"][i]["nom"]) {
				break;
			}
		}
		for (var j = 0; j < json["filiere"][i]["groupe"].length; j++) {
			option.innerHTML += "<option value=" + json["filiere"][i]["groupe"][j] + ">" + json["filiere"][i]["groupe"][j] + "</option>";
		}
		
	}
</script>
<script src="js/script.js"></script>
</body>
</html>