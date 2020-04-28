<h1 class="title">Etudiant</h1>
<nav>
    <ul>
<?php
    if (!isset($_SESSION["nom"])) {
        ?>
        <li class="menu"><a href="index">Accueil</a>
        </li>
        <li class="menu"><a href="api">Api</a>
        </li>
        <li class="menu"><a href="connexion">Connexion</a>
        </li>
        <?php
    }else{
    ?>
        <li class="menu"><a href="../profil">Mon profil</a>
        </li>
        <?php
        if ($_SESSION["id"] == 1) {
            ?>
            <li class="menu"><a href="../administration">Administration</a>
            </li>
            <?php
        }
        ?>
        <li class="menu"><a href="../deconnexion">Déconnexion</a>
        </li>
    <?php } ?>
    </ul>
</nav>