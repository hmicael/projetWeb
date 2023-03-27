<?php
/**
 * Function qui echappe les caractères spécials des données envoyé par les utilisateurs
 *
 * @param array $data
 * @return array $sanitizedData
 */
function sanitize(array $data) {
    $sanitizedData = [];
    foreach ($data as $key => $value) {
        if (! is_array($value)) {
            $sanitizedData[$key] = htmlspecialchars($value);
        } else {
            $sanitizedData[$key] = sanitize($value);
        }
    }

    return $sanitizedData;
}

// sanitize les données
$get = sanitize($_GET);
$post = sanitize($_POST);
$filename = '/data/edt/' . $get["semaine"] . '.json';

// ouverture du fichier
$jsonString = file_get_contents($filename);
$edt = json_decode($jsonString, true);

if ($_GET['action'] == 'edt-delete') {
    echo 'delete';
} else {
    // les identifiants des groupes sont les clé du tableau, du coup on fait array_key
    $groupes = array_keys($groupes);
    // on fait un tri du tableau pour faciliter la determination des fusions colonnes
    sort($groupes);
    if ($_GET['action'] == 'edt-add') {
        // sectionner la séquence de groupe s'elle n'est pas continue pour faciliter l'affichage
        if(! array_diff([0, 2, 3], $groupes)) {
            $edt[$post["form-edt-hdebut"]][$get["jour"] - 1][0] = [
                "type" => $post["form-edt-type"],
                "matiere" => $post["form-edt-matiere"],
                "enseignant" => $post["form-edt-enseignant"],
                "salle" => $post["form-edt-salle"],
                "date" => $post["form-edt-date"],
                "hdebut" => $post["form-edt-hdebut"],
                "hfin" =>  $post["form-edt-hfin"],
                "groupes" => [0]
            ];
            $edt[$post["form-edt-hdebut"]][$get["jour"] - 1][2] = [
                "type" => $post["form-edt-type"],
                "matiere" => $post["form-edt-matiere"],
                "enseignant" => $post["form-edt-enseignant"],
                "salle" => $post["form-edt-salle"],
                "date" => $post["form-edt-date"],
                "hdebut" => $post["form-edt-hdebut"],
                "hfin" =>  $post["form-edt-hfin"],
                "groupes" => [2, 3]
            ];
        } else if(! array_diff([0, 1, 3], $groupes)) {
            $edt[$post["form-edt-hdebut"]][$get["jour"] - 1][0] = [
                "type" => $post["form-edt-type"],
                "matiere" => $post["form-edt-matiere"],
                "enseignant" => $post["form-edt-enseignant"],
                "salle" => $post["form-edt-salle"],
                "date" => $post["form-edt-date"],
                "hdebut" => $post["form-edt-hdebut"],
                "hfin" =>  $post["form-edt-hfin"],
                "groupes" => [0,1]
            ];
            $edt[$post["form-edt-hdebut"]][$get["jour"] - 1][3] = [
                "type" => $post["form-edt-type"],
                "matiere" => $post["form-edt-matiere"],
                "enseignant" => $post["form-edt-enseignant"],
                "salle" => $post["form-edt-salle"],
                "date" => $post["form-edt-date"],
                "hdebut" => $post["form-edt-hdebut"],
                "hfin" =>  $post["form-edt-hfin"],
                "groupes" => [3]
            ];
        } else {
            $edt[$post["form-edt-hdebut"]][$get["jour"] - 1][$get["groupe"] - 1] = [
                "type" => $post["form-edt-type"],
                "matiere" => $post["form-edt-matiere"],
                "enseignant" => $post["form-edt-enseignant"],
                "salle" => $post["form-edt-salle"],
                "date" => $post["form-edt-date"],
                "hdebut" => $post["form-edt-hdebut"],
                "hfin" =>  $post["form-edt-hfin"],
                "groupes" => $groupes
            ];
        }
        echo '<pre>';
        var_dump($_POST, $_GET, $edt);
    } elseif ($_GET['action'] == 'edt-edit') {
        # code...
    }
}

// enregistrement
file_put_contents($filename, json_encode($edt, JSON_PRETTY_PRINT));