<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta name="description" content="Gestion d'emploi du temps" />
        <meta charset="utf-8">
        <title><?= $title ?> | EDT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ridouane OUSMANE DOUDOU">
        <meta name="author" content="Henintsoa ANDRIAMAHADIMBY">
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
    </body>
</html>