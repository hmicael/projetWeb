<?php
/**
 * Function qui echappe les caractères spécials des données envoyé par les utilisateurs
 *
 * @param array $data
 * @return array $sanitizedData
 */
function sanitizeAndCheck(array $data, $dataKey) {
    $sanitizedData = [];
    foreach ($dataKey as $key) {
        if (! isset($data[$key])) {
            // si une donnée obligatoire est absent -> redirection
            $_SESSION['error-msg'] = 'Veuillez bien remplir le formulaire';
            header('Location: index.php?action=visualiser');
            exit();
        } else {  
            if (! is_array($data[$key])) {
                $sanitizedData[$key] = htmlspecialchars($data[$key]);
            } else {
                $sanitizedData[$key] = sanitizeAndCheck($data[$key], array_keys($data[$key]));
            }
        }
    }

    return $sanitizedData;
}

// sanitize les données
$get = sanitizeAndCheck($_GET, ['heure', 'jour', 'groupe', 'semaine']);
$filename = WEBROOT . '/data/edt/' . $get['semaine'] . '.json';

// ouverture du fichier
$edt = json_decode(file_get_contents($filename), true);

if ($_GET['action'] === 'edt-delete') {
    if (isset($edt[$get['heure']][$get['jour'] - 1][$get['groupe'] - 1])) {
        unset($edt[$get['heure']][$get['jour'] - 1][$get['groupe'] - 1]);
    }
} else {
    $postDataKey = [
        'form-edt-groupe',
        'form-edt-hdebut',
        'form-edt-type',
        'form-edt-matiere',
        'form-edt-enseignant',
        'form-edt-salle',
        'form-edt-hfin',
        'form-edt-date'
    ];    
    $post = sanitizeAndCheck($_POST, $postDataKey);

    // les identifiants des groupes sont les clé du tableau, du coup on fait array_key
    $groupes = array_keys($post['form-edt-groupe']);
    // on fait un tri du tableau pour faciliter la determination des fusions colonnes
    sort($groupes);
    if ($_GET['action'] === 'edt-add') {
        // sectionner la séquence de groupe si elle n'est pas continue pour faciliter l'affichage
        // il faut que l'indice du groupe est égale à la première valeur de "groupes" pour bien afficher
        // array1 === array2 compare s'ils ont les mêmes valeurs dans le même ordre
        $data = [
            "type" => $post['form-edt-type'],
            "matiere" => $post['form-edt-matiere'],
            "enseignant" => $post['form-edt-enseignant'],
            "salle" => $post['form-edt-salle'],
            "date" => $post['form-edt-date'],
            "hdebut" => $post['form-edt-hdebut'],
            "hfin" =>  $post['form-edt-hfin']
        ];
        if([0, 2, 3] === $groupes &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][0]) && // ! isset pour ne pas écraser un valeur déjà existant
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][2])) {
            $data["groupes"] = [0];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;

            $data["groupes"] = [2, 3];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][2] = $data;
            
        } else if([0, 1, 3] === $groupes &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][0]) &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][3])) {
            $data["groupes"] = [0, 1];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
            
            $data["groupes"] = [3];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
        } else if([0, 2] === $groupes &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][0]) &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][2])) {
            $data["groupes"] = [0];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
            
            $data["groupes"] = [2];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][2] = $data;
        } else if([1, 3] === $groupes &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][1]) &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][3])) {
            $data["groupes"] = [1];    
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][1] = $data;
            
            $data["groupes"] = [3];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
        } else if([0, 3] === $groupes &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][0]) &&
            ! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][3])) {
            $data["groupes"] = [0];  
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
            
            $data["groupes"] = [3];  
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
        } else if (! isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][$groupes[0]])) { // $groupes[0] car c'est l'ordre trié des groupes
            $data["groupes"] = $groupes; 
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][$groupes[0]] = $data;
        }
    } else if ($_GET['action'] === 'edt-edit') {
        // sectionner la séquence de groupe si elle n'est pas continue pour faciliter l'affichage
        // il faut que l'indice du groupe est égale à la première valeur de "groupes" pour bien afficher
        // array1 === array2 compare s'ils ont les mêmes valeurs dans le même ordre
        $data = [
            "type" => $post['form-edt-type'],
            "matiere" => $post['form-edt-matiere'],
            "enseignant" => $post['form-edt-enseignant'],
            "salle" => $post['form-edt-salle'],
            "date" => $post['form-edt-date'],
            "hdebut" => $post['form-edt-hdebut'],
            "hfin" =>  $post['form-edt-hfin']
        ];
        if([0, 2, 3] === $groupes &&
            isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][0])) {
            $data["groupes"] = [0];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;

            $data["groupes"] = [2, 3];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][2] = $data;
            
        } else if([0, 1, 3] === $groupes &&
            isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][0])) {
            $data["groupes"] = [0, 1];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
            
            $data["groupes"] = [3];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
        } else if([0, 2] === $groupes &&
            isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][0])) {
            $data["groupes"] = [0];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
            
            $data["groupes"] = [2];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][2] = $data;
        } else if([1, 3] === $groupes &&
            isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][1])) {
            $data["groupes"] = [1];    
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][1] = $data;
            
            $data["groupes"] = [3];
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
        } else if([0, 3] === $groupes &&
            isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][0])) {
            $data["groupes"] = [0];  
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
            
            $data["groupes"] = [3];  
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
        } else if (isset($edt[$post['form-edt-hdebut']][$get['jour'] - 1][$groupes[0]])) { // $groupes[0] car c'est l'ordre trié des groupes
            $data["groupes"] = $groupes; 
            $edt[$post['form-edt-hdebut']][$get['jour'] - 1][$groupes[0]] = $data;
        }
    }
}

// enregistrement
file_put_contents($filename, json_encode($edt, JSON_PRETTY_PRINT));

// redirection
header('Location: index.php?action=visualiser');
exit();
