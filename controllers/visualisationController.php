<?php
$title = 'Visualisation';
$lundiDeLaSemaine =  date('d-m-Y', strtotime("last Monday +0 days"));
// $defaultDate->modify('last monday');
$jsonEnseignant = file_get_contents(WEBROOT . '/data/enseignants.json');
$enseignants = json_decode($jsonEnseignant, true);
$jsonMatiere = file_get_contents(WEBROOT . '/data/matieres.json');
$matieres = json_decode($jsonMatiere, true);
$jsonSalle = file_get_contents(WEBROOT . '/data/salles.json');
$salles = json_decode($jsonSalle, true);
// $jsonString = file_get_contents(WEBROOT. '/data/edt/edt-06-03-2023.json');
// $edt = json_decode($jsonString, true);
$edt = [];
// Définir les heures de début et de fin
$heureDebut = strtotime('08:00');
$heureFin = strtotime('19:00');
for ($heure = $heureDebut; $heure <= $heureFin; $heure += 900) {
    $horaires = [];
    for ($i=0; $i < 5; $i++) { 
        // Boucler à travers les heures avec un pas de 15 minutes
        for ($j=0; $j < 4; $j++) {
            $horaires[$i][$j] = [
                "type" => "TD",
                "matiere" => "Web",
                "enseignant" => "Frederic Vernier",
                "salle" => "D203",
                "date" => date('H:i', $heure),
                "hdebut" => date('H:i', $heure + 900),
                "hfin" => "10:00"
            ];
        }
    }
    $edt[date('H:i', $heure)] = $horaires;
}

require(WEBROOT . '/views/visualisation.php');