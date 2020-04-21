<h1 class="title">Trombinoscope</h1>
<nav>
    <ul>
<?php
    if (!isset($_SESSION["nom"])) {
        ?>
        <li class="menu"><a href="index.php">Accueil</a>
        </li>
        <li class="menu"><a href="connexion.php">Connexion</a>
        </li>
        <?php
    }else{
    ?>
        <li class="menu"><a href="profil.php">Mon profil</a>
        </li>
        <li class="menu"><a href="parametres.php">Paramètres</a>
        </li>
        <li class="menu"><a href="deconnexion.php">Déconnexion</a>
        </li>
    <?php } ?>
    </ul>
</nav>
