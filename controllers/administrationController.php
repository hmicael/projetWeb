<?php
$title = 'Administration';
// ouverture des fichiers
$jsonUtilisateur = file_get_contents(WEBROOT. '/data/utilisateurs.json');
$utilisateurs = json_decode($jsonUtilisateur, true);
$jsonEnseignant = file_get_contents(WEBROOT. '/data/enseignants.json');
$enseignants = json_decode($jsonEnseignant, true);
$jsonMatiere = file_get_contents(WEBROOT. '/data/matieres.json');
$matieres = json_decode($jsonMatiere, true);
$jsonSalle = file_get_contents(WEBROOT. '/data/salles.json');
$salles = json_decode($jsonSalle, true);

require(WEBROOT . '/views/administration.php');