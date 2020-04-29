<?php include("includes/config.php");

$jsonText = file_get_contents("https://etudiants.alwaysdata.net/filiere.json");
$jsonArray = json_decode($jsonText,True);


// print_r($jsonArray);

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
</style>
<body>

<header>
  <?php include "includes/menunav.php" ?>
</header>
	<form method="get">
		<select name="filiere">
			<option selected="" disabled="">Choisir une filière</option>
				<?php 
					for ($i=0; $i < sizeof($jsonArray["filiere"]); $i++) { 
						$jsonNom = $jsonArray["filiere"][$i]["nom"];
						?>
						<option value="<?php echo $jsonNom ?>"><?php echo $jsonNom ?></option>
						<?php
					}
				?>			
		</select name="groupe" id="opt-groupe">
		<select>
			<option selected="" disabled="">Choisir un groupe</option>
		</select>

		<input type="submit" value="TEST"/>
	</form>

<script type="text/javascript">

	function json(id){
		var option = document.getElementById("opt-groupe");
		var json = <?php echo $jsonTextApiFiliere; ?>;
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

</body>
</html>