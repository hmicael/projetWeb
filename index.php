<?php
session_start();
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));   // chemin absolue depuis la racine du serveur
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));  // chemin depuis la racine du site
try {
    if(! isset($_SESSION['role'])) { // si la personne n'est pas encore connectée
        // si la personne vient de se connecter
        if(isset($_GET['action']) && $_GET['action'] === 'login-check') {
            require(WEBROOT. '/controllers/LoginController.php');
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
                require(WEBROOT. '/controllers/VisualisationController.php');
            } else if ($_GET['action'] === 'admin') {
                require(WEBROOT. '/controllers/AdministrationController.php');
            } else if ($_GET['action'] === 'ajax' && isset($_GET['search'])) {
                require(WEBROOT. '/controllers/AjaxRequestController.php');
            } else if (preg_match('/^edt-(delete|add|edit)$/', $_GET['action'])) {
                require(WEBROOT. '/controllers/EdtController.php');
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
