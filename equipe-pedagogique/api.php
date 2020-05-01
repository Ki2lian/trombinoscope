<?php include("includes/config.php");

$jsonTextApiFiliere = file_get_contents("https://etudiants.alwaysdata.net/filiere.json");
$jsonArrayApiFiliere = json_decode($jsonTextApiFiliere, True);

?>
<!DOCTYPE html>
<html>
<head>
	<title>API</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="styles/imprim.css" media="print" />
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>

<header>
  <?php include "includes/menunav.php" ?>
</header>
<?php
if (isset($_POST["form-trombinoscope"])) {
	if (!empty($_POST["filiere"]) && !empty($_POST["groupe"])) {
		$filiere = $_POST["filiere"];
		$groupe = $_POST["groupe"];
		$key = "UikPqwDB8c1SHlFAn6FoMryc3610OMbZ";
		$jsonTextApiTrombi = file_get_contents("https://etudiants.alwaysdata.net/test_api?groupe=$groupe&filiere=$filiere&key=$key");
		$jsonArrayApiTrombi = json_decode($jsonTextApiTrombi, True);
		if (isset($jsonArrayApiTrombi[$filiere][$groupe])) {
			echo "<h2 class=\"h2-api\">Filière: $filiere | Groupe: $groupe | Nombre d'élèves: " . sizeof($jsonArrayApiTrombi[$filiere][$groupe]) . "</h2><br/>\n";
			echo "<input class=\"input-api\" type=\"button\" onclick=\"hideInfo();window.print();\" value=\"Imprimer\" />";
			echo "<div class=\"container-api\">";
			for ($i=1; $i <= sizeof($jsonArrayApiTrombi[$filiere][$groupe]); $i++) {
				$info = $jsonArrayApiTrombi[$filiere][$groupe][$i];
				echo "<div class=\"div-api\">\n";
				echo "<img src=\"https://etudiants.alwaysdata.net/img/profil/" . $info["image"] . "\" onclick='showInfo(\"profil-" . $i ."\")' alt=\"Erreur\"/><br/>\n";
				echo "<p>" . $info["nom"] . " " . $info["prenom"] . "</p>\n";
				echo "<p class=\"profil-info\" id=\"profil-" . $i . "\" style=\"display: none;\">" . $info["email"] . "<br/>" . $info["telephone"] . "</p>\n";
				echo "</div>\n";
			}
			echo "</div>";
		}
	}else{
		$erreur = "Tous les champs doivent être complétés.";}
}
?>


<?php
if (empty($_POST["filiere"])) {
?>
	<div class="formulaire-trombi">
		<form method="post">
			<table>
				<tr>
					<td>
						<select onchange="json(this.id);" id="filiere" name="filiere">
							<option selected="" disabled="">Choisir une filière</option>
								<?php 
									for ($i=0; $i < sizeof($jsonArrayApiFiliere["filiere"]); $i++) { 
										$jsonNom = $jsonArrayApiFiliere["filiere"][$i]["nom"];
										?>
										<option value="<?php echo $jsonNom ?>"><?php echo $jsonNom ?></option>
										<?php
									}
								?>			
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<select name="groupe" id="opt-groupe">
							<option selected="" disabled="">Choisir un groupe</option>
						</select>
					</td>
				</tr>
			</table>
			<input class="submit-form-trombi" type="submit" value="Valider" name="form-trombinoscope" />
		</form>
		<?php
		if (isset($erreur)) {
			echo "<font color='#dc3545' style=\"font-weight: bold; font-size: 16px;\">". $erreur . "</font>\n";
		}

		?>
	</div>
<?php } ?>
<script type="text/javascript">

	function json(id){
		var option = document.getElementById("opt-groupe");
		var json = <?php echo $jsonTextApiFiliere; ?>;
		var nomFiliere = document.getElementById(id).value;
		option.innerHTML = "<option value =\"filiere-only\">Filière seulement</option>";
		for (var i = 0; i < json["filiere"].length; i++) {
			if (nomFiliere == json["filiere"][i]["nom"]) {
				break;
			}
		}
		for (var j = 0; j < json["filiere"][i]["groupe"].length; j++) {
			option.innerHTML += "<option value=" + json["filiere"][i]["groupe"][j] + ">" + json["filiere"][i]["groupe"][j] + "</option>";
		}
		
	}

	function showInfo(id){
		
		var infoProfil = document.getElementById(id);

		if(infoProfil.style.display == "none"){
			infoProfil.style.display ="block";
		}else{
			infoProfil.style.display ="none";
		}		
	}

	function hideInfo(){
		var infoProfil = document.getElementsByClassName("profil-info");
		for (var i = 0; i < infoProfil.length; i++) {
			infoProfil[i].style.display = "none";
		}
	}
</script>

</body>
</html>