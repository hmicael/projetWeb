<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));   // chemin absolue depuis la racine du serveur
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));  // chemin depuis la racine du site
// require(WEBROOT. '/controllers/TacheController.php');
// $tacheController = new TacheController();
// var_dump($_GET);exit;
try {
    if(! isset($_SESSION['role'])) { // si la personne n'est pas encore connectée
        // si la personne vient de se connecter
        if(isset($_GET['action']) && $_GET['action'] === 'login-check') {
            require(WEBROOT. '/controllers/loginController.php');
        } else {
            require(WEBROOT. '/views/login.php');
        }
    } else {
        if (isset($_GET['action']) && $_GET['action'] !== '') {
            if ($_GET['action'] === 'deconnect') {
                session_destroy();
                header('Location:' . ROOT);
                exit();
            } else if ($_GET['action'] === 'visualiser') {
                require(WEBROOT. '/controllers/visualisationController.php');
            } else if ($_GET['action'] === 'admin') {
                require(WEBROOT. '/controllers/administrationController.php');
            } else {
                throw new Exception("Error 404 : Page not found");
            }
        } else {
            // Par defaut: page d'accueuil
            $title = 'Accueil';
            require(WEBROOT. '/views/accueil.php');
        }
    }   
} catch (Exception $e) {
	require(WEBROOT. '/views/error.php');
}
