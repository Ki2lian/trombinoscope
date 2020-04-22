<?php

$db = "db.csv";
// Fonctions pour la page index.php (inscription)
function randomKey($length=20){
	$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = '';

	for($i=0; $i<$length; $i++){
		$string .= $chars[rand(0, strlen($chars)-1)];
	}

	return $string;
}

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

function verifMail($db, $email){
	$nbrLignes = file($db);
	$fichier = fopen($db, "r+");

	for ($i=0; $i < sizeof($nbrLignes) ; $i++) { 
		$ligne = fgets($fichier);
		$tableau = explode(";", $ligne);

		if ($tableau[3] == $email) {
			return False;
		}
	}
	fclose($fichier);
	return True;
}

function verifNum($db, $num){
	$meta_carac = array("-", ".", " ");
	$num = str_replace($meta_carac, "", $num);
	$nbrLignes = file($db);
	$fichier = fopen($db, "r+");

	for ($i=0; $i < sizeof($nbrLignes) ; $i++) { 
		$ligne = fgets($fichier);
		$tableau = explode(";", $ligne);


		if ($tableau[4] == $num) {
			return False;
		}
	}
	fclose($fichier);
	return True;
}

function verifDateAnniv($anniversaire){
	if (preg_match('#^([0-9]{4})([-])([0-9]{2})\2([0-9]{2})$#', $anniversaire, $m) == 1 && checkdate($m[3], $m[4], $m[1])){						
		$tableau = explode("-", $anniversaire);
		$anniversaire = mktime(12, 0, 0, $tableau[1], $tableau[2], $tableau[0]);
		return $anniversaire;
	}else{
		return False;
	}
}

function getID($db){
	$nbrLignes = file($db);
	for ($i=0; $i < sizeof($nbrLignes) ; $i++) { 
		$id++;
	}
	return $id;
}

// Fonction pour la connexion

function verifConnexion($db, $email, $password){
	$lignes = file($db);
	$stockInformation = array();
	for ($i=0; $i < sizeof($lignes) ; $i++) {
		$ligne = $lignes[$i];
		$ligne = str_replace("\n", "", $ligne);

		$tableau = explode(";", $ligne);

		if ($tableau[3] == $email && $tableau[7] == hash("sha256", $password . $tableau[11])) {
			for ($i = 0; $i < 13; $i++){
			 $stockInformation[$i] = $tableau[$i];
			}
			return $stockInformation;
		}else{
			return False;
		}
	}
}


// Fonction pour la confirmation du compte

function VerifConfirmation($db, $nom, $prenom, $key){
	$fichier = fopen($db, "r");
	$valeur = 1;
	$tableauStock = array();
    while ($lignes = fgets($fichier)){
	 	$lignes = explode(';', $lignes);
		if ($lignes[1] == $nom && $lignes[2] == $prenom && $lignes[11] == $key && $lignes[12] != 1){
			$validate = 1;

			$stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $lignes[5] . ";" . $lignes[6] . ";" . $lignes[7] . ";" . $lignes[8] . ";" . $lignes[9] . ";" . $lignes[10] . ";" . $lignes[11] . ";" . $valeur . "\n";
		}elseif ($lignes[1] == $nom && $lignes[2] == $prenom && $lignes[11] == $key && $lignes[12] == 1) {
			return 3;
		}else{
		    $stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $lignes[5] . ";" . $lignes[6] . ";" . $lignes[7] . ";" . $lignes[8] . ";" . $lignes[9] . ";" . $lignes[10] . ";" . $lignes[11] . ";" . $lignes[12];
		}
		array_push($tableauStock, $stockerLigne);
	}

	fclose($fichier);
	if ($validate == 1) {
		$fichier = fopen($db, "w");
		for ($i = 0; $i < sizeof($tableauStock); $i++){
			 fputs($fichier, $tableauStock[$i]);
		}
		return 1;
	}else{
		return False;
	}
}

?>