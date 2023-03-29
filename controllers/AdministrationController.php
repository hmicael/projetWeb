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
            header('Location: index.php?action=admin');
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
 * Fonction qui vérifie si une valeur existe déjà
 *
 * @param $uniqueValue
 * @param $key
 * @param array $data
 * @param int $tabs
 * @return void
 */
function checkUnique($uniqueValue, $key, array $data, int $tabs) {
    foreach ($data as $value) {
        if ($value[$key] == $uniqueValue) {
            $_SESSION['error-msg'] = "La valeur $uniqueValue existe déjà";
            header('Location: index.php?action=admin#tabs-' . $tabs);
            exit();
        }
    }
}

/**
 * Fonction qui test si un id existe dans un tableau
 *
 * @param [type] $id
 * @param array $data
 * @return void
 */
function checkIdExist($id, array $data) {
    if (! array_key_exists($id, $data)) {
        throw new Exception('La page n\'existe pas');
    }
}

$title = 'Administration';
// Ouverture des fichiers
$utilisateurs = json_decode(file_get_contents(WEBROOT . '/data/utilisateurs.json'), true);
$enseignants = json_decode(file_get_contents(WEBROOT . '/data/enseignants.json'), true);
$matieres = json_decode(file_get_contents(WEBROOT . '/data/matieres.json'), true);
$salles = json_decode(file_get_contents(WEBROOT . '/data/salles.json'), true);
$tabs = 1; // correspond à l'id du tabs dans l'affichage

// Suppression
if(isset($_GET['delete'])) {
    if (! $_GET['id']) {
        throw new Exception('Vous devez spécifier un identifiant');
    }
    if (! $_GET['delete']) {
        throw new Exception('Vous devez spécifier un element à supprimer');
    }
    $id = intval($_GET['id']) - 1;
    $entity = htmlspecialchars($_GET['delete']);
    switch ($entity) {
        case 'utilisateurs':
            checkIdExist($id, $utilisateurs);
            unset($utilisateurs[$id]);
            file_put_contents(WEBROOT . '/data/utilisateurs.json', json_encode($utilisateurs, JSON_PRETTY_PRINT));
            $tabs = 1;
            break;
        case 'matieres':
            checkIdExist($id, $matieres);
            unset($matieres[$id]);
            file_put_contents(WEBROOT . '/data/matieres.json', json_encode($matieres, JSON_PRETTY_PRINT));
            $tabs = 2;
            break;
        case 'enseignants':
            checkIdExist($id, $enseignants);
            unset($enseignants[$id]);
            file_put_contents(WEBROOT . '/data/enseignants.json', json_encode($enseignants, JSON_PRETTY_PRINT));
            $tabs = 3;
            break;
        case 'salles':
            checkIdExist($id, $salles);
            unset($salles[$id]);
            file_put_contents(WEBROOT . '/data/salles.json', json_encode($salles, JSON_PRETTY_PRINT));
            $tabs = 4;
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
    }
    header('Location: index.php?action=admin#tabs-' . $tabs);
    exit();
}

// Ajout 
if(isset($_GET['create'])) {
    $entity = htmlspecialchars($_GET['create']);
    switch ($entity) {
        case 'utilisateurs':
            $tabs = 1;
            // Récupération des données du formulaire
            $data = sanitizeAndCheck($_POST, ['nom', 'prenom', 'password', 'email', 'role']);
            checkUnique($data['email'], 'email', $utilisateurs, $tabs);
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
            break;
        case 'matieres':
            $tabs = 2;
            $data = sanitizeAndCheck($_POST, ['nom', 'referent', 'couleur']);
            checkUnique($data['nom'], 'nom', $matieres, $tabs);
            $matieres[] = array(
                'nom' => ucfirst($data['nom']),
                'referent' => ucfirst($data['referent']),
                'couleur' => $data['couleur']//  . '80'// 80 pour la transparence
            );
            $jsonMatieres = json_encode($matieres, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/matieres.json', $jsonMatieres);
            break;
        case 'enseignants':
            $tabs = 3;
            $data = sanitizeAndCheck($_POST, ['nom', 'referent']);
            checkUnique($data['nom'], 'nom', $enseignants, $tabs);
            $enseignants[] = array(
                'nom' => ucwords($data['nom']),
                'referent' => ucfirst($data['referent'])
            );
            $jsonEnseignants = json_encode($enseignants, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/enseignants.json', $jsonEnseignants);
            break;
        case 'salles':
            $tabs = 4;
            $data = sanitizeAndCheck($_POST, ['nom']);
            checkUnique($data['nom'], 'nom', $salles, $tabs);
            $salles[] = array(
                'nom' => strtoupper($data['nom'])
            );
            $jsonSalles = json_encode($salles, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/salles.json', $jsonSalles);
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
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
            $tabs = 1;
            $data = sanitizeAndCheck($_POST, ['nom', 'prenom', 'password', 'email', 'role']);
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
            break;
        case 'matieres':
            $tabs = 2;
            $data = sanitizeAndCheck($_POST, ['nom', 'referent', 'couleur']);
            $matieres[$id] = array(
                'nom' => ucfirst($data['nom']),
                'referent' => ucfirst($data['referent']),
                'couleur' => $data['couleur']//  . '80'// 80 pour la transparence
            );
            file_put_contents(WEBROOT . '/data/matieres.json', json_encode($matieres, JSON_PRETTY_PRINT));
            break;
        case 'enseignants':
            $tabs = 3;
            $data = sanitizeAndCheck($_POST, ['nom', 'referent']);
            $enseignants[$id] = array(
                'nom' => ucwords($data['nom']),
                'referent' => ucfirst($data['referent'])
            );
            file_put_contents(WEBROOT . '/data/enseignants.json', json_encode($enseignants, JSON_PRETTY_PRINT));
            break;
        case 'salles':
            $tabs = 4;
            $data = sanitizeAndCheck($_POST, ['nom']);
            $salles[$id] = array(
                'nom' => strtoupper($data['nom'])
            );
            $jsonSalles = json_encode($salles, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT . '/data/salles.json', json_encode($salles, JSON_PRETTY_PRINT));
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
    }
    header('Location: index.php?action=admin#tabs-' . $tabs);
    exit();
}

require(WEBROOT . '/views/administration.php');
