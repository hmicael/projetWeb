<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta name="description" content="Error page" />
        <meta charset="utf-8">
        <title>Error | EDT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ridouane OUSMANE DOUDOU">
        <meta name="author" content="Henintsoa ANDRIAMAHADIMBY">
    </head>
    <body>
        <section>
            <?php echo $e->getMessage() ?>
            revenir &agrave; 
            <a href="index.php">l'accueil</a>
        </section>
    </body>
</html>