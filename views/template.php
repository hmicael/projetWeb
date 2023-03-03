<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta name="description" content="Gestion d'emploi du temps" />
        <meta charset="utf-8">
        <title><?= $title ?> | EDT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ridouane OUSMANE DOUDOU">
        <meta name="author" content="Henintsoa ANDRIAMAHADIMBY">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="public/style.css">
    </head>

    <body>
        <header>
            <nav>
                Navigation
                <a href="index.php?action=deconnect">Deconnection</a>
            </nav>
        </header>
        <main>
            <?= $content ?>
        </main>
        <footer>
            <!-- BEGIN: Footer -->
            <!-- END: Footer -->
        </footer>
        <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"
            integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
        <script src="public/script.js"></script>
        <script src="public/modals.js"></script>
    </body>
</html>