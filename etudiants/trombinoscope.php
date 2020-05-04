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

$key = "UikPqwDB8c1SHlFAn6FoMryc3610OMbZ";
if (isset($_GET["key"])) {
    if ($_GET["key"] == $key) {
        $filiereLog = $_GET["filiere"];
        $groupeLog = $_GET["groupe"];
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
        $info = error("key");}
}else{
    $info = error("key");}

$json = transformArrayToJSON($info);
header('Content-Type: application/json');
echo($json);

?>