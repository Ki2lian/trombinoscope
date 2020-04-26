<?php setlocale(LC_TIME, 'fr', 'fr_FR'); session_start(); // NE PAS ENLEVER !
	
	$db = "db.csv"; // La base de données pour ce site doit être un fichier (csv de préférence).
	$generalLog = "general.log"; // Fichier des logs principaux y seront notés (inscription/connexion/déconnexion)
	$modifLog = "modif.log"; // Fichier des logs des modifications du profil
	$apiLog = "api.log"; // Fichier des logs contenant toutes les requêtes de l'api
?>