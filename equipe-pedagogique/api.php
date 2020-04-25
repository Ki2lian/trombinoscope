<?php

$json = file_get_contents("https://etudiants.alwaysdata.net/test_api?filiere=L1-MIPI&groupe=A1");
$json = json_decode($json,True);

print_r($json);

?>