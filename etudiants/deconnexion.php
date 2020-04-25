<?php
session_start();
include("includes/function.php");
$nom = $_SESSION["nom"];
$prenom = $_SESSION["prenom"];
writeLogs("general.log", "$nom $prenom;s'est déconnecté");
$_SESSION = array();
session_destroy();
header("Location: index.php");
?>