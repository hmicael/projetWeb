<?php
$title = 'Visualisation';
if ($_SESSION['role'] == 'etudiant') {
    require(WEBROOT . '/views/visualisation.php');
} else {
    require(WEBROOT . '/views/editionEdt.php');
}