<h1 class="title">Equipe pédagogique</h1>
<nav>
    <ul>
<?php
    if (!isset($_SESSION["nom"])) {
        ?>
        <li class="menu"><a href="index">Accueil</a>
        </li>
        <li class="menu"><a href="connexion">Connexion</a>
        </li>
        <?php
    }else{
    ?>
        <li class="menu"><a href="../deconnexion">Déconnexion</a>
        </li>
        <?php
        }
        ?>
    </ul>
</nav>
