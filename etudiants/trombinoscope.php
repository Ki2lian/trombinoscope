<?php include("includes/config.php"); include("includes/function.php");

function error($nom){
    $tableau["erreur"] = array();

    switch ($nom) {
        case 'key':
            $tableau["erreur"]["code"] = "401";
            $tableau["erreur"]["message"] = "La clé d'api n'est pas valide";
            break;
        case 'filiere':
            $tableau["erreur"]["code"] = "404";
            $tableau["erreur"]["message"] = "La $nom n'existe pas";
            break;
        case 'groupe':
            $tableau["erreur"]["code"] = "404";
            $tableau["erreur"]["message"] = "Le $nom n'existe pas";
            break;
        case 'groupeEtFiliere':
            $tableau["erreur"]["code"] = "404";
            $tableau["erreur"]["message"] = "Le groupe et/ou la filière n'existe pas";
            break;
        case 'erreurMax':
            $tableau["erreur"]["code"] = "404";
            $tableau["erreur"]["message"] = "Vous avez déjà utilisé toutes les requêtes pour cette heure-ci";
            break;
    }
    return $tableau["erreur"];
}

function getAllStudentInfo( $db, $filiere, $group ){

    $tableau = array();
    $jsonTab = array();

    $fichier = fopen($db, "r+");
    $nbrLignes = file($db);
    $compteur = 0;
    for ($i=0; $i < sizeof($nbrLignes) ; $i++) { 
        $ligne = fgets($fichier);
        $tab = explode(";", $ligne);
        

        if ($group != "" && $filiere != "") {
            if ($tab[5] == $filiere && $tab[6] == $group) {
                $compteur += 1;
                $tableau["ID"] = $tab[0];
                $tableau["nom"] = $tab[1];
                $tableau["prenom"] = $tab[2];
                $tableau["email"] = $tab[3];
                $tableau["telephone"] = $tab[4];
                $tableau["image"] = $tab[8];
                $tableau["naissance"] = strftime("%d/%m/%Y", $tab[10]);
                $jsonTab[$filiere][$group][$compteur] = $tableau;
            }
        }elseif ($group == "" && $filiere != "") {
            if ($tab[5] == $filiere) {
                $tableau["ID"] = $tab[0];
                $tableau["nom"] = $tab[1];
                $tableau["prenom"] = $tab[2];
                $tableau["email"] = $tab[3];
                $tableau["telephone"] = $tab[4];
                $tableau["image"] = $tab[8];
                $tableau["naissance"] = strftime("%d/%m/%Y", $tab[10]);

                $jsonTab[$filiere][$tab[6]][] = $tableau;
            }
        }elseif ($group != "" && $filiere == "") {
            if ($tab[6] == $group) {
                $compteur += 1;
                $tableau["ID"] = $tab[0];
                $tableau["nom"] = $tab[1];
                $tableau["prenom"] = $tab[2];
                $tableau["email"] = $tab[3];
                $tableau["telephone"] = $tab[4];
                $tableau["image"] = $tab[8];
                $tableau["naissance"] = strftime("%d/%m/%Y", $tab[10]);

                $jsonTab[$group][$tab[5]][] = $tableau;
            }
        }
    }
    fclose($fichier);
    return $jsonTab;
}

function transformArrayToJSON( $tab ){
    return json_encode($tab);
}

$jsonTextApiFiliere = file_get_contents("https://etudiants.alwaysdata.net/filiere.json");
$jsonArrayApiFiliere = json_decode($jsonTextApiFiliere, True);
$filiere = array();
for ($i=0; $i < sizeof($jsonArrayApiFiliere["filiere"]); $i++) { 
    $filiere[$i] = $jsonArrayApiFiliere["filiere"][$i]["nom"];
    for ($j=0; $j < sizeof($jsonArrayApiFiliere["filiere"][$i]["groupe"]); $j++) { 
        $groupe[] = $jsonArrayApiFiliere["filiere"][$i]["groupe"][$j];
    }
}

