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
            <div class="background-image"></div>
            <div class="login-form">
                <h1>Login</h1>
                <form action="index.php?action=login-check" method="POST" class="form-card">*
                    <?php
                        if (isset($_SESSION['error-msg'])) {
                            echo '<span class="error-msg">' . $_SESSION['error-msg'] . '</span>';
                            unset($_SESSION['error-msg']);
                        }
                    ?>
                    <div>
                        <label>Email:</label>
                        <input type="email" name="email" required autofocus>
                    </div>
                    <div>
                        <label>Mot de passe:</label>
                        <input type="password" name="password" required>
                    </div>
                    <input type="submit" name="submit" value="Login">
                </form>
            </div>
        </main>
    </body>
</html>