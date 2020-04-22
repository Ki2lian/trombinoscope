<?php
session_start();
include("config/function.php");
$nom = $_SESSION["nom"];
$prenom = $_SESSION["prenom"];
writeLogs("logs/general.log", "$nom $prenom;s'est déconnecté");
$_SESSION = array();
session_destroy();
header("Location: index.php");
?>