function limitationApi($dbApi, $keyURL, $max){
    $fichier = fopen($dbApi, "r");
    $tableauStock = array();

    while ($lignes = fgets($fichier)){
        $lignes = explode(';', $lignes);
        if ($lignes[1] == $keyURL && $lignes[4] != strftime("%H", time())){
            $write = 1;

            $stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . strftime("%H", time()) . ";" . 1 . "\n";

        }elseif ($lignes[1] == $keyURL && $lignes[4] == strftime("%H", time()) && $lignes[5] != $max) {
            $write = 1;
            $valeur = $lignes[5];
            $valeur += 1;
            $stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $valeur . "\n";
        }elseif ($lignes[1] == $keyURL && $lignes[4] == strftime("%H", time()) && $lignes[5] == $max) {
            fclose($fichier);
            return "erreurMax"; // l'utilisateur a déjà utilisé les 20 utilisations par heure
        }
        else{
            $stockerLigne = $lignes[0] . ";" . $lignes[1] . ";" . $lignes[2] . ";" . $lignes[3] . ";" . $lignes[4] . ";" . $lignes[5];
        }

        array_push($tableauStock, $stockerLigne);
    }

    fclose($fichier);
    if ($write == 1) {
        $fichier = fopen($dbApi, "w");
        for ($i = 0; $i < sizeof($tableauStock); $i++){
             fputs($fichier, $tableauStock[$i]);
        }
        fclose($fichier);
        return 1;
    }else{
        return False;
    }
}


$key = "QTAG6uFg7Ta62PIFFRDm3Kp82kkmxVvh";
$keyURL = $_GET["key"];
if (isset($keyURL)) {
    if ($keyURL == $key) {
        $filiereLog = $_GET["filiere"];
        $groupeLog = $_GET["groupe"];

        if (limitationApi($dbApi, $keyURL, $maxApi) != "erreurMax") {
            if (isset($_GET["filiere"]) && !isset($_GET["groupe"])) {
                if (!empty($_GET["filiere"])) {
                    if (in_array($_GET["filiere"], $filiere)) {
                        $info = getAllStudentInfo( $db, $_GET["filiere"], "" );
                        writeLogs($apiLog, "$key;1;a utilisé l'api pour voir la filière;$filiereLog;none"); // le chiffre après $key permet de savoir quelle action a été faite.
                    }else{
                        $info = error("filiere");}  
                }else{
                    $info = error("filiere");}

            }elseif (!isset($_GET["filiere"]) && isset($_GET["groupe"])) {
                if (!empty($_GET["groupe"])) {
                    if (in_array($_GET["groupe"], $groupe)) {
                        $info = getAllStudentInfo( $db,"", $_GET["groupe"] );
                        writeLogs($apiLog, "$key;2;a utilisé l'api pour voir le groupe;none;$groupeLog");
                    }else{
                        $info = error("groupe");}
                }else{
                    $info = error("groupe");}



            }elseif (isset($_GET["filiere"]) && isset($_GET["groupe"])) {
                if (!empty($_GET["filiere"]) && !empty($_GET["groupe"]) && in_array($_GET["filiere"], $filiere) && in_array($_GET["groupe"], $groupe)) {
                    $info = getAllStudentInfo( $db, $_GET["filiere"], $_GET["groupe"] );
                    writeLogs($apiLog, "$key;3;a utilisé l'api pour voir la filière et le groupe;$filiereLog;$groupeLog");
                }else{
                    $info = error("groupeEtFiliere");}
            }
        }else{
            $info = error("erreurMax"); writeLogs($erreurLog, "$keyURL;$pageLog;a atteint le maximum d'utilisation pour cette heure;none");}
    }else{
        $info = error("key");}
}else{
    $info = error("key");}

$json = transformArrayToJSON($info);
header('Content-Type: application/json');
echo($json);

?>