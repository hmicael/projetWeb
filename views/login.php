<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta name="description" content="Gestion d'emploi du temps" />
        <meta charset="utf-8">
        <title>Login | EDT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ridouane OUSMANE DOUDOU">
        <meta name="author" content="Henintsoa ANDRIAMAHADIMBY">
        <link rel="stylesheet" href="public/style.css">
    </head>

    <body>
        <main>
            <form action="index.php?action=login-check" method="POST" class="form-card">
                <h1>Login</h1>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div>
                    <label>Mot de passe:</label>
                    <input type="password" name="password" required>
                </div>
                <input type="submit" name="submit" value="Login">
            </form>
        </main>
    </body>
</html>