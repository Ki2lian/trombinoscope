<?php include("includes/config.php");
include("includes/function.php");
$nom = $_SESSION["nom"];
$prenom = $_SESSION["prenom"];
writeLogs($generalLog, "$nom $prenom;s'est déconnecté");
$_SESSION = array();
session_destroy();
header("Location: index");
?>