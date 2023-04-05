<?php
if (! isset($_POST['email']) && ! isset($_POST['password'])) {
    $_SESSION['error-msg'] = 'Veuillez fournir un email et un mot de passe';
    header('Location: ' . ROOT);
    exit();
}

$jsonString = file_get_contents(WEBROOT. '/data/utilisateurs.json');
$utilisateurs = json_decode($jsonString, true);
echo '<pre>';
foreach ($utilisateurs as $key => $u) {
    if($u['email'] === htmlspecialchars($_POST['email']) && password_verify(htmlspecialchars($_POST['password']), $u['password'])) {
        // si les crendentials sont conforme, on quitte la boucle en se redirigeant vers la page d'accueil
        $_SESSION['id'] = $key;
        $_SESSION['role'] = strtolower($u['role']);
        $_SESSION['nom'] = $u['nom'];
        $_SESSION['prenom'] = $u['prenom'];
        header('Location:' . ROOT);
        exit();
    }
}

$_SESSION['error-msg'] = 'Veuillez vérifier votre email ou votre mot de passe';
header('Location:' . ROOT);
exit();
