<?php

// Fonctions pour la page index.php (inscription)

	// Génère une clé aléatoire
function randomKey($length=20){
	$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = '';

	for($i=0; $i<$length; $i++){
		$string .= $chars[rand(0, strlen($chars)-1)];
	}

	return $string;
}

	// Vérifie si le prénom et le nom de famille n'existe pas déjà dans la base de données
function verifName($db, $lname, $fname){
	$lignes = file($db);
	for ($i=0; $i < sizeof($lignes) ; $i++) {
		$ligne = $lignes[$i];
		$ligne = str_replace("\n", "", $ligne);

		$tableau = explode(";", $ligne);

		if ($tableau[1] == $lname && $tableau[2] == $fname) {
			return False;
		}
	}
	return True;
}

	// Vérifie si l'émail n'existe pas déjà dans la base de données
function verifMail($db, $email){
	$nbrLignes = file($db);
	$fichier = fopen($db, "r+");

	for ($i=0; $i < sizeof($nbrLignes) ; $i++) { 
		$ligne = fgets($fichier);
		$tableau = explode(";", $ligne);

		if ($tableau[3] == $email) {
			fclose($fichier);
			return False;
		}
	}
	fclose($fichier);
	return True;
}

	// Pour obtenir l'id (on fera +1 pour l'inscription car c'est un nouvel id)
function getID($db){
	$nbrLignes = file($db);
	for ($i=0; $i < sizeof($nbrLignes) ; $i++) { 
		$id++;
	}
	return $id;
}

// Fonction pour la connexion

	// Vérifie si l'email, le mot de passe sont valides
function verifConnexion($db, $email, $password){
	$lignes = file($db);
	$stockInformation = array();
	for ($i=0; $i < sizeof($lignes) ; $i++) {
		$ligne = $lignes[$i];
		$ligne = str_replace("\n", "", $ligne);

		$tableau = explode(";", $ligne);

		if ($tableau[3] == $email && $tableau[4] == hash("sha256", $password . $tableau[6])) {
			for ($i = 0; $i < 7; $i++){
			 $stockInformation[$i] = $tableau[$i];
			}
			return $stockInformation;
		}elseif($i == sizeof($lignes)-1){
			return False;
		}
	}
}

	// Écrit dans le fichier qu'on veut avec le message qu'on veut
function writeLogs($fichier, $message){
	$fichier = fopen( "logs/" . $fichier, "a+");
	$write = strftime("%d/%m/%Y à %T", time()) . ";" . mktime() . ";" . $message . "\n";
	fputs($fichier, $write);
	fclose($fichier);
}

?>