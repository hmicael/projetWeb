<?php
$title = 'Visualisation';
// $jsonString = file_get_contents(WEBROOT. '/data/edt/edt-06-03-2023.json');
// $edt = json_decode($jsonString, true);
$edt = [];
// Définir les heures de début et de fin
$heureDebut = strtotime('08:00');
$heureFin = strtotime('19:00');
for ($i=0; $i < 5; $i++) { 
    $groupes = [];
    for ($j=0; $j < 4; $j++) {
        // Boucler à travers les heures avec un pas de 15 minutes
        for ($heure = $heureDebut; $heure <= $heureFin; $heure += 900) {
            $groupes[$j][date('H:i', $heure)] = [
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
    $edt[$i] = $groupes;
}

var_dump($edt[0][0]["08:00"]);

if ($_SESSION['role'] == 'etudiant') {
    require(WEBROOT . '/views/visualisation.php');
} else {
    require(WEBROOT . '/views/editionEdt.php');
}