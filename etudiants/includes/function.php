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
			fclose($fichier);
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
			fclose($fichier);
			return False;
		}
	}
	fclose($fichier);
	return $num;
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
function changeAvatar($name){
	$tailleMax = 2097152; // 2 Mo
	$extensionsValides = array("jpg", "jpeg", "png");
	$id = $_SESSION["id"];

	if ($_FILES[$name]["size"] <= $tailleMax ) {
		$extensionUpload = strtolower(substr(strrchr($_FILES[$name]["name"], "."), 1));
		if (in_array($extensionUpload, $extensionsValides)) {

			// Suppression des images peu importe l'extension
			$avatarJpg = "img/profil/" . $id . ".jpg";
			$avatarJpeg = "img/profil/" . $id . ".jpeg";
			$avatarPng = "img/profil/" . $id . ".png";
			if( file_exists ( $avatarJpg)){
     			unlink( $avatarJpg ) ;
			}
			if( file_exists ( $avatarJpeg)){
     			unlink( $avatarJpeg ) ;
			}
			if( file_exists ( $avatarPng)){
     			unlink( $avatarPng ) ;
			}

			$chemin = "img/profil/" . $id . "." . $extensionUpload;
			$resultat = move_uploaded_file($_FILES[$name]["tmp_name"], $chemin);
			if ($resultat) {
				$_SESSION["avatar"] = $id . "." . $extensionUpload;
				putAvatarCodeInDb("db.csv");

				return "ça a marché";
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

	// Écrit dans le fichier qu'on veut avec le message qu'on veut
function writeLogs($fichier, $message){
	$fichier = fopen( "logs/" . $fichier, "a+");
	$write = strftime("%d/%m/%Y à %T", time()) . ";" . mktime() . ";" . $message . "\n";
	fputs($fichier, $write);
	fclose($fichier);
}

// Fonctions pour générer des comptes

	// Création de comptes
function genereAccount($db, $nombre=20){
	$prenoms = array("William","Eugène","Arianne","Evrard","Madelene","Aurore","Marguerite","Philippine","Fabienne","Eustache","Senapus","Jean","Baptiste","Corette","Honore","Thomas","André","Benjamin","Rémy","Amaury","Aubin","Jeanne","Elena","Salomé","Clara","Léa","Emma","Marie","Lola","Sarah","Erwann","Adrien","Paul","Margot","Madisson","Nora","Claire","Nolwenn","Chantal","Roméo","Juliette","Gérard","Jacques","Michel","Pierre","Gaëtan","Jason","Chris","Damien","Jordan","Lucas","Maxime","Valentin","Théo","Guillaume","Marcel","Clément");
	$noms = array("Arpin","Faubert","Guibord","Lapointe","Gougeon","Labelle","Givry","Lazure","Rodrigue","Bernard","Boivin","Daigle","Chalifour","Compagnon","Bisaillon","Noël","Trépanier","Gagnon","Bernier","Auberjonois","Louineaux","Patenaude","Bourgeois","Dupont","Carignan","Martin","Boisclair","Desjardins","Charette","Gabriaux","Bonenfant","Flamand","Quiron","Gousse","Lereau");
	$img = "defaut.png";
	$fichier = fopen($db, "a+");

	for ($i=0; $i < $nombre ; $i++) {
		$rand_prenoms = array_rand($prenoms, 2);
		$rand_noms = array_rand($noms, 2);
		$nom = $noms[$rand_noms[0]];
		$prenom = $prenoms[$rand_prenoms[0]];
		$mail = randMail($nom, $prenom);
		$telephone = randTelephone();
		$filiere = randForG("filiere");
		$groupe = randForG("groupe");
		$anniv = randAnniv();
		$key = randomKey(32);
		$mdp = hash("sha256", strtolower($nom[4] . $prenom[4]) . $key);
		$id = getID($db);


		if (verifName($db, $nom, $prenom) == True) {
			while (verifNum($db, $telephone) == False) {
				$telephone = randTelephone();
			}
			fputs($fichier, $id+1 . ";" . $nom . ";" . $prenom . ";" . $mail . ";" . $telephone . ";" . $filiere . ";" . $groupe . ";" . $mdp . ";" . $img . ";" . mktime() . ";" . $anniv . ";" . $key . ";1" . "\n");
			echo "Le compte $nom $prenom a été créé.<br/>";
			writeLogs("general.log", "$nom $prenom;a été créé par l'administrateur");
		}else{
			echo "Le compte $nom $prenom existe déjà, il n'a pas pu être créé.<br/>";
			writeLogs("general.log", "$nom $prenom;le compte existe déjà, il n'a pas pu être crée");
		}
	}
	$fichier = fopen($db, "a+");
}

	// Génère une adresse email selon le nom et le prénom et après l'arobase, c'est aléatoire.
function randMail($nom, $prenom){
	$mails = array("gmail.com","yahoo.fr","hotmail.fr","hotmail.com","outlook.com","sfr.fr","orange.fr","free.fr","email.fr","live.fr","laposte.net","aol.com","sfr.com","francetv.fr","msn.com","voila.fr");
	$rand_mails = array_rand($mails, 2);
	$mail = $nom . $prenom . "@" . $mails[$rand_mails[0]];
	return $mail;
}

	// Génère un numéro de téléphone
function randTelephone(){
	$numero = "0" . mt_rand(6,7);
	for ($i=0; $i < 4; $i++) { 
		$nombre = mt_rand(1,99);

		if ($nombre < 10) {
			$nombre = "0" . $nombre;
		}

		$numero .= strval($nombre);
	}
	return $numero;
}


	// Génère une date d'anniversaire aléatoire
function randAnniv(){
	$annees = mt_rand(1997,2002);

	$mois = mt_rand(1,12);
	if ($mois < 10) {
		$mois = "0" . $mois;
	}

	$jour = mt_rand(1,28);
	if ($jour < 10) {
		$jour = "0" . $jour;
	}

	$anniv = $annees . "-" . $mois . "-" . $jour;

	$anniv = verifDateAnniv($anniv);
	return $anniv;
}

	// Donne une filière aléatoire ou un groupe aléatoire (F = filière, G = groupe)
function randForG($choix){

	if ($choix == "filiere") {
		$filieres = array("L1-MIPI","L2-MIPI", "L3-I", "LP RS", "LPI-RIWS");
		$rand_filieres = array_rand($filieres, 2);
		return $filieres[$rand_filieres[0]];
	}elseif ($choix == "groupe") {
		$groupes = array("A1","B2","LPI-1","LPI-2","LPI-3");
		$rand_groupes = array_rand($groupes, 2);
		return $groupes[$rand_groupes[0]];
	}else{
		return "Ce choix n'existe pas.";
	}
}

// Fonctions pour modifier le profil

	// Modification des informations générales
function ModifGeneral($db, $nom, $prenom, $telephone, $filiere, $groupe, $anniv, $erreur=False){
	$fichier = fopen($db, "r");
	$id = $_SESSION["id"];

	$_SESSION["nom"] = $nom;
	$_SESSION["prenom"] = $prenom;
	$_SESSION["numero"] = $telephone;
	$_SESSION["filiere"] = $filiere;
	$_SESSION["groupe"] = $groupe;
	$_SESSION["anniv"] = $anniv;

	$tableauStock = array();
    while ($lignes = fgets($fichier)){
	 	$lignes = explode(';', $lignes);
		if ($lignes[0] == $id){

			$stockerLigne = $lignes[0] . ";" . $nom . ";" . $prenom . ";" . $lignes[3] . ";" . $telephone . ";" . $filiere . ";" . $groupe . ";" . $lignes[7] . ";" . $lignes[8] . ";" . $lignes[9] . ";" . $anniv . ";" . $lignes[11] . ";" .  $lignes[12];

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
	if ($erreur == True) {
		header('refresh:5;url=profil');
	}else{
		header("location: profil");
	}

}

	// Fonction qui vérifie si le mot de passe actuel est bon ou non
function verifPassword($db, $password){
	$id = $_SESSION["id"];
	$lignes = file($db);
	for ($i=0; $i < sizeof($lignes) ; $i++) {
		$ligne = $lignes[$i];
		$ligne = str_replace("\n", "", $ligne);

		$tableau = explode(";", $ligne);

		if ($tableau[0] == $id && $tableau[7] == hash("sha256", $password . $tableau[11])) {
			return True;
		}
	}
	return False;
}

function modifPassword($db, $password){
	$fichier = fopen($db, "r");
	$tableauStock = array();
	$id = $_SESSION["id"];

    while ($lignes = fgets($fichier)){
	 	$lignes = explode(';', $lignes);
		if ($lignes[0] == $id){

			$stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $lignes[5] . ";" . $lignes[6] . ";" . hash("sha256", $password . $lignes[11]) . ";" . $lignes[8] . ";" . $lignes[9] . ";" . $lignes[10] . ";" . $lignes[11] . ";" . $lignes[12];

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

function modifMail($db, $mail){
	$fichier = fopen($db, "r");
	$tableauStock = array();
	$id = $_SESSION["id"];

	$_SESSION["mail"] = $mail;
    while ($lignes = fgets($fichier)){
	 	$lignes = explode(';', $lignes);
		if ($lignes[0] == $id){

			$stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $mail . ";" . $lignes[4] . ";" . $lignes[5] . ";" . $lignes[6] . ";" . $lignes[7] . ";" . $lignes[8] . ";" . $lignes[9] . ";" . $lignes[10] . ";" . $lignes[11] . ";" . $lignes[12];

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
	header("location: profil");
}



?>

