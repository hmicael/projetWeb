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
        $sanitizedData[$key] = htmlspecialchars($value);
    }

    return $sanitizedData;
}

$title = 'Administration';
// Ouverture des fichiers
$jsonUtilisateur = file_get_contents(WEBROOT . '/data/utilisateurs.json');
$utilisateurs = json_decode($jsonUtilisateur, true);
$jsonEnseignant = file_get_contents(WEBROOT . '/data/enseignants.json');
$enseignants = json_decode($jsonEnseignant, true);
$jsonMatiere = file_get_contents(WEBROOT . '/data/matieres.json');
$matieres = json_decode($jsonMatiere, true);
$jsonSalle = file_get_contents(WEBROOT . '/data/salles.json');
$salles = json_decode($jsonSalle, true);

// Suppression
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

// Ajout 
if(isset($_GET['create'])) {
    $entity = htmlspecialchars($_GET['create']);
    switch ($entity) {
        case 'utilisateurs':
            // Récupération des données du formulaire
            $data = sanitize($_POST);
            if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                // Ajout du nouvel objet dans le tableau de données
                $utilisateurs[] = array(
                    'nom' => strtoupper($data['nom']),
                    'prenom' => ucfirst($data['prenom']),
                    'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                    'email' => $data['email'],
                    'role' => ucfirst($data['role'])
                );
                // Conversion du tableau PHP en contenu JSON
                $jsonUtilisateur = json_encode($utilisateurs, JSON_PRETTY_PRINT);
                // Écriture du contenu JSON dans le fichier
                file_put_contents(WEBROOT .  '/data/utilisateurs.json', $jsonUtilisateur);
            } else {
                echo json_encode(['status' => 'Adresse e-mail est invalide']);
                die();
            }
            break;
        case 'matieres':
            $matieres[] = array(
                'nom' => ucfirst(htmlspecialchars($_POST['nom'])),
                'referant' => ucfirst(htmlspecialchars($_POST['referant'])),
            );
            $jsonMatieres = json_encode($matieres, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/matieres.json', $jsonMatieres);
            break;
        case 'enseignants':
            $data = sanitize($_POST);
            $enseignants[] = array(
                'nom' => ucfirst($data['nom']),
                'referant' => ucfirst($data['referant'])
            );
            $jsonEnseignants = json_encode($enseignants, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/enseignants.json', $jsonEnseignants);
            break;
        case 'salles':
            $salles[] = array(
                'nom' => strtoupper(htmlspecialchars($_POST['nom']))
            );
            $jsonSalles = json_encode($salles, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/salles.json', $jsonSalles);
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
            break;
    }
    echo json_encode(['status' => 'ok']);
    die();
}

// Modification
if(isset($_GET['edit'])) {
    if (! $_GET['id']) {
        throw new Exception('Vous devez spécifier un identifiant');
    }
    $id = intval($_GET['id']) - 1;
    $entity = htmlspecialchars($_GET['edit']);
    switch ($entity) {
        case 'utilisateurs':
            $data = sanitize($_POST);
            $utilisateurs[$id] = array(
                'nom' => strtoupper($data['nom']),
                'prenom' => ucfirst($data['prenom']),
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'email' => $data['email'],
                'role' => ucfirst($data['role'])
            );
            // Convertir le tableau PHP en JSON
            file_put_contents(WEBROOT . '/data/utilisateurs.json', json_encode($utilisateurs, JSON_PRETTY_PRINT));
            break;
        case 'matieres':
            $matieres[$id] = array(
                'nom' => ucfirst(htmlspecialchars($_POST['nom'])),
                'referant' => ucfirst(htmlspecialchars($_POST['referant'])),
            );
            file_put_contents(WEBROOT . '/data/matieres.json', json_encode($matieres, JSON_PRETTY_PRINT));
            break;
        case 'enseignants':
            $data = sanitize($_POST);
            $enseignants[$id] = array(
                'nom' => ucfirst($data['nom']),
                'referant' => ucfirst($data['referant'])
            );
            file_put_contents(WEBROOT . '/data/enseignants.json', json_encode($enseignants, JSON_PRETTY_PRINT));
            break;
        case 'salles':
            $salles[$id] = array(
                'nom' => strtoupper(htmlspecialchars($_POST['nom']))
            );
            $jsonSalles = json_encode($salles, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT . '/data/salles.json', json_encode($salles, JSON_PRETTY_PRINT));
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
            break;
    }
    echo json_encode(['status' => 'ok', 'id' => $id]);
    die();
}

require(WEBROOT . '/views/administration.php');
