<?php ob_start(); ?>
Page d'accueil
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>