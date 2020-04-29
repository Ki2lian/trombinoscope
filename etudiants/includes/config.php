<?php setlocale(LC_TIME, 'fr', 'fr_FR'); session_start(); // NE PAS ENLEVER !
	
	$db = "db.csv"; // La base de données pour ce site doit être un fichier (csv de préférence).
	// La base de données est comme ceci:
	// id;nom;prenom;email;telephone;filière;groupe;mot de passe hasher avec une clé;image;timestamp d'inscription;date de naissance;clé aléatoire de 32 caractères;0 (si tu n'as pas confirmé)/ 1 (si ton compte est confirmé).

	$dbApi = "dbapi.csv"; // La base de données pour les demandeurs de l'api
	// La base de données est comme ceci:
	// id;clé;mot de passe hasher avec la clé;email;heure;nombre d'utilisation

	$generalLog = "general.log"; // Fichier des logs principaux y seront notés (inscription/connexion/déconnexion)
	$modifLog = "modif.log"; // Fichier des logs des modifications du profil
	$apiLog = "api.log"; // Fichier des logs contenant toutes les requêtes de l'api

	// Ne pas modifier, cela permet de vérifier si la page actuelle est en https ou non
	if (isset($_SERVER['HTTPS'])) {
		$protocol = "https";
	}else{
		$protocol = "http";
	}

	$url = $_SERVER['HTTP_HOST'];

	// Permet de faire une requête pour avoir les filières
	$jsonTextApiFiliere = file_get_contents("filiere.json");
	$jsonArrayApiFiliere = json_decode($jsonTextApiFiliere, True);
?>