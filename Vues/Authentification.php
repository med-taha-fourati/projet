<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de reparation d'ordinateurs</title>
    <link rel="stylesheet" href="./Styles/auth_style.css">
    <link rel="stylesheet" href="Styles/breadcrumb_style.css">
</head>
<body>
    <div class="the-whole-thing">
        <div class="the-image">
            <div class="winder">
                <h1>Gestion de reparation d'ordinateurs</h1>
            </div>
        </div>
        <div class="the-form">
            <h1>Login</h1>
            <form action="../Controlleur/AuthController.php" method="post">
                <label for="login">Login:</label>
                <input type="text" id="login" name="loginL" required>
                <?php
                if (isset($_GET['status']) 
                && $_GET['status'] == 'false' 
                && isset($_GET['errcode'])
                ) {
                    switch ($_GET['errcode']) {
                        case 2:
                            echo "<p style='color: red;'>Login ou mot de passe vide</p>";
                            break;
                        case 3:
                            echo "<p style='color: red;'>Login ou mot de passe incorrect</p>";
                            break;
                    }
                }
                 ?>
                <br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="passwordL" required>
                <?php
                if (isset($_GET['status']) 
                && $_GET['status'] == 'false' 
                && isset($_GET['errcode'])) {
                    switch ($_GET['errcode']) {
                        case 2:
                            echo "<p style='color: red;'>Login ou mot de passe vide</p>";
                            break;
                        case 3:
                            echo "<p style='color: red;'>Login ou mot de passe incorrect</p>";
                            break;
                    }
                }
                 ?>

<br><br>
                <input type="submit" name="login_btn" value="Login">
            </form>
            <a href="../Vues/InscriptionUtilisateur.php">Pas de compte?</a>
        </div>
    </div>
</body>
</html>