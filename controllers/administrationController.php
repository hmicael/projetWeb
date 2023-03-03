<?php
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
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $role = htmlspecialchars($_POST['role']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Création d'un nouvel objet
                $newUtilisateur = array(
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'password' => $password,
                    'email' => $email,
                    'role' => $role
                );
                // Ajout du nouvel objet dans le tableau de données
                $utilisateurs[] = $newUtilisateur;

                // Conversion du tableau PHP en contenu JSON
                $jsonUtilisateur = json_encode($utilisateurs, JSON_PRETTY_PRINT);

                // Écriture du contenu JSON dans le fichier
                file_put_contents(WEBROOT .  '/data/utilisateurs.json', $jsonUtilisateur);
                echo json_encode(['status' => 'ok']);
                die();
            } else {
                echo json_encode(['status' => 'Adresse e-mail est invalide']);
                die();
            }
            break;
        case 'matieres':
            $nom = htmlspecialchars($_POST['nom']);
            $newMatieres = array(
                'nom' => $nom,
            );
            $matieres[] = $newMatieres;
            $jsonMatieres = json_encode($matieres, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/matieres.json', $jsonMatieres);
            break;
        case 'enseignants':
             $nom = htmlspecialchars($_POST['nom']);
             $matiere = htmlspecialchars($_POST['matiere']);
             $newEnseignants = array(
                'nom' => $nom,
                'matiere' => $matiere
                );
                 $enseignants[] = $newEnseignants;
                 $jsonEnseignants = json_encode($enseignants, JSON_PRETTY_PRINT);
                 file_put_contents(WEBROOT .  '/data/enseignants.json', $jsonEnseignants);
                break;
        case 'salles':
            $nom = htmlspecialchars($_POST['nom']);
            $newSalles = array(
            'nom' => $nom
            );
            $salles[] = $newSalles;
            $jsonSalles = json_encode($salles, JSON_PRETTY_PRINT);
            file_put_contents(WEBROOT .  '/data/salles.json', $jsonSalles);
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
            break;
    }
    header('Location: index.php?action=admin');
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
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $role = htmlspecialchars($_POST['role']);
            $utilisateur = $utilisateurs[$id];
            // Modifier les informations de l'utilisateur
            $utilisateur['nom'] = $nom;
            $utilisateur['prenom'] = $prenom;
            $utilisateur['email'] = $email;
            $utilisateur['password'] = $password;
            $utilisateur['role'] = $role;
            // Convertir le tableau PHP en JSON
            file_put_contents(WEBROOT . '/data/utilisateurs.json', json_encode($utilisateurs, JSON_PRETTY_PRINT));
            break;
        case 'matieres':
            $nom = htmlspecialchars($_POST['nom']);
            $matiere = $matieres[$id];
            $matiere['nom'] = $nom;
            file_put_contents(WEBROOT . '/data/matieres.json', json_encode($matieres, JSON_PRETTY_PRINT));
            break;
        case 'enseignants':
            $nom = htmlspecialchars($_POST['nom']);
            $matiere = htmlspecialchars($_POST['matiere']);
            $enseignant = $enseignants[$id];
            $enseignant['nom'] = $nom;
            $enseignant['matiere'] = $matiere;
            file_put_contents(WEBROOT . '/data/enseignants.json', json_encode($enseignants, JSON_PRETTY_PRINT));
            break;
        case 'salles':
            $nom = htmlspecialchars($_POST['nom']);
            $salle = $salles[$id];
            $salle['nom'] = $nom;
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
