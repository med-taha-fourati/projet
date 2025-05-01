<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de reparation d'ordinateurs</title>
    <link rel="stylesheet" href="./Styles/auth_style.css">
</head>
<body>
    <div class="the-whole-thing">
        <div class="the-image">
            <div class="winder">
                <h1>Gestion de reparation d'ordinateurs</h1>
            </div>
        </div>
        <div class="the-form">
            <h1>S'inscrire</h1>
            <form action="../Controlleur/AuthController.php" method="post">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="adresse">Adresse:</label>
                <input type="text" id="adresse" name="adresse" required><br><br>

                <label for="tel">Telephone:</label>
                <input type="number" min="10000000" max="99999999" id="tel" name="tel" required><br><br>

                <div class="action-buttons">
                    <input type="reset" value="Reinitialiser">
                    <input type="submit" name="inscription" value="Inscription">
                
                </div>
            </form>
            <a href="../Vues/Authentification.php">Avez vous deja un compte?</a>
        </div>
    </div>
    
</body>
</html>