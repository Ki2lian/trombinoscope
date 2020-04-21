<h1 class="title">Trombinoscope</h1>
<nav>
    <ul>
        <li class="menu"><a href="index.php">Accueil</a>
        </li>
<?php
    if (!isset($_SESSION["nom"])) {
        ?>
        <li class="menu"><a href="#">Connexion</a>
        </li>
        <?php
    }else{
    ?>
        <li class="menu"><a href="#">Déconnexion</a>
        </li>
    <?php } ?>
    </ul>
</nav>
