<?php include("config.php");

$json = file_get_contents("https://etudiants.alwaysdata.net/test_api?filiere=L1-MIPI&groupe=A1");
$json = json_decode($json,True);

print_r($json);

?>
<!DOCTYPE html>
<html>
<head>
	<title>API</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>

<header>
  <?php include "includes/menunav.php" ?>
</header>

</body>
</html>