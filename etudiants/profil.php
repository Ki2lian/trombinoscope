<?php  include("includes/config.php");
include("includes/function.php");
if(isset($_SESSION["id"])){
	$id = $_SESSION["id"];
	$nom = $_SESSION["nom"];
	$prenom = $_SESSION["prenom"];
	$img = $_SESSION["avatar"];
	$date = $_SESSION["date"];
	$filiere = $_SESSION["filiere"];
	$groupe = $_SESSION["groupe"];
	$anniv = $_SESSION["anniv"];
	$mail = $_SESSION["mail"];
	$telephone = $_SESSION["numero"];
}

if (isset($_POST["form-modifgeneral"])) {
	if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["numero"]) && !empty($_POST["filiere"]) && !empty($_POST["groupe"]) && !empty($_POST["anniv"])){
		$modifNom = htmlspecialchars($_POST["nom"]);
		$modifPrenom = htmlspecialchars($_POST["prenom"]);
		$modifTelephone = $_POST["numero"];
		$modifFiliere = $_POST["filiere"];
		$modifGroupe = $_POST["groupe"];
		$modifAnniv = $_POST["anniv"];
		if (preg_match('#^[a-zA-Z]*$#', $modifNom) && preg_match('#^[a-zA-Z]*$#', $modifPrenom)) {
			if ($nom != $modifNom || $prenom != $modifPrenom) {
				if (verifName($db, $modifNom, $modifPrenom) == False) {
					$modifNom = $nom;
					$modifPrenom = $prenom;
					$msgError = "Le nom et le prénom n'ont pas pu être modifié car il existe déjà.";
					$erreur = True;
				}else{
					writeLogs($modifLog, "$nom $prenom;nom;a modifié son prénom et/ou nom pour $modifNom $modifPrenom");
				}
			}
		}else{
			$msgError = "Le nom ou le prénom ne peut pas contenir de caractères spéciaux et/ou de chiffres.";
			$erreur = True;
			$modifNom = $nom;
			$modifPrenom = $prenom;
		}

		if ($telephone != $modifTelephone) {
			if (verifNum($db, $modifTelephone) != False) {
				$modifTelephone = verifNum($db, $modifTelephone);
				writeLogs($modifLog, "$nom $prenom;telephone;a modifié son numéro de téléphone (avant: $telephone | maintenant: $modifTelephone)");
			}else{
				$modifTelephone = $telephone;
				$msgError = "Le numéro n'a pas pu être modifié car il existe déjà.";
				$erreur = True;
			}
		}
		
		if (strftime("%Y-%m-%d", $anniv) != $modifAnniv) {
			if (verifDateAnniv($modifAnniv) != False) {
				$modifAnniv = verifDateAnniv($modifAnniv);
				writeLogs($modifLog, "$nom $prenom;anniv;a modifié sa date de naissance (avant:" . strftime('%d/%m/%Y', $anniv) ."| maintenant:" . strftime('%d/%m/%Y', $modifAnniv) . ")");
			}else{
				$modifAnniv = strftime("%Y-%m-%d", $anniv);
				$msgError = "La date de naissance n'a pas pu être modifié car la date n'est pas reconnue.";
				$erreur = True;
			}
		}else{
			$modifAnniv = $anniv;
		}

		if ($modifFiliere != $filiere) {
			writeLogs($modifLog, "$nom $prenom;filiere;a modifié sa filière (avant: $filiere | maintenant: $modifFiliere)");
		}

		if ($modifGroupe != $groupe) {
			writeLogs($modifLog, "$nom $prenom;groupe;a modifié son groupe (avant: $groupe | maintenant: $modifGroupe)");
		}
		ModifGeneral($db, $modifNom, $modifPrenom, $modifTelephone, $modifFiliere, $modifGroupe, $modifAnniv, $erreur);
	}
}

if (isset($_POST["form-modifpassword"])) {
	if (!empty($_POST["mdp"]) && !empty($_POST["mdp3"]) && !empty($_POST["mdp4"])){
		$mdp = htmlspecialchars($_POST["mdp"]);
		$mdp3 = htmlspecialchars($_POST["mdp3"]);
		$mdp4 = htmlspecialchars($_POST["mdp4"]);

		if (strlen($_POST["mdp3"]) >= 8 || strlen($_POST["mdp4"]) >= 8) {
			if ($mdp3 == $mdp4) {
				if (verifPassword($db, $mdp) == True) {
					if ($mdp != $mdp3) {
						modifPassword($db, $mdp3);
						$modifOK = "Le mot de passe a bien changé.";
						writeLogs($modifLog, "$nom $prenom;password;a modifié son mot de passe");
					}else{
						$msgError = "Le mot de passe doit être différent de celui actuel."; header('refresh:5;url=profil');}
				}else{
					$msgError = "Votre mot de passe actuel n'est pas correct."; header('refresh:5;url=profil');}
			}else{
				$msgError = "Les deux mots de passe doivent correspondre."; header('refresh:5;url=profil');}
		}else{
			$msgError = "Les mots de passe doivent contenir au moins 8 caractères."; header('refresh:5;url=profil');}
	}
}

if (isset($_POST["form-modifmail"])) {
	if (!empty($_POST["mail"]) && !empty($_POST["mail2"])){
		$modifMail = htmlspecialchars($_POST["mail"]);
		$modifMail2 = htmlspecialchars($_POST["mail2"]);

		if ($modifMail == $modifMail2) {

			if (filter_var($modifMail, FILTER_VALIDATE_EMAIL)) {
				if ($mail != $modifMail) {
					if (verifMail($db, $modifMail) == True) {
						modifMail($db, $modifMail);
						writeLogs($modifLog, "$nom $prenom;email;a modifié son email (avant: $mail | maintenant: $modifMail)");
					}else{
						$msgError = "L'adresse email a déjà été utilisée."; header('refresh:5;url=profil');}
				}else{
					$msgError = "L'email doit être différent de celui actuel."; header('refresh:5;url=profil');}
			}else{
				$msgError = "L'adresse email n'est pas valide."; header('refresh:5;url=profil');}
		}else{
			$msgError = "Les deux emails doivent correspondre."; header('refresh:5;url=profil');}
	}
}

if (isset($_POST["form-avatar"]) ) {
	if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
		$cAvatar = changeAvatar("file");
		if ($cAvatar == "erreur1") {
			$msgError = "Il y a eu une erreur durant l'importation de votre photo de profil.";
			header('refresh:5;url=profil');
		}elseif ($cAvatar == "erreur2") {
			$msgError = "Votre photo de profil doit être au format jpg, jpeg ou png.";
			header('refresh:5;url=profil');
		}elseif ($cAvatar == "erreur3") {
			$msgError = "Votre photo de profil ne doit pas dépasser 2Mo.";
			header('refresh:5;url=profil');
		}else{
			writeLogs($modifLog, "$nom $prenom;avatar;a modifié son avatar");
			header("location: profil");
		}
	}
}

?>
<?php
if (isset($_SESSION["nom"])) {?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Profil: <?php echo $nom . " " . $prenom ?></title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>
<header>
  <?php include "includes/menunav.php" ?>
</header>

<div class="row">
	<div class="box">
		<div class="box_body profil">
			<img class="img-profil" src="img/profil/<?php echo $img; ?>" />
		</div>
		<div id="preview-file"></div>
		<div class="profil_framed">
			<h2 class="profil_name"><?php echo mb_strtoupper($nom, 'UTF-8') . " "  . ucfirst(strtolower($prenom)); ?></h2>
		</div>
		<div class="profil_about">
			<p class="underline">Inscrit le:</p><p class="bold"><?php echo " " . strftime("%d %B %Y", $date); ?></p>
		</div>
		<div class="profil_about">
			<p class="underline">Filière:</p><p class="bold"><?php echo " " . $filiere; ?></p>
		</div>
		<div class="profil_about">
			<p class="underline">Groupe:</p><p class="bold"><?php echo " " . $groupe; ?></p>
		</div>
		<div class="profil_about">
			<p class="underline">Email:</p><p class="bold"><?php echo " " . $mail; ?></p>
		</div>
		<div class="profil_about">
			<p class="underline">Téléphone:</p><p class="bold"><?php echo " " . $telephone; ?></p>
		</div>
		<div class="profil_about">
			<p class="underline">Anniversaire:</p><p class="bold"><?php echo " " . strftime("%d/%m/%Y", $anniv); ?></p>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<button onclick="modif('modif-general')" class="btn">Modification Générale</button>
		<button onclick="modif('modif-password')" class="btn">Changement du mot de passe</button>
		<button onclick="modif('modif-mail')" class="btn">Changement d'email</button>
		<button onclick="modif('modif-avatar')" class="btn">Changement d'avatar</button>
	</div>
</div>

<div class="row" id="modif-general" style="display: none;">
	<div class="form-modif">
		<form method="post">
			<table>
				<tr>
					<td>
						<input title="Votre nom" type="text" maxlength="20" placeholder="Nom" id="nom" name="nom" required="required" aria-required="true" value="<?php echo($nom); ?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<input title="Votre prénom" type="text" maxlength="20" placeholder="Prénom" id="prenom" name="prenom" required="required" aria-required="true" value="<?php echo($prenom); ?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<input title="Votre numéro de téléphone portable" type="text" placeholder="Numéro de téléphone portable" id="numero" name="numero" required="required" aria-required="true" value="<?php echo($telephone); ?>" pattern="0[1-68]([-. ]?[0-9]{2}){4}"/>
					</td>
				</tr>
				<tr>
					<td>
						<input title="Date de naissance" type="date" value="<?php echo strftime("%Y-%m-%d", $anniv); ?>" name="anniv" required="required" aria-required="true"/>
					</td>
				</tr>
				<tr>
					<td>
						<select name="filiere">
							<?php
								switch ($filiere) {
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
										<?php break; 
								}?>
						
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<select name="groupe">
							<?php
									switch ($groupe) {
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
									}?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<input class="submit-form" type="submit" value="Modifier" name="form-modifgeneral" />
					</td>
				</tr>

			</table>
		</form>
	</div>
</div>

<div class="row" id="modif-password" style="display: none;">
	<div class="form-modif">
		<form method="post">
			<table>
				<tr>
					<td>
						<input title="Votre mot de passe actuel" type="password" placeholder="Votre mot de passe actuel" id="mdp" name="mdp" required="required" aria-required="true" minlength="8" />
					</td>
					<td>
						<img id="eyes_mdp" style="padding-left: 18px;height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('mdp'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
					</td>
				</tr>
				<tr>
					<td>
						<input title="Votre nouveau mot de passe doit contenir au minimum 8 caractères" type="password" placeholder="Nouveau mot de passe" id="mdp3" name="mdp3" required="required" aria-required="true" minlength="8" />
					</td>
					<td>
						<img id="eyes_mdp2" style="padding-left: 18px;height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('mdp3'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
					</td>
				</tr>
				<tr>
					<td>
						<input title="Confirmation du nouveau mot de passe doit contenir au minimum 8 caractères" type="password" placeholder="Confirmation du nouveau mot de passe" id="mdp4" name="mdp4" required="required" aria-required="true" minlength="8" />
					</td>
					<td>
						<img id="eyes_mdp3" style="padding-left: 18px;height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('mdp4'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
					</td>
				</tr>
				<tr>
					<td>
						<input class="submit-form" type="submit" value="Modifier" name="form-modifpassword" />
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

<div class="row" id="modif-mail" style="display: none;">
	<div class="form-modif">
		<form method="post">
			<table>
				<tr>
					<td>
						<input title="Votre nouvel email" type="email" placeholder="Nouvel email" id="mail" name="mail" required="required" aria-required="true" value=""/>
					</td>
				</tr>
				<tr>
					<td>
						<input title="Votre nouvel email de confirmation" type="email" placeholder="Nouvel email de confirmation" id="mail2" name="mail2" required="required" aria-required="true" value=""/>
					</td>
				</tr>
				<tr>
					<td>
						<input class="submit-form" type="submit" value="Modifier" name="form-modifmail" />
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

<div class="row" id="modif-avatar" style="display: none;">
	<form name="formAvatar" method="post" enctype="multipart/form-data">
		<div>
    		<input class="uploadFile" name="file" type="file" multiple accept=".png, .jpg, .jpeg" required="required" aria-required="true"/>
 		</div> 
  		<input class="submit-form" type="submit" name="form-avatar" value="Changer" />
	</form>
</div>

<?php

if (isset($msgError)) {
	echo "<div id='center'>\n";
	echo "<font color='#dc3545' style=\"font-weight: bold; font-size: 16px;\">". $msgError . "</font><br/>\n";
	echo "<font id='temps-modif-profil' color='#dc3545' style=\"font-weight: bold; font-size: 16px;\">La page va se recharger automatiquement dans 5 secondes</font>\n";
	echo "</div>";
}

if (isset($modifOK)) {
	echo "<div id='center'>\n";
		echo "<font color='#28a745' style=\"font-weight: bold; font-size: 16px;\">". $modifOK . "</font>\n";
	echo "</div>";
}

if (isset($msg)) {
	echo "<div id='center'>\n";
	echo "<font color='#dc3545' style=\"font-weight: bold; font-size: 16px;\">". $msg . "</font><br/>\n";
	echo "</div>";
}
?>

<script type="text/javascript">
	function modif(id){
		divModifActuel = document.getElementById(id);

		if (id == "modif-general") {
			document.getElementById("modif-password").style.display = 'none';
			document.getElementById("modif-mail").style.display = 'none';
			document.getElementById("modif-avatar").style.display = 'none';
		}else if (id == "modif-password") {
			document.getElementById("modif-general").style.display = 'none';
			document.getElementById("modif-mail").style.display = 'none';
			document.getElementById("modif-avatar").style.display = 'none';
		}else if (id == "modif-mail") {
			document.getElementById("modif-general").style.display = 'none';
			document.getElementById("modif-password").style.display = 'none';
			document.getElementById("modif-avatar").style.display = 'none';
		}else if (id == "modif-avatar") {
			document.getElementById("modif-general").style.display = 'none';
			document.getElementById("modif-password").style.display = 'none';
			document.getElementById("modif-mail").style.display = 'none';
		}

		if (divModifActuel.style.display == 'none') {
				divModifActuel.style.display = 'block';
		}else{
				divModifActuel.style.display = 'none';
		}
		if (document.getElementById("center")) {
			document.getElementById("center").style.display = 'none';
		}
		
	}

	// Ces fonctions proviennent du site: https://codepen.io/Zonecss/pen/mzXojY

	function createThumbnail(sFile,sId) {
	  var oReader = new FileReader();
	  oReader.addEventListener('load', function() {
	    var oImgElement = document.createElement('img');
	    oImgElement.classList.add('img-profil') 
	    oImgElement.src = this.result;
	    document.getElementById('preview-'+sId).appendChild(oImgElement);
	  }, false);

	  oReader.readAsDataURL(sFile);

	}
	function changeInputFil(oEvent){
	  var oInputFile = oEvent.currentTarget,
	      sName = oInputFile.name,
	      aFiles = oInputFile.files,
	      aAllowedTypes = ['png', 'jpg', 'jpeg'],
	      imgType;  
	  document.getElementById('preview-'+sName).innerHTML ='';
	  for (var i = 0 ; i < aFiles.length ; i++) {
	    imgType = aFiles[i].name.split('.');
	    imgType = imgType[imgType.length - 1];
	    if(aAllowedTypes.indexOf(imgType) != -1) {
	      createThumbnail(aFiles[i],sName);
	    }
	  }
	}

	document.addEventListener('DOMContentLoaded',function(){
	 var aFileInput = document.forms['formAvatar'].querySelectorAll('[type=file]');
	  for(var k = 0; k < aFileInput.length;k++){
	    aFileInput[k].addEventListener('change', changeInputFil, false);
	  }
	});
</script>

<script src="js/script.js"></script>

</body>
</html>
<?php
}else{
	header("Location: index.php");
}
?>