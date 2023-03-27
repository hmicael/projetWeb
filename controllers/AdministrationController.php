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
$tabs = 1;
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
            $tabs = 1;
            break;
        case 'matieres':
            unset($matieres[$id]);
            file_put_contents(WEBROOT . '/data/matieres.json', json_encode($matieres, JSON_PRETTY_PRINT));
            $tabs = 2;
            break;
        case 'enseignants':
            unset($enseignants[$id]);
            file_put_contents(WEBROOT . '/data/enseignants.json', json_encode($enseignants, JSON_PRETTY_PRINT));
            $tabs = 3;
            break;
        case 'salles':
            unset($salles[$id]);
            file_put_contents(WEBROOT . '/data/salles.json', json_encode($salles, JSON_PRETTY_PRINT));
            $tabs = 4;
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
            break;
    }
    header('Location: index.php?action=admin#tabs-' . $tabs);
    exit();
}

// Ajout 
if(isset($_GET['create'])) {
    $entity = htmlspecialchars($_GET['create']);
    switch ($entity) {
        case 'utilisateurs':
            // Récupération des données du formulaire
            $data = sanitize($_POST);
            // check password
            if (! preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/', $data['password'])) {
                $_SESSION['error-msg'] = "Le mot de passe doit contenir au moins un chiffre et une lettre majuscule et minuscule, et au moins 8 caractères.";
                break;
            }
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
                $_SESSION['error-msg'] = "Votre email est invalide";
            }
            $tabs = 1;
            break;
        case 'matieres':
            $matieres[] = array(
                'nom' => ucfirst(htmlspecialchars($_POST['nom'])),
                'referant' => ucfirst(htmlspecialchars($_POST['referant'])),
                'couleur' => htmlspecialchars($_POST['couleur'])
            );
            $jsonMatieres = json_encode($matieres, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/matieres.json', $jsonMatieres);
            $tabs = 2;
            break;
        case 'enseignants':
            $data = sanitize($_POST);
            $enseignants[] = array(
                'nom' => ucwords($data['nom']),
                'referant' => ucfirst($data['referant'])
            );
            $jsonEnseignants = json_encode($enseignants, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/enseignants.json', $jsonEnseignants);
            $tabs = 3;
            break;
        case 'salles':
            $salles[] = array(
                'nom' => strtoupper(htmlspecialchars($_POST['nom']))
            );
            $jsonSalles = json_encode($salles, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/salles.json', $jsonSalles);
            $tabs = 4;
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
            break;
    }
    header('Location: index.php?action=admin#tabs-' . $tabs);
    exit();
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
            // check password
            if (! preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/', $data['password'])) {
                $_SESSION['error-msg'] = "Le mot de passe doit contenir au moins un chiffre et une lettre majuscule et minuscule, et au moins 8 caractères.";
                break;
            }
            $utilisateurs[$id]['nom'] = strtoupper($data['nom']);
            $utilisateurs[$id]['prenom'] = ucfirst($data['prenom']);
            $utilisateurs[$id]['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $utilisateurs[$id]['role'] = ucfirst($data['role']);
            // Convertir le tableau PHP en JSON
            file_put_contents(WEBROOT . '/data/utilisateurs.json', json_encode($utilisateurs, JSON_PRETTY_PRINT));
            $tabs = 1;
            break;
        case 'matieres':
            $matieres[$id] = array(
                'nom' => ucfirst(htmlspecialchars($_POST['nom'])),
                'referant' => ucfirst(htmlspecialchars($_POST['referant'])),
                'couleur' => htmlspecialchars($_POST['couleur'])
            );
            file_put_contents(WEBROOT . '/data/matieres.json', json_encode($matieres, JSON_PRETTY_PRINT));
            $tabs = 2;
            break;
        case 'enseignants':
            $data = sanitize($_POST);
            $enseignants[$id] = array(
                'nom' => ucwords($data['nom']),
                'referant' => ucfirst($data['referant'])
            );
            file_put_contents(WEBROOT . '/data/enseignants.json', json_encode($enseignants, JSON_PRETTY_PRINT));
            $tabs = 3;
            break;
        case 'salles':
            $salles[$id] = array(
                'nom' => strtoupper(htmlspecialchars($_POST['nom']))
            );
            $jsonSalles = json_encode($salles, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT . '/data/salles.json', json_encode($salles, JSON_PRETTY_PRINT));
            $tabs = 4;
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
            break;
    }
    header('Location: index.php?action=admin#tabs-' . $tabs);
    exit();
}

require(WEBROOT . '/views/administration.php');
