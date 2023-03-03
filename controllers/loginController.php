<?php
if (! isset($_POST['email']) && ! isset($_POST['password'])) {
    header('Location: ' . ROOT);
    $_SESSION['error-msg'] = 'Veuillez fournir un email et un mot de passe';
    exit();
}

$jsonString = file_get_contents(WEBROOT. '/data/utilisateurs.json');
$utilisateurs = json_decode($jsonString, true);
echo '<pre>';
foreach ($utilisateurs as $key => $u) {
    if($u['email'] == htmlspecialchars($_POST['email']) && $u['password'] == htmlspecialchars($_POST['password'])) {
        // si les crendentials sont conforme, on quitte la boucle en se redirigeant vers la page d'accueil
        $_SESSION['role'] = $u['role'];
        $_SESSION['nom'] = $u['nom'];
        $_SESSION['prenom'] = $u['prenom'];
        $title = 'Accueil';
        header('Location:' . ROOT);
        exit();
    }
}

$_SESSION['error-msg'] = 'Veuillez vérifier votre email ou votre mot de passe';
header('Location:' . ROOT);
exit();