<?php include("includes/config.php");

$json = file_get_contents("https://etudiants.alwaysdata.net/test_api?filiere=L1-MIPI&groupe=A1");
$json = json_decode($json,True);

//print_r($json);


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
		<label><input type="radio" value="L1-MIPI" name="filiere"/>L1-MIPI</label>
		<label><input type="radio" value="L2-MIPI" name="filiere"/>L2-MIPI</label>
		<label><input type="radio" value="L3-I" name="filiere"/>L3-I</label>
		<label><input type="radio" value="LP RS" name="filiere"/>LP RS</label>
		<label><input type="radio" value="LPI-RIWS" name="filiere"/>LPI-RIWS</label>

		<input type="submit" value="TEST"/>
	</form>
	

</body>
</html>