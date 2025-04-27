<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de reparation d'ordinateurs</title>
</head>
<body>
    <h1>Inscription - Gestion de reparation d'ordinateurs</h1>
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
        
        <input type="submit" name="inscription" value="Inscription">
        <input type="reset" value="Annuler">
    </form>
</body>
</html>