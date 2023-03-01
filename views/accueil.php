<?php ob_start(); ?>
Page d'accueil
<marquee>Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . ' - ' . $_SESSION['role'] ?></marquee>
<a href="index.php?action=deconnect">Deconnection</a>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>