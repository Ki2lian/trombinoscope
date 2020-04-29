<?php include("includes/config.php");

$jsonTextApiFiliere = file_get_contents("https://etudiants.alwaysdata.net/filiere.json");
$jsonArrayApiFiliere = json_decode($jsonTextApiFiliere, True);

?>
<!DOCTYPE html>
<html>
<head>
	<title>API</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<style type="text/css">
	.btn {
	  background-color: #0f7dbc;
	  border: none;
	  color: #fff;
	  padding: 15px 32px;
	  text-align: center;
	  text-decoration: none;
	  display: inline-block;
	  font-size: 16px;
	  font-weight: 700;
	  border-radius: 2px;
	  cursor: pointer;
	  margin-bottom: 5px;
	  transition: background-color .2s;
	}
	.btn:hover {
	    background-color: #128ed5;
	}

	img{
		height: 150px;
	}

	div{
		text-align: center;
		float: left;
		border-radius: 5%;
		margin: 2%;
		font-weight: bold;
	}
</style>
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
			for ($i=1; $i <= sizeof($jsonArrayApiTrombi[$filiere][$groupe]); $i++) { 
				$info = $jsonArrayApiTrombi[$filiere][$groupe][$i];
				echo "<div>";
				echo "<img src=\"https://etudiants.alwaysdata.net/img/profil/" . $info["image"] . "\" alt=\"Erreur\"/><br/>";
				echo $info["nom"] . " " . $info["prenom"];
				echo "</div>";
			}
		}
	}else{
		$erreur = "Tous les champs doivent être complétés.";}
}
?>


<?php
if (!isset($_POST["form-trombinoscope"])) {
?>
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
		<input type="submit" value="Valider" name="form-trombinoscope" />
	</form>
<?php } ?>
<script type="text/javascript">

	function json(id){
		var option = document.getElementById("opt-groupe");
		var json = <?php echo $jsonTextApiFiliere; ?>;
		var nomFiliere = document.getElementById(id).value;
		option.innerHTML = "";
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

</body>
</html>