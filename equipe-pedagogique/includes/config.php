<?php setlocale(LC_TIME, 'fr', 'fr_FR'); session_start(); // NE PAS ENLEVER !
	
	$db = "db.csv"; // La base de données pour ce site doit être un fichier (csv de préférence).
	$generalLog = "general.log"; // Fichier des logs principaux y seront notés (inscription/connexion/déconnexion)
	$erreurLog = "erreur.log"; // Ficher des logs contenant toutes les erreurs
	$pageLog = $_SERVER['SCRIPT_NAME']; // Variable contenant la page actuelle du visiteur (utile pour les erreurs de logs)

?>