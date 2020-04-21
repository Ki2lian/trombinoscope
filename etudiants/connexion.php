<?php session_start();
if (isset($_POST["form-connexion"])) {
	$pseudo = htmlspecialchars($_POST["pseudo"]);
	$mdp = hash("sha256", $_POST['mdp']);

	if (!empty($pseudo) && !empty($mdp)) {
		
		$lignes = file("bd.csv");

		for ($i=0; $i < sizeof($lignes) ; $i++) { 
			$ligne = $lignes[$i];
			$ligne = str_replace("\n", "", $ligne);

			$tableau = explode(";", $ligne);


			if ($tableau[1] == $pseudo) {
				$continueToMdp = True;
				$erreur = "";
				break;
			}else{
				$erreur = "Le pseudo ou le mot de passe est incorrect !";
				$continueToMdp = False;
			}
		}

		if ($continueToMdp == True) {
			for ($i=0; $i < sizeof($lignes) ; $i++) { 

				if ($tableau[3] == $mdp) {
					$continueToLogin = True;
					$erreur = "";
					break;
				}else{
					$erreur = "Le pseudo ou le mot de passe est incorrect !";
					$continueToLogin = False;
				}
		}

			if ($continueToLogin == True) {
				$_SESSION["id"] = $tableau[0];
				$_SESSION["pseudo"] = $tableau[1];
				$_SESSION["mail"] = $tableau[2];
				$_SESSION["rank"] = $tableau[4];
				header("Location: index.php");
			}


	}

	}else{
		$erreur = "Tous les champs doivent être complétés";
	}
}

?>

<?php
if (!isset($_SESSION["pseudo"])) {
	?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Connexion</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>
<header>
  <?php include "includes/menunav.php" ?>
</header>
<div class="formulaire">
	<!-- <form method="post">
		<table>
			<tr>
				<td>
					<label for="pseudo">Pseudo</label>
				</td>
				<td>
					<input title="Votre pseudo" type="text" maxlength="20" placeholder="Votre pseudo" id="pseudo" name="pseudo" required="" value="<?php if(isset($pseudo)){echo($pseudo);} ?>"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="mdp">Mot de passe</label>
				</td>
				<td>
					<input title="Votre mot de passe" type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" required=""/>
				</td>
			</tr>
		</table>

		<input class="submit-form" type="submit" value="Se connecter" name="form-connexion" />
	</form> -->
<?php
if (isset($erreur)) {
	echo "<font color='red'>". $erreur . "</font>";
}
?>
</div>
<?php
}else{
	header("Location: index.php");
}
?>
<script src="js/script.js"></script>
</body>
</html>