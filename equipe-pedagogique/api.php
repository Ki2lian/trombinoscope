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
		<select>
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

		<input type="submit" value="TEST"/>
	</form>
	

</body>
</html>