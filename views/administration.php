<?php ob_start(); ?>
Edition responsable
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Utilisateurs</a></li>
        <li><a href="#tabs-2">Mati&egrave;res</a></li>
        <li><a href="#tabs-3">Enseignants</a></li>
        <li><a href="#tabs-4">Salles</a></li>
    </ul>
    <div id="tabs-1">
        <?php require(WEBROOT . 'views/utilisateurs.php') ?>
    </div>
    <div id="tabs-2">
        <?php require(WEBROOT . 'views/matieres.php') ?>
    </div>
    <div id="tabs-3">
        <?php require(WEBROOT . 'views/enseignants.php') ?>
    </div>
    <div id="tabs-4">
        <?php require(WEBROOT . 'views/salles.php') ?>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>