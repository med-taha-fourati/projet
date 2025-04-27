<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de reparation d'ordinateurs</title>
</head>
<body>
    <h1>Login - Gestion de reparation d'ordinateurs</h1>
    <form action="../Controlleur/AuthController.php" method="post">
        <label for="login">Login:</label>
        <input type="text" id="login" name="loginL" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="passwordL" required><br><br>

        <input type="submit" name="login_btn" value="Login">
    </form>
</body>
</html>