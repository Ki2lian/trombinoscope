<?php setlocale(LC_TIME, 'fr', 'fr_FR'); session_start();

$msg = "coucou";

?>
<!DOCTYPE html>
<html>
<head>
	<title>Paramètres</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="../styles/styles.css"/>
	<link rel="icon" type="image/png" href="../img/faviconcoursucp.png" />
</head>
<body>
<header>
  <?php include "../includes/menunav.php" ?>
</header>

<?php echo $msg; ?>

</body>
</html>