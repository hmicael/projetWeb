<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="description" content="Error page"/>
    <meta charset="utf-8">
    <title>Error | EDT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Ridouane OUSMANE DOUDOU">
    <meta name="author" content="Henintsoa ANDRIAMAHADIMBY">
    <style>
        body > section {
            text-align: center;
            font-weight: bold;
            font-size: 25px;
            display: flex;
            width: 50%;
            height: 200px;
            margin: auto;
        }

        p {
            margin: auto; /* Important */
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<section>
    <p> <?php echo $e->getMessage() ?>
        revenir &agrave;
        <a href="index.php">l'accueil</a></p>
</section>
</body>
</html>