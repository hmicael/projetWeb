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


//Suppression
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



//Ajout Ou Modification
if(isset($_GET['create'])) {
    $entity = htmlspecialchars($_GET['create']);
    switch ($entity) {
        case 'utilisateurs':
            // Récupération des données du formulaire
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Création d'un nouvel objet
                $newUtilisateur = array(
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role
                );
                // Ajout du nouvel objet dans le tableau de données
                $utilisateurs[] = $newUtilisateur;

                // Conversion du tableau PHP en contenu JSON
                $jsonUtilisateur = json_encode($utilisateurs);

                // Écriture du contenu JSON dans le fichier
                file_put_contents('utilisateurs.json', $jsonUtilisateur);
            } else {
                echo "Adresse e-mail est invalide";
            }
            break;
        case 'matieres':
            $nom = $_POST['nom'];
            $newMatieres = array(
                'nom' => $nom,
            );
            $matieres[] = $newMatieres;
            $jsonMatieres = json_encode($matieres);
            file_put_contents('matieres.json', $jsonMatieres);
            break;
        case 'enseignants':
             $nom = $_POST['nom'];
             $matiere = $_POST['matiere'];
             $newEnseignants = array(
                'nom' => $nom,
                'matiere' => $matiere
                );
                 $enseignants[] = $newEnseignants;
                 $jsonEnseignants = json_encode($enseignants);
                 file_put_contents('enseignants.json', $jsonEnseignants);
                break;
        case 'salles':
            $nom = $_POST['nom'];
            $newSalles = array(
            'nom' => $nom
            );
            $salles[] = $newSalles;
            $jsonSalles = json_encode($salles);
            file_put_contents('salles.json', $jsonSalles);
            break;
        default:
            throw new Exception("L'element $entity n'existe pas");
            break;
    }
    header('Location: index.php?action=admin');
    exit();
}









require(WEBROOT . '/views/administration.php');


?>