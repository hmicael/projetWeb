<?php ob_start(); ?>
Page d'accueil
<marquee>Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . ' - ' . ucfirst($_SESSION['role']) ?></marquee>
<div>
    <a href="">Visualiser</a>
    <?php if ($_SESSION['role'] == 'responsable') {
        echo '<a href="">Edition</a>';
    }
    ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>