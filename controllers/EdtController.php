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
$filename = WEBROOT . '/data/edt/' . $get["semaine"] . '.json';

// ouverture du fichier
$edt = json_decode(file_get_contents($filename), true);

if ($_GET['action'] == 'edt-delete') {
    if (isset($edt[$get["heure"]][$get["jour"] - 1][$get["groupe"] - 1])) {
        unset($edt[$get["heure"]][$get["jour"] - 1][$get["groupe"] - 1]);
    }
} else {
    // les identifiants des groupes sont les clé du tableau, du coup on fait array_key
    $groupes = array_keys($post["form-edt-groupe"]);
    // on fait un tri du tableau pour faciliter la determination des fusions colonnes
    sort($groupes);
    if ($_GET['action'] == 'edt-add') {
        // sectionner la séquence de groupe si elle n'est pas continue pour faciliter l'affichage
        // il faut que l'indice du groupe est égale à la première valeur de "groupes" pour bien afficher
        if(! array_diff([0, 2, 3], $groupes) &&
            ! isset($edt[$post["form-edt-hdebut"]][$get["jour"] - 1][0]) && // ! isset pour ne pas écraser un valeur déjà existant
            ! isset($edt[$post["form-edt-hdebut"]][$get["jour"] - 1][2])) {
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
        } else if(! array_diff([0, 1, 3], $groupes) &&
        ! isset($edt[$post["form-edt-hdebut"]][$get["jour"] - 1][0]) && // ! isset pour ne pas écraser un valeur déjà existant
        ! isset($edt[$post["form-edt-hdebut"]][$get["jour"] - 1][3])) {
            $edt[$post["form-edt-hdebut"]][$get["jour"] - 1][0] = [
                "type" => $post["form-edt-type"],
                "matiere" => $post["form-edt-matiere"],
                "enseignant" => $post["form-edt-enseignant"],
                "salle" => $post["form-edt-salle"],
                "date" => $post["form-edt-date"],
                "hdebut" => $post["form-edt-hdebut"],
                "hfin" =>  $post["form-edt-hfin"],
                "groupes" => [0, 1]
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
        } else if (! isset($edt[$post["form-edt-hdebut"]][$get["jour"] - 1][$get["groupe"] - 1])) {
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
    } elseif ($_GET['action'] == 'edt-edit') {
        # code...
    }
}

// enregistrement
file_put_contents($filename, json_encode($edt, JSON_PRETTY_PRINT));

// redirection
header('Location: index.php?action=visualiser');
exit();
