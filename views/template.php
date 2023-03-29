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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="public/style.css">
    </head>

    <body>
        <header>
            <nav>
                <ul>
                    <?php
                        if (isset($_GET['action'])) {
                            echo '<li><a href="index.php">Accueil</a></li>';
                        }
                    ?>
                    <li>
                        <a href="index.php?action=deconnect" class="logout-button">
                            Deconnection 
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- <nav>
                Navigation
                <a href="index.php?action=deconnect" class="logout-button">Deconnection</a>
            </nav> -->
        </header>
        <main>
        <section id="dialog-confirm" title="Suppression">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
                Cet &eacute;l&eacute;ment sera d&eacute;finitivement supprim&eacute; et ne pourra pas &ecirc;tre r&eacute;cup&eacute;r&eacute;. &Ecirc;tes-vous s&ucirc;r ?
            </p>
        </section>
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
    </body>
</html>