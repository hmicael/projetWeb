<?php
$title = 'Administration';
// ouverture des fichiers
$jsonUtilisateur = file_get_contents(WEBROOT . '/data/utilisateurs.json');
$utilisateurs = json_decode($jsonUtilisateur, true);
$jsonEnseignant = file_get_contents(WEBROOT . '/data/enseignants.json');
$enseignants = json_decode($jsonEnseignant, true);
$jsonMatiere = file_get_contents(WEBROOT . '/data/matieres.json');
$matieres = json_decode($jsonMatiere, true);
$jsonSalle = file_get_contents(WEBROOT . '/data/salles.json');
$salles = json_decode($jsonSalle, true);

if(isset($_GET['delete'])) {
    if (! $_GET['id']) {
        throw new Exception('Vous devez spécifier un identifiant');
    }
    $id = intval($_GET['id']) - 1;
    $entity = htmlspecialchars($_GET['delete']);
    switch ($entity) {
        case 'utilisateurs':
            unset($utilisateurs[$id]);
            file_put_contents(WEBROOT . '/data/utilisateurs.json', json_encode($utilisateurs, JSON_PRETTY_PRINT));
            break;
        case 'matieres':
            unset($matieres[$id]);
            file_put_contents(WEBROOT . '/data/matieres.json', json_encode($matieres, JSON_PRETTY_PRINT));
            break;
        case 'enseignants':
            unset($enseignants[$id]);
            file_put_contents(WEBROOT . '/data/enseignants.json', json_encode($enseignants, JSON_PRETTY_PRINT));
            break;
        case 'salles':
            unset($salles[$id]);
            file_put_contents(WEBROOT . '/data/salles.json', json_encode($salles, JSON_PRETTY_PRINT));
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
            break;
    }
    header('Location: index.php?action=admin');
    exit();
}

require(WEBROOT . '/views/administration.php');