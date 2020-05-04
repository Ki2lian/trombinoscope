<?php include("includes/config.php");

$jsonTextApiFiliere = file_get_contents("https://etudiants.alwaysdata.net/filiere.json");
$jsonArrayApiFiliere = json_decode($jsonTextApiFiliere, True);
$key = "UikPqwDB8c1SHlFAn6FoMryc3610OMbZ";

if (!empty($_POST["filiere"]) && !empty($_POST["groupe"])) {
	$tabTrombi["filière"] = $_POST["filiere"];
    $tabTrombi["groupe"] = $_POST["groupe"];
    $jsonTabTrombi = json_encode($tabTrombi);
  	setcookie("trombi", $jsonTabTrombi, time() +365*24*3600);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Trombinoscope</title>
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
function menu(){
	$jsonTextApiFiliere = file_get_contents("https://etudiants.alwaysdata.net/filiere.json");
	$jsonArrayApiFiliere = json_decode($jsonTextApiFiliere, True);
	echo "<div class=\"menu-api\">";
	?>
	<form method="post">
					<table>
						<tr>
							<td>
								<select class="select-api" onchange="json(this.id);" id="filiere" name="filiere">
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
							<td>
								<select class="select-api" name="groupe" id="opt-groupe">
									<option selected="" disabled="">Choisir un groupe</option>
								</select>
							</td>
							<td>
								<input class="submit-form-trombi2" type="submit" value="Valider" name="form-trombinoscope" />
							</td>
						</tr>
					</table>
				</form>
	<?php
	echo "<input id=\"infos\" class=\"input-api\" type=\"button\" onclick=\"showAllInfo();\" value=\"Plus d'infos\" />";
	echo "<input class=\"input-api\" type=\"button\" onclick=\"hideAllInfo();window.print();\" value=\"Imprimer\" />";
	echo "</div>";
}

function filiereAndGroupe($filiere, $groupe, $key){
	
	$jsonTextApiTrombi = file_get_contents("https://etudiants.alwaysdata.net/test_api?groupe=$groupe&filiere=$filiere&key=$key");
	$jsonArrayApiTrombi = json_decode($jsonTextApiTrombi, True);

	if (isset($jsonArrayApiTrombi[$filiere][$groupe])) {
		echo "<h2 class=\"h2-api\">Le groupe $groupe de la filière $filiere compte " . sizeof($jsonArrayApiTrombi[$filiere][$groupe]). " élèves</h2><br/>\n";
		menu();
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
}

function filiereOnly($filiere,$groupe,$key){
	$jsonTextApiTrombi = file_get_contents("https://etudiants.alwaysdata.net/test_api?filiere=$filiere&key=$key");
	$jsonArrayApiTrombi = json_decode($jsonTextApiTrombi, True);
	
	$jsonTextApiFiliere = file_get_contents("https://etudiants.alwaysdata.net/filiere.json");
	$jsonArrayApiFiliere = json_decode($jsonTextApiFiliere, True);
	for ($i=0; $i < sizeof($jsonArrayApiFiliere["filiere"]); $i++) { 
		if ($filiere == $jsonArrayApiFiliere["filiere"][$i]["nom"]) {
			break;
		}
	}

	// Permet de récupérer le nom des groupes correspondant à la filière recherchée
	$stockGroupe = array();
	for ($k=0; $k < sizeof($jsonArrayApiFiliere["filiere"][$i]["groupe"]); $k++) { 
		$stockGroupe[$k] = $jsonArrayApiFiliere["filiere"][$i]["groupe"][$k];
	}

	// Nombre d'élèves

	for ($j=0; $j < sizeof($stockGroupe); $j++) { 
		$nombreEleves += sizeof($jsonArrayApiTrombi[$filiere][$stockGroupe[$j]]);
	}

	echo "<h2 class=\"h2-api\">La filière $filiere compte " . $nombreEleves . " élèves</h2><br/>\n";
	menu();


	echo "<div class=\"container-api\">";
	$stockAlea = array();
	for ($j=0; $j < sizeof($stockGroupe); $j++) {
		$groupe = $stockGroupe[$j];
		for ($i=0; $i < sizeof($jsonArrayApiTrombi[$filiere][$groupe]); $i++) {
			$info = $jsonArrayApiTrombi[$filiere][$groupe][$i];
			echo "<div class=\"div-api\">\n";
			$alea = mt_rand(0,99999); // on prend une valeur aléatoire car $i recommence à 0 donc va faire des doublons
			while (in_array($alea, $stockAlea)) {
				$alea = mt_rand(0,99999);
			}
			$stockAlea[] = $alea;
			echo "<img src=\"https://etudiants.alwaysdata.net/img/profil/" . $info["image"] . "\" onclick='showInfo(\"profil-" . $alea ."\")' alt=\"Erreur\"/><br/>\n";
			echo "<p>" . $info["nom"] . " " . $info["prenom"] . "</p>\n";
			echo "<p class=\"profil-info\" id=\"profil-" . $alea . "\" style=\"display: none;\">" . $info["email"] . "<br/>" . $info["telephone"] . "</p>\n";
			echo "</div>\n";
		}
	}
	echo "</div>";
}

if (isset($_COOKIE["trombi"]) && !isset($_POST["form-trombinoscope"])) {
	$jsonTabTrombi = $_COOKIE["trombi"];
 	$tabTrombi = json_decode($jsonTabTrombi,True);
 	$filiere = $tabTrombi["filière"];
 	$groupe = $tabTrombi["groupe"];

 	if ($groupe != "filiere-only") {
 		filiereAndGroupe($filiere,$groupe,$key);
 	}else{
 		filiereOnly($filiere,$groupe,$key);
 	}
}


if (isset($_POST["form-trombinoscope"])) {
	if (!empty($_POST["filiere"]) && !empty($_POST["groupe"])) {
		$filiere = $_POST["filiere"];
		$groupe = $_POST["groupe"];

		if ($groupe != "filiere-only") {
			filiereAndGroupe($filiere,$groupe,$key);
		}else{
			filiereOnly($filiere,$groupe,$key);
		}
		
	}else{
		$erreur = "Tous les champs doivent être complétés.";}
}
?>


<?php
if (empty($_POST["filiere"]) && !isset($_COOKIE["trombi"])) {
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


	function showAllInfo(){
		var infoProfil = document.getElementsByClassName("profil-info");
		var bouton = document.getElementById("infos");
		if (bouton.value === "Plus d'infos") {
			for (var i = 0; i < infoProfil.length; i++) {
				infoProfil[i].style.display = "block";
			}
			bouton.value = "Moins d'infos";
		}else{
			hideAllInfo();
		}
	}

	function hideAllInfo(){
		var bouton = document.getElementById("infos");
		var infoProfil = document.getElementsByClassName("profil-info");
		for (var i = 0; i < infoProfil.length; i++) {
			infoProfil[i].style.display = "none";
		}
		bouton.value = "Plus d'infos";
	}
</script>

</body>
</html>