<?php
/**
 * Function qui echappe les caractères spécials des données envoyé par les utilisateurs
 *
 * @param array $data
 * @return
 */
function sanitize(array $data, $dataKey) {
    $sanitizedData = [];
    foreach ($dataKey as $key) {
        if (! isset($data[$key])) {
            // si une donnée obligatoire est absent -> redirection
            echo json_encode(
                ['code' => 500, 'status' => 'Bad Request', 'message' => "$key obligatoire"],
                JSON_PRETTY_PRINT
            );
            exit();
        } else {  
            if (! is_array($data[$key])) {
                $sanitizedData[$key] = htmlspecialchars($data[$key]);
            } else {
                $sanitizedData[$key] = sanitize($data[$key], array_keys($data[$key]));
            }
        }
    }
    
    return $sanitizedData;
}

if ($_GET['search'] === 'edt') {
    $data = sanitize($_POST, ['heure', 'jour', 'groupe', 'semaine']);
    $filename = WEBROOT . '/data/edt/' . $data['semaine'] . '.json';
    if (! file_exists($filename)) {
        // si le fichier n'existe pas, on renvoi un erreur
        echo json_encode(
            ['code' => 404, 'status' => 'Not Found', 'message' => 'L\'emploi du temps n\'existe pas'],
            JSON_PRETTY_PRINT
        );
        exit();
    }

    // ouverture du fichier
    $edt = json_decode(file_get_contents($filename), true);
    if (isset($edt[$data['heure']][$data['jour']][$data['groupe']])) {
        echo json_encode(
            [
                'code' => 200,
                'status' => 'Ok',
                'data' => $edt[$data['heure']][$data['jour']][$data['groupe']]
            ],
            JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode(
            ['code' => 404, 'status' => 'Not Found', 'message' => 'L\'emploi du temps n\'existe pas'],
            JSON_PRETTY_PRINT
        );
    }
    exit();
}

if ($_GET['search'] === 'enseignant') {
    // Rechercher le prof referant d'une matiere et tout les autres prof non référant (jugé etre tous aptes à dispenser le cours)
    $nomMatiere = sanitize($_POST, ['matiere'])['matiere'];
    $matieres = json_decode(file_get_contents(WEBROOT . '/data/matieres.json'), true);
    $profs = json_decode(file_get_contents(WEBROOT . '/data/enseignants.json'), true);
    $data = [];
    // recherche du prof référant de la matière;
    foreach ($matieres as $m) {
        if ($m['nom'] == $nomMatiere) {
            $data[] = $m['referant'];
            break;
        }
    }

    // recherche prof non referant
    foreach ($profs as $p) {
        if ($p['referant'] == 'Non') {
            $data[] = $p['nom'] = $p['nom'];
        }
    }
    echo json_encode(
        [
            'code' => 200,
            'status' => 'Ok',
            'data' => $data
        ],
        JSON_PRETTY_PRINT
    );
    exit();
}