<?php
$title = 'Visualisation';

$lundiDeLaSemaine =  null;
// vérifier si le jour actuel est le lundi
if(date('D') != 'Mon') {    
 // prendre le dernier lundi
  $lundiDeLaSemaine = date('Y-m-d', strtotime('last Monday +2 days'));    

}else{
    $lundiDeLaSemaine = date('Y-m-d');   
}
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
// for ($heure = $heureDebut; $heure <= $heureFin; $heure += 900) {
//     $horaires = [];
//     for ($jour=0; $jour < 5; $jour++) { 
//         // Boucler à travers les heures avec un pas de 15 minutes
//         for ($groupe=0; $groupe < 4; $groupe++) {
//             $horaires[$jour][$groupe] = [
//                 "type" => "TD",
//                 "matiere" => "Web",
//                 "enseignant" => "Frederic Vernier",
//                 "salle" => "D203",
//                 "date" => date('d-m-Y'),
//                 "hdebut" => date('H:i', $heure),
//                 "hfin" =>  date('H:i', $heure + 900),
//                 "groupes" => [1,2]
//             ];
//         }
//     }
//     $edt[date('H:i', $heure)] = $horaires;
// }
$edt["09:00"][0][0] = [
    "type" => "TD",
    "matiere" => "BD",
    "enseignant" => "Frederic Vernier",
    "salle" => "D203",
    "date" => date('d-m-Y'),
    "hdebut" => date("09:00"),
    "hfin" =>  date("09:45"),
    "groupes" => [0,1,3]
];
$edt["09:00"][2][2] = [
    "type" => "TD",
    "matiere" => "BD",
    "enseignant" => "Frederic Vernier",
    "salle" => "D203",
    "date" => date('d-m-Y'),
    "hdebut" => date("09:00"),
    "hfin" =>  date("09:45"),
    "groupes" => [2,3]
];
$edt["08:00"][0][0] = [
    "type" => "TD",
    "matiere" => "BD",
    "enseignant" => "Frederic Vernier",
    "salle" => "D203",
    "date" => date('d-m-Y'),
    "hdebut" => date("08:00"),
    "hfin" =>  date("10:00"),
    "groupes" => [0,1,3]
];
// unset($edt["09:15"][0][0]);
// echo '<pre>';
// var_dump($edt);

require(WEBROOT . '/views/visualisation.php');