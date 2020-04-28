<?php include("includes/config.php"); include("includes/function.php");

function error(){
    $tableau["erreur"] = array();
    $tableau["erreur"]["code"] = "401";
    $tableau["erreur"]["message"] = "La clé d'api n'est pas valide";

    return $tableau["erreur"];
}

function transformArrayToJSON( $tab ){
    return json_encode($tab);
}



$key = "UikPqwDB8c1SHlFAn6FoMryc3610OMbZ";
if (isset($_GET["key"])) {
    if (!empty($_GET["key"])) {
    	$tableau = array();
		$jsonTab = array();
        if (isset($_GET["filiere"]) && !isset($_GET["groupe"])) {
        	$tableau["ID"] = 9;
        	$tableau["nom"] = "Dupont";
			$tableau["prenom"] = "Jean";
			$tableau["email"] = "JeanDupont@gmail.com";
			$tableau["telephone"] = "0623456789";
		    $tableau["image"] = "defaut.png";
		    $tableau["naissance"] = "20/04/2000";
		    $jsonTab["LPI-RIWS"]["A1"]["1"] = $tableau;
		    $tableau["ID"] = 5;
		    $tableau["nom"] = "Martin";
			$tableau["prenom"] = "Cédric";
			$tableau["email"] = "CedMar@aol.fr";
			$tableau["telephone"] = "0681225041";
		    $tableau["image"] = "defaut.png";
		    $tableau["naissance"] = "18/11/2001";
        	$jsonTab["LPI-RIWS"]["A1"]["2"] = $tableau;
        	$tableau["ID"] = 13;
        	$tableau["nom"] = "Anguille";
			$tableau["prenom"] = "Chloé";
			$tableau["email"] = "Chloelafolle@yahoo.fr";
			$tableau["telephone"] = "0717308018";
		    $tableau["image"] = "defaut.png";
		    $tableau["naissance"] = "28/05/1999";
        	$jsonTab["LPI-RIWS"]["A2"]["1"] = $tableau;
		    $info = $jsonTab;

        }elseif (!isset($_GET["filiere"]) && isset($_GET["groupe"])) {
        	$tableau["ID"] = 17;
        	$tableau["nom"] = "Arpin";
			$tableau["prenom"] = "William";
			$tableau["email"] = "WilliamARPIN@hotmail.com";
			$tableau["telephone"] = "0798765432";
		    $tableau["image"] = "defaut.png";
		    $tableau["naissance"] = "10/09/1999";
        	$jsonTab["B2"]["L1-MIPI"]["1"] = $tableau;
        	$tableau["ID"] = 26;
        	$tableau["nom"] = "Pillot";
			$tableau["prenom"] = "Christophe";
			$tableau["email"] = "PillChristo@hotmail.com";
			$tableau["telephone"] = "0621371204";
		    $tableau["image"] = "defaut.png";
		    $tableau["naissance"] = "09/07/1998";
        	$jsonTab["B2"]["L1-MIPI"]["2"] = $tableau;
        	$tableau["ID"] = 12;
        	$tableau["nom"] = "Chaise";
			$tableau["prenom"] = "Marie";
			$tableau["email"] = "MarieChaise@hotmail.com";
			$tableau["telephone"] = "0618541320";
		    $tableau["image"] = "defaut.png";
		    $tableau["naissance"] = "14/10/1997";
        	$jsonTab["B2"]["L1-MIPI"]["3"] = $tableau;
            $info = $jsonTab;

        }elseif (isset($_GET["filiere"]) && isset($_GET["groupe"])) {
        	$tableau["ID"] = 4;
            $tableau["nom"] = "Savoie";
			$tableau["prenom"] = "Nadine";
			$tableau["email"] = "NadineSavoie@hotmail.com";
			$tableau["telephone"] = "0769438758";
		    $tableau["image"] = "defaut.png";
		    $tableau["naissance"] = "17/03/1998";
        	$jsonTab["L2-MIPI"]["C1"]["1"] = $tableau;
        	$tableau["ID"] = 21;
        	$tableau["nom"] = "Mercier";
			$tableau["prenom"] = "Olivier";
			$tableau["email"] = "OlivierMercier@hotmail.com";
			$tableau["telephone"] = "0794911097";
		    $tableau["image"] = "defaut.png";
		    $tableau["naissance"] = "08/02/2000";
        	$jsonTab["L2-MIPI"]["C1"]["2"] = $tableau;
            $info = $jsonTab;
        }
    }else{
        $info = error();}
}else{
    $info = error();}

/*$tableau = array();
$jsonTab = array();
$tableau["nom"] = "LPI-RIWS";
$tableau["groupe"][] = "A1";
$tableau["groupe"][] = "A2";
$jsonTab["filiere"][] = $tableau;
$tableau = array();
$tableau["nom"] = "L1-MIPI";
$tableau["groupe"][] = "B1";
$tableau["groupe"][] = "B2";
$jsonTab["filiere"][] = $tableau;
$tableau = array();
$tableau["nom"] = "L2-MIPI";
$tableau["groupe"][] = "C1";
$tableau["groupe"][] = "C2";
$jsonTab["filiere"][] = $tableau;
$tableau = array();
$tableau["nom"] = "L3-I";
$tableau["groupe"][] = "D1";
$tableau["groupe"][] = "D2";
$jsonTab["filiere"][] = $tableau;
$tableau = array();
$tableau["nom"] = "LP RS";
$tableau["groupe"][] = "E1";
$tableau["groupe"][] = "E2";
$jsonTab["filiere"][] = $tableau;
$info = $jsonTab;*/

$json = transformArrayToJSON($info);
header('Content-Type: application/json');
echo($json);

?>