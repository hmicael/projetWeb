<?php ob_start(); ?>
Edition responsable
<?php
    if (isset($_SESSION['error-msg'])) {
        echo '<span class="error-msg">' . $_SESSION['error-msg'] . '</span>';
        unset($_SESSION['error-msg']);
    }
?>
<section id="tabs">
    <ul>
        <li><a href="#tabs-1">Utilisateurs</a></li>
        <li><a href="#tabs-2">Mati&egrave;res</a></li>
        <li><a href="#tabs-3">Enseignants</a></li>
        <li><a href="#tabs-4">Salles</a></li>
    </ul>
    <section id="tabs-1">
        <?php require(WEBROOT . 'views/utilisateurs.php') ?>
    </section>
    <section id="tabs-2">
        <?php require(WEBROOT . 'views/matieres.php') ?>
    </section>
    <section id="tabs-3">
        <?php require(WEBROOT . 'views/enseignants.php') ?>
    </section>
    <section id="tabs-4">
        <?php require(WEBROOT . 'views/salles.php') ?>
    </section>
</section>
<a href="index.php">Accueil</a>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>