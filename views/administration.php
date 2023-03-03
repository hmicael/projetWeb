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
        Utilisateurs
    </div>
    <div id="tabs-2">
        Mati&egrave;res
    </div>
    <div id="tabs-3">
        Enseignants
    </div>
    <div id="tabs-4">
        Salles
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>