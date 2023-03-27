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

if ($_GET['action'] == 'edt-delete') {
    echo 'delete';
} else {
    // les identifiants des groupes sont les clé du tableau, du coup on fait array_key
    $groupes = array_keys($groupes);
    // on fait un tri du tableau pour faciliter la determination des fusions colonnes
    sort($groupes);
    if ($_GET['action'] == 'edt-add') {
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
        echo '<pre>';
        var_dump($_POST, $_GET, $edt);
    } elseif ($_GET['action'] == 'edt-edit') {
        # code...
    }
}