<?php  setlocale(LC_TIME, 'fr', 'fr_FR'); session_start();
if(isset($_SESSION["id"])){
	//$pseudo = "Bienvenue sur votre profil " . $_SESSION["nom"] . " " . $_SESSION["prenom"];
	$nom = $_SESSION["nom"];
	$prenom = $_SESSION["prenom"];
	$img = $_SESSION["avatar"];
	$date = $_SESSION["date"];
	$groupe = $_SESSION["groupe"];
	$filiere = $_SESSION["filiere"];
	$anniv = $_SESSION["anniv"];
	$mail = $_SESSION["mail"];
	$telephone = $_SESSION["numero"];
}

if (isset($_POST["form-modifgeneral"]) || isset($_POST["form-modifpassword"]) || isset($_POST["form-modifmail"])) {
	$msg = "c'est bien, mais j'ai pas commencé à faire";
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

<div class="row">
	<div class="box">
		<div class="box_body profil">
			<img class="img-profil" src="img/profil/<?php echo $img; ?>" />
		</div>
		<div class="profil_framed">
			<h2 class="profil_name"><?php echo $nom . " " . $prenom; ?></h2>
		</div>
		<div class="profil_about">
			<u>Inscrit le:</u><?php echo " " . strftime("%e %B %Y", $date); ?>
		</div>
		<div class="profil_about">
			<u>Filière:</u><?php echo " " . $filiere; ?>
		</div>
		<div class="profil_about">
			<u>Groupe:</u><?php echo " " . $groupe; ?>
		</div>
		<div class="profil_about">
			<u>Email:</u><?php echo " " . $mail; ?>
		</div>
		<div class="profil_about">
			<u>Téléphone:</u><?php echo " " . $telephone; ?>
		</div>
		<div class="profil_about">
			<u>Anniversaire:</u><?php echo " " . strftime("%e/%m/%Y", $anniv); ?>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<button onclick="modif('modif-general')" class="btn">Modification Générale</button>
		<button onclick="modif('modif-password')" class="btn">Changement du mot de passe</button>
		<button onclick="modif('modif-mail')" class="btn">Changement d'email</button>
	</div>
</div>

<div class="row" id="modif-general" style="display: none;">
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
					<input title="Date de naissance" type="date" value="<?php echo strftime("%Y-%m-%e", $anniv); ?>" name="anniv" required="required" aria-required="true"/>
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

<div class="row" id="modif-password" style="display: none;">
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
					<input class="submit-form" type="submit" value="Modifier" name="form-modifpassword" />
				</td>
			</tr>
		</table>
	</form>
</div>

<div class="row" id="modif-mail" style="display: none;">
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

<?php
if (isset($msg)) {
	echo "<div class='row'>\n";
	echo $msg;
	echo "</div>";
}

?>

<script type="text/javascript">
	function modif(id){
		divModifActuel = document.getElementById(id);

		if (id == "modif-general") {
			document.getElementById("modif-password").style.display = 'none';
			document.getElementById("modif-mail").style.display = 'none';
		}else if (id == "modif-password") {
			document.getElementById("modif-general").style.display = 'none';
			document.getElementById("modif-mail").style.display = 'none';
		}else if (id == "modif-mail") {
			document.getElementById("modif-general").style.display = 'none';
			document.getElementById("modif-password").style.display = 'none';
		}

		if (divModifActuel.style.display == 'none') {
				divModifActuel.style.display = 'block';
		}else{
				divModifActuel.style.display = 'none';
		}
}
</script>

<script src="js/script.js"></script>

</body>
</html>
<?php
}else{
	header("Location: index.php");
}
?>