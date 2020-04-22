<?php
$db = "db.csv";

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
			return False;
		}
	}
	fclose($fichier);
	return True;
}

	// Vérifie si le numéro de téléphone n'existe pas déjà dans la base de données
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

	// Vérifie si la date d'anniversaire est valide ou non (pour éviter le changement de type depuis inspécter l'élément, t'as pas essayé hein !)
function verifDateAnniv($anniversaire){
	if (preg_match('#^([0-9]{4})([-])([0-9]{2})\2([0-9]{2})$#', $anniversaire, $m) == 1 && checkdate($m[3], $m[4], $m[1])){						
		$tableau = explode("-", $anniversaire);
		$anniversaire = mktime(12, 0, 0, $tableau[1], $tableau[2], $tableau[0]);
		return $anniversaire;
	}else{
		return False;
	}
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

		if ($tableau[3] == $email && $tableau[7] == hash("sha256", $password . $tableau[11])) {
			for ($i = 0; $i < 13; $i++){
			 $stockInformation[$i] = $tableau[$i];
			}
			return $stockInformation;
		}elseif($i == sizeof($lignes)-1){
			return False;
		}
	}
}


// Fonction pour la confirmation du compte

	// Vérifie si le compte existe, s'il n'est pas validé ou s'il est déjà validé
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


// Fonction pour changer d'image d'avatar

	// Met le code de l'image dans la base de données
function putAvatarCodeInDb($db){
	$fichier = fopen($db, "r");
	$tableauStock = array();
	$id = $_SESSION["id"];
	$valeur = $_SESSION["avatar"];

    while ($lignes = fgets($fichier)){
	 	$lignes = explode(';', $lignes);
		if ($lignes[0] == $id){
			$validate = 1;

			$stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $lignes[5] . ";" . $lignes[6] . ";" . $lignes[7] . ";" . $valeur . ";" . $lignes[9] . ";" . $lignes[10] . ";" . $lignes[11] . ";" .  $lignes[12];
		}else{
		    $stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $lignes[5] . ";" . $lignes[6] . ";" . $lignes[7] . ";" . $lignes[8] . ";" . $lignes[9] . ";" . $lignes[10] . ";" . $lignes[11] . ";" . $lignes[12];
		}
		array_push($tableauStock, $stockerLigne);
	}

	fclose($fichier);
	$fichier = fopen($db, "w");
	for ($i = 0; $i < sizeof($tableauStock); $i++){
		fputs($fichier, $tableauStock[$i]);
	}

}

	// Vérifie la taille de l'image, l'extension de l'image et 
function changeAvatar(){
	$tailleMax = 2097152; // 2 Mo
	$extensionsValides = array("jpg", "jpeg", "png");
	$id = $_SESSION["id"];
	if ($_FILES["avatar"]["size"] <= $tailleMax ) {
		
		$extensionUpload = strtolower(substr(strrchr($_FILES["avatar"]["name"], "."), 1));
		if (in_array($extensionUpload, $extensionsValides)) {
			
			$chemin = "img/profil/" . $id . "." . $extensionUpload;
			$resultat = move_uploaded_file($_FILES["avatar"]["tmp_name"], $chemin);
			if ($resultat) {
				// Prend l'image actuel pour la remplacer avec la nouvelle dans la base de données
				$_SESSION["avatar"] = $id . "." . $extensionUpload;
				putAvatarCodeInDb("db.csv");

			}else{
				return "erreur1";
			}

		}else{
			return "erreur2";
		}

	}else{
		return "erreur3";
	}
}




?>