<?php include("includes/config.php");



?>
<!DOCTYPE html>
<html>
<head>
	<title>Trombinoscope: API</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="styles/styles.css"/>
	<link rel="icon" type="image/png" href="img/faviconcoursucp.png" />
</head>
<style type="text/css">

	.container{
	    margin: 3%;
	    margin-left: 10%;
	    width: 45%;
	    background: #ced6e0;
	    border: solid 1px #1e272e;
	    border-radius: 10px;
	}

	h2, h3, h4{
		color: #1e272e;
	}
	.h2-doc{
		margin-left: 6%;
	}

	.filiere{
		margin-left: 8%;
	}
	
	.h4-doc{
		margin-bottom: 1%;
	}

	.lead {
	    font-size: 21px;
	    font-weight: 500;
	    margin-left: 8%;

	}

	.information{
	    background-color: #dfe4ea;
	    margin-left: 6%;
	    margin-right: 4%;
    	padding: 10px 25px 10px 10px;
    	border-left: 5px solid #e96e50;
    	color: #505050;
	}



</style>
<body>
	<header>
	  <?php include "includes/menunav.php" ?>
	</header>
	<!-- Inspiré de https://openweathermap.org/current -->
	<div class="container">
		<h2 class="h2-doc">Documentation</h2>
			<p class="lead">
				Accédez aux données des filières et des groupes !<br/>
				Les données sont disponibles au format JSON.
			</p>
			<div class="information">
				<h4>	
					N'oubliez pas que tous les exemples d'appels API répertoriés sur cette page ne sont que des exemples et n'ont aucune connexion avec le véritable service API !
				</h4>
			</div>
			<h2 class="h2-doc">Par filière</h2>
			<div class="filiere">
				<h4 class="h4-doc">Description:</h4>
				Permet de récupérer les informations des étudiants de la filière souhaitée.
				<h4 class="h4-doc">Exemples d'appels d'API:</h4>
				<a href="exemple">cc</a>
			</div>
			
	</div>
	
</body>
</html>