<?php include("includes/function.inc.php"); include("includes/config.inc.php");

if (isset($_POST["form-demand-key"])) {
	$mail = htmlspecialchars($_POST["mail"]);
	$key = randomKey(32);
	$mdp = hash("sha256", $_POST["mdp"] . $key);
	$mdp2 = hash("sha256", $_POST["mdp2"] . $key);
	$id = getID($dbApi);


	if (!empty($_POST["mail"]) && !empty($_POST["mdp"]) && !empty($_POST["mdp2"])) {
		if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			if (verifMail($dbApi, $mail) == True) {
				if (strlen($_POST["mdp"]) >= 8 || strlen($_POST["mdp2"]) >= 8) {
					if ($mdp == $mdp2) {
						$fichier = fopen($dbApi, "a+");
						fputs($fichier, $id+1 . ";" . $key . ";" . $mdp . ";" . $mail . ";" . strftime("%H", time()) . ";0" . "\n");
						$message = "Bonjour, vous nous avez fait la demande pour une clé d'api, vous pourrez l'utiliser en suivant la documentation en cliquant sur ce lien https://etudiants.alwaysdata.net/api. Voici votre clé: $key";
						writeLogs($apiLog, "$mail;a demandé une clé d'API, sa clé: $key");
						//mail($mail, "Clé d'API", $message);
						//$inscriptionOK = "Vous avez obtenu votre clé dans votre mail.";
						$inscriptionOK = "Votre clé: $key";
					}else{
						$erreur = "Les mots de passe ne correspondent pas."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;none");}
				}else{
					$erreur = "Votre mot de passe doit contenir au moins 8 caractères."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;none");}
			}else{
				$erreur = "L'adresse email a déjà été utilisée."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$mail");}
		}else{
			$erreur = "L'adresse email n'est pas valide."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur;$mail");}
	}else{
		$erreur = "Tous les champs doivent être complétés."; writeLogs($erreurLog, "anonyme;$pageLog;$erreur");}
}

if (isset($_POST["form-recup-key"])) {
	$mail = htmlspecialchars($_POST["mail"]);
	$mdp = htmlspecialchars($_POST["mdp"]);
	if (!empty($_POST["mail"]) && !empty($_POST["mdp"])) {
		if (verifConnexion($dbApi, $mail, $mdp, 3, 2, 1) != False) {
			$tableau = verifConnexion($dbApi, $mail, $mdp, 3, 2, 1);
			$recupKey = "Votre clé: $tableau[1]";
			$recupUtil = "Utilisation: $tableau[5]/$maxApi";
			writeLogs($apiLog, "$tableau[3];a fait une demande pour récupérer sa clé");
		}else{
			$erreurRecup = "L'email ou le mot de passe est incorrect !";  writeLogs($erreurLog, "anonyme;$pageLog;$erreurRecup;$mail");}
	}else{
		$erreurRecup = "Tous les champs doivent être complétés."; writeLogs($erreurLog, "anonyme;$pageLog;$erreurRecup;none");}}?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Trombinoscope: API</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<body>
	<header>
	  <?php include "includes/menunav.inc.php" ?>
	</header>
	<!-- Inspiré de https://openweathermap.org/current -->
	<div class="container">
		
	
	<div class="container-left">
		<h2 class="h2-doc">Documentation</h2>
			<p class="lead">
				Accédez aux données des filières et des groupes !<br/>
				Les données sont disponibles au format JSON.
			</p>
			<div class="information">
				<h4>	
					N'oubliez pas que tous les exemples d'appels API répertoriés sur cette page ne sont que des exemples et n'ont aucune connexion avec le véritable service API ! Par ailleurs, l'API est limitée à <?php echo $maxApi ?> utilisations par heure pour une clé.
				</h4>
			</div>
			<h2 class="h2-doc">Par filière</h2>
				<div class="filiere">
					<h4 class="h4-doc">Description:</h4>
					Permet de récupérer les informations des étudiants de la filière souhaitée.
					<h4 class="h4-doc">Appel d'API:</h4>
						<code class="bold">
							<?php echo $protocol ?>://<?php echo $url ?>/trombinoscope?filiere={nom de la filière}&amp;key={votre clé d'api}
						</code>
					<h4 class="h4-doc">Exemple d'appel d'API:</h4>
						<a href="exemple?filiere=LPI-RIWS&amp;key=UikPqwDB8c1SHlFAn6FoMryc3610OMbZ" target="_blank"><?php echo $protocol ?>://<?php echo $url ?>/trombinoscope?filiere=LPI-RIWS</a>
					<h4>Réponse d'API:</h4>
					<pre>{
  "LPI-RIWS": {
    "A1": {
      "1": {
        "ID": 9,
      	"nom": "Dupont",
      	"prenom": "Jean",
      	"email": "JeanDupont@gmail.com",
      	"telephone": "0623456789",
      	"image": "defaut.png",
      	"naissance": "20/04/2000"
      },
      "2": {
        "ID": 8,
      	"nom": "Martin",
      	"prenom": "Cédric",
      	"email": "CedMar@aol.fr",
      	"telephone": "0681225041",
      	"image": "defaut.png",
      	"naissance": "18/11/2001"
      }
  },
  "A2": {
    "1": {
        "ID": 13,
      	"nom": "Anguille",
      	"prenom": "Chloé",
      	"email": "Chloelafolle@yahoo.fr",
      	"telephone": "0717308018",
      	"image": "defaut.png",
      	"naissance": "28/05/1999"
      }
  },
}
</pre>
				</div>
			<h2 class="h2-doc">Par groupe</h2>
				<div class="groupe">
					<h4 class="h4-doc">Description:</h4>
					Permet de récupérer les informations des étudiants du groupe souhaité.
					<h4 class="h4-doc">Appel d'API:</h4>
					<code class="bold">
							<?php echo $protocol ?>://<?php echo $url ?>/trombinoscope?groupe={nom du groupe}&amp;key={votre clé d'api}
						</code>
					<h4 class="h4-doc">Exemple d'appel d'API:</h4>
						<a href="exemple?groupe=B2&amp;key=UikPqwDB8c1SHlFAn6FoMryc3610OMbZ" target="_blank"><?php echo $protocol ?>://<?php echo $url ?>/trombinoscope?groupe=B2</a>
					<h4>Réponse d'API:</h4>
<pre>{
  "B2": {
    "LPI-RIWS": {
      "1": {
        "ID": 17,
      	"nom": "Arpin",
      	"prenom": "William",
      	"email": "WilliamARPIN@hotmail.com",
      	"telephone": "0798765432",
      	"image": "defaut.png",
      	"naissance": "10/09/1999"
      },
      "2": {
        "ID": 26,
      	"nom": "Pillot",
      	"prenom": "Christophe",
      	"email": "PillChristo@hotmail.com",
      	"telephone": "0621371204",
      	"image": "defaut.png",
      	"naissance": "09/07/1998"
      },
      "3": {
        "ID": 12,
      	"nom": "Chaise",
      	"prenom": "Marie",
      	"email": "MarieChaise@hotmail.com",
      	"telephone": "0618541320",
      	"image": "defaut.png",
      	"naissance": "14/10/1997"
      }
    },
  }
}
</pre>
				</div>
				<h2 class="h2-doc">Les différents paramètres de réponse de l'API</h2>
				<div class="parametres">
					<h4 class="h4-doc">Paramètres de la réponse d'api du dessus:</h4>
					<ul class="ul1">
						<li>
							<code>B2</code>
							Correspond au groupe
						</li>
					</ul>

					<ul class="ul2">
						<li>
							<code>LPI-RIWS</code>
						Correspond à la filière
						</li>
						<li>
							<code>1</code>
							Correspond à la position de l'étudiant
						</li>
					</ul>
					<ul class="ul3">
						<li>
							<code>ID</code>
							Correspond à l'identifiant unique de l'étudiant
						</li>
						<li>
							<code>nom</code>
							Correspond au nom de l'étudiant
						</li>
						<li>
							<code>prenom</code>
							Correspond au prénom de l'étudiant
						</li>
						<li>
							<code>email</code>
							Correspond au mail de l'étudiant
						</li>
						<li>
							<code>telephone</code>
							Correspond au numéro de téléphone de l'étudiant
						</li>
						<li>
							<code>image</code>
							Correspond à l'image de l'étudiant <a href="#image">(comment l'utiliser)</a>
						</li>
						<li>
							<code>naissance</code>
							Correspond à la date de naissance de l'étudiant
						</li>
					</ul>
				</div>
				<h2 class="h2-doc">Par filière et par groupe</h2>
					<div class="filiere">
						<h4 class="h4-doc">Description:</h4>
						Permet de récupérer les informations des étudiants de la filière et du groupe souhaité.
						<h4 class="h4-doc">Appel d'API:</h4>
					<code class="bold">
							<?php echo $protocol ?>://<?php echo $url ?>/trombinoscope?groupe={nom du groupe}&amp;key={votre clé d'api}
						</code>
					<h4 class="h4-doc">Exemple d'appel d'API:</h4>
						<a href="exemple?filiere=L2-MIPI&amp;groupe=C1&amp;key=UikPqwDB8c1SHlFAn6FoMryc3610OMbZ" target="_blank"><?php echo $protocol ?>://<?php echo $url ?>/trombinoscope?filiere=L2-MIPI&amp;groupe=C1</a>
						<h4>Réponse d'API:</h4>
<pre>{
  "L2-MIPI": {
    "C1": {
      "1": {
        "ID": 4,
      	"nom": "Savoie",
      	"prenom": "Nadine",
      	"email": "NadineSavoie@hotmail.com",
      	"telephone": "0769438758",
      	"image": "defaut.png",
      	"naissance": "17/03/1998"
      },
      "2": {
        "ID": 21,
      	"nom": "Mercier",
      	"prenom": "Olivier",
      	"email": "OlivierMercier@hotmail.com",
      	"telephone": "0794911097",
      	"image": "defaut.png",
      	"naissance": "08/02/2000"
      }
    },
  }
}
</pre>
					</div>
				<h2 class="h2-doc" id="image">Utiliser les images</h2>
				<div class="image">
					<h4 class="h4-doc">Description:</h4>
					Lorsque l'on fait une requête de l'api, nous obtenons un paramètre "image" comme vu précédemment dans la description des paramètres, on peut voir par exemple "defaut.png", ce qui correspond à l'image par défaut quand un étudiant s'inscrit. Si on veut afficher image, il faut suivre la méthode suivante.
					<h4 class="h4-doc">Méthode:</h4>
					Après avoir décodé votre json <a href="https://www.php.net/manual/fr/function.json-decode.php" target="_blank">(tutoriel ici)</a>, vous devrez récupérer l'image, par exemple: <code>$jsonArray["groupe"]["filiere"][1]["image"]</code>
					<h4 class="h4-doc">Dans votre code dans votre balise img dans src:</h4>
					<code class="bold"><?php echo $protocol ?>://<?php echo $url ?>/img/profil/{image}</code>
				</div>
	</div>
		<div class="container-right">
			<h2>Demandez votre clé</h2>
			<form method="post">
				<table>
					<tr>
						<td>
							<input class="input-api" title="Votre email" type="email" placeholder="Email" name="mail" required="required" aria-required="true" value="<?php if (isset($erreur)) {echo($mail);} ?>" />
						</td>
						<td> <!-- Pour initialiser à 2 cellules, sinon problème dans la vérification html -->
							
						</td>
					</tr>
					<tr>
						<td>
							<input class="input-api" title="Votre mot de passe doit contenir au minimum 8 caractères" type="password" placeholder="Mot de passe" name="mdp" id="password" required="required" aria-required="true" minlength="8"/>
						</td>
						<td>
							<img id="eyes_mdp1" style="height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('password'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
						</td>
					</tr>
					<tr>
						<td>
							<input class="input-api" title="Votre mot de passe de confirmation doit contenir au minimum 8 caractères" type="password" placeholder="Mot de passe de confirmation" id="password2" name="mdp2" required="required" aria-required="true" minlength="8" />
						</td>
						<td>
							<img id="eyes_mdp2" style="height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('password2'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
						</td>
					</tr>
				</table>
				<input class="submit-form" type="submit" value="Obtenir" name="form-demand-key" />
			</form>
			<?php
			if (isset($erreur)) {
				echo "<font color='#dc3545' style=\"font-weight: bold; font-size: 16px;\">". $erreur . "</font>\n";
			}elseif (isset($inscriptionOK)) {
				echo "<font color='#28a745' style=\"font-weight: bold; font-size: 16px;\">". $inscriptionOK . "</font>\n";
			}
			?>
			<h2>Récupérez votre clé</h2>
			<form method="post">
				<table>
					<tr>
						<td>
							<input class="input-api" title="Votre email" type="email" placeholder="Email" name="mail" required="required" aria-required="true" value="<?php if (isset($erreurRecup)) {echo($mail);} ?>" />
						</td>
						<td> <!-- Pour initialiser à 2 cellules, sinon problème dans la vérification html -->
							
						</td>
					</tr>
					<tr>
						<td>
							<input class="input-api" title="Votre mot de passe doit contenir au minimum 8 caractères" type="password" placeholder="Mot de passe" name="mdp" id="password3" required="required" aria-required="true" minlength="8"/>
						</td>
						<td>
							<img id="eyes_mdp3" style="height: 50px; width: 50px; cursor: pointer;" onclick="showPassword('password3'); changeimg(this)" src="img/eyes.png" alt="Oeil"/>
						</td>
					</tr>
				</table>
				<input class="submit-form" type="submit" value="Récuperer" name="form-recup-key" />
			</form>
			<?php 
			if (isset($erreurRecup)) {
				echo "<font color='#dc3545' style=\"font-weight: bold; font-size: 16px;\">". $erreurRecup . "</font>\n";
			}elseif ($recupKey) {
				echo "<font color='#28a745' style=\"font-weight: bold; font-size: 16px;\">". $recupKey . "</font><br/>\n";
				echo "<font color='#28a745' style=\"font-weight: bold; font-size: 16px;\">". $recupUtil . "</font>\n";
			}
			?>
		</div>
	</div>
<script src="js/script.js"></script>
</body>
</html>