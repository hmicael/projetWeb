<?php
/**
 * Fonction qui echappe les caractères spécials des données envoyé par les utilisateurs et retourne un tableau
 * sinon on redirige vers la page de visualisation
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

/**
 * Fonction qui supprime un slot de l'emploi du temps
 *
 * @param array $edt
 * @param [type] $hdeb
 * @param [type] $jour
 * @param [type] $groupe
 * @return array
 */
function deleteEdt(array $edt, $hdeb, $jour, $groupe)
{
    // si le slot existe
    if (isset($edt[$hdeb][$jour][$groupe])) {
        // on supprime le slot et tous les slots qui sont dans le même groupe
        $slot = $edt[$hdeb][$jour][$groupe];
        $hfin = strtotime($slot['hfin']);
        // pour chaque groupe du slot
        foreach ($slot['groupes'] as $g) {
            // pour chaque heure du slot
            for ($h = strtotime($slot['hdebut']); $h < $hfin; $h += 900) {
                unset($edt[date('H:i', $h)][$jour][$g]);
            }
        }
    }

    return $edt;
}

// sanitize les données
$get = sanitizeAndCheck($_GET, ['heure', 'jour', 'groupe', 'semaine']);
$filename = WEBROOT . '/data/edt/' . $get['semaine'] . '.json';

// ouverture du fichier
$edt = json_decode(file_get_contents($filename), true);

if ($_GET['action'] === 'edt-delete') { // si on veut supprimer un slot
    $edt = deleteEdt($edt, $get['heure'], ($get['jour']-1), ($get['groupe']-1));
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
    $hdeb = strtotime($post['form-edt-hdebut']);
    $hfin = strtotime($post['form-edt-hfin']);
    $jour = ($get['jour'] - 1);

    // pour une modification, on va d'abord supprimé tous les slots à modifier
    // puis après vérifier si les slots où sera insérer la modification est vide ou pas
    if ($_GET['action'] === 'edt-edit' &&
        isset($edt[$post['form-edt-hdebut']][$jour][$get['groupe']-1])) { // ici on vérifier que le slot existe
        $edt = deleteEdt($edt, $get['heure'], ($get['jour']-1), ($get['groupe']-1));
    }

    // les identifiants des groupes sont les clé du tableau, du coup on fait array_key
    $groupes = array_keys($post['form-edt-groupe']);
    // on fait un tri du tableau pour faciliter la determination des fusions colonnes
    sort($groupes);

    // les nouvelles données
    $data = [
        "type" => $post['form-edt-type'],
        "matiere" => $post['form-edt-matiere'],
        "enseignant" => $post['form-edt-enseignant'],
        "salle" => $post['form-edt-salle'],
        "date" => $post['form-edt-date'],
        "hdebut" => $post['form-edt-hdebut'],
        "hfin" =>  $post['form-edt-hfin'],
        "groupes" => $groupes
    ];

    // vérifier si un slot est vide, sinon renvoie un erreur
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    foreach ($groupes as $g) { 
        for ($h = $hdeb; $h < $hfin; $h += 900) {
            if (isset($edt[date('H:i', $h)][$jour][$g])) {
                $_SESSION['error-msg'] = 'Vous ne pouvez plus mettre un cours sur ' .
                    $jours[$jour] . ' à ' . date('H:i', $h) . ' Groupe ' . (intval($g)+1);
                header('Location: index.php?action=visualiser');
                exit();
            };
        }
    }
    
    // pré-remplir avec fusion pour indiquer les slots issue d'une fusion de ligne ou de colonne
    foreach ($groupes as $g) { 
        for ($h = $hdeb; $h < $hfin; $h += 900) {
            $edt[date('H:i', $h)][$jour][$g]['fusion'] = true;
        }
    }

    // sectionner la séquence de groupe si elle n'est pas continue pour faciliter l'affichage
    // il faut que l'indice du groupe est égale à la première valeur de 'groupes' pour bien afficher
    // array1 === array2: vérifie si les valeurs sont les mêmes et dans le même ordre
    // on remet $data avec le bon groupe et sans ['fusion' => true] car c'est le premier slot
    if([0, 2, 3] === $groupes) { // split 0,2,3 en 0 et 2,3
        // 0
        $data['groupes'] = [0];
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
        // 2,3
        $data['groupes'] = [2, 3];
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][2] = $data;            
    } else if([0, 1, 3] === $groupes) { // split 0,1,3 en 0,1 et 3
        // 0,1
        $data['groupes'] = [0, 1];
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
        // 3
        $data['groupes'] = [3];
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
    } else if([0, 2] === $groupes) { // split 0 et 2
        $data['groupes'] = [0];
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
        $data['groupes'] = [2];
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][2] = $data;
    } else if([1, 3] === $groupes) { // split 1 et 3
        $data['groupes'] = [1];    
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][1] = $data;
        $data['groupes'] = [3];
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
    } else if([0, 3] === $groupes) { // split 0 et 3
        $data['groupes'] = [0];  
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][0] = $data;
        $data['groupes'] = [3];  
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][3] = $data;
    } else { // par defaut
        $data['groupes'] = $groupes;
        // $groupes[0] car c'est l'ordre trié des groupes et on veut le premier
        $edt[$post['form-edt-hdebut']][$get['jour'] - 1][$groupes[0]] = $data;
    }
}

// enregistrement
file_put_contents($filename, json_encode($edt, JSON_PRETTY_PRINT));

// redirection
header('Location: index.php?action=visualiser');
exit();
