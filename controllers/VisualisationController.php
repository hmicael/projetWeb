<?php
$title = 'Visualisation';
$lundiDeLaSemaine =  null;

if (isset($_GET['semaine']) &&
    preg_match('([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))', $_GET['semaine'])) {
    // s'il y a une demande de semaine spécifique et que c'est une date valide
    $_GET['semaine'] = htmlspecialchars($_GET['semaine']);
    if (date('D', strtotime($_GET['semaine'])) != 'Mon') {
        // si le jour actuel est n'est pas un lundi
        $lundiDeLaSemaine = date('Y-m-d', strtotime($_GET['semaine']) . ' last Monday'); // prendre le dernier lundi
    } else {
        $lundiDeLaSemaine = date('Y-m-d', strtotime($_GET['semaine']));   
    }
} else {
    // vérifier si le jour actuel est le lundi
    if(date('D') != 'Mon') {
        // si le jour actuel est n'est pas un lundi
        $lundiDeLaSemaine = date('Y-m-d', strtotime('last Monday')); // prendre le dernier lundi
    } else {
        $lundiDeLaSemaine = date('Y-m-d');   
    }
}

$enseignants = json_decode(file_get_contents(WEBROOT . '/data/enseignants.json'), true);
$matieres = json_decode(file_get_contents(WEBROOT . '/data/matieres.json'), true);
$salles = json_decode(file_get_contents(WEBROOT . '/data/salles.json'), true);
// le tableau qui recevra le contenu du fichier
$edt = [];
// le fichier à ouvrir correspond à un fichier date-du-lundi-de-la-semaine.json
$filename = WEBROOT . "/data/edt/$lundiDeLaSemaine.json";
if (!file_exists($filename)) {
    // si le fichier n'existe pas, on va le créer
    $handle = fopen($filename, 'x');
    fclose($handle);
} else {
    // sinon, on va lire le fichier et stocker son contenu dans la varialbe $edt
    $jsonString = file_get_contents($filename);
    $edt = json_decode($jsonString, true);
}

// Définir les heures de début et de fin
$heureDebut = strtotime('08:00');
$heureFin = strtotime('18:45');

require(WEBROOT . '/views/visualisation.php');
