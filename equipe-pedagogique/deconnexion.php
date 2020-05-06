<?php include("includes/config.inc.php");
include("includes/function.inc.php");
$nom = $_SESSION["nom"];
$prenom = $_SESSION["prenom"];
writeLogs($generalLog, "$nom $prenom;s'est déconnecté");
$_SESSION = array();
session_destroy();
header("Location: index.php");
?>