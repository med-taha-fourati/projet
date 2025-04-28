<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des reparations de l'ordinateur</title>
    <link rel="stylesheet" href="./Styles/style.css">
</head>
<body>
    <div style="display: flex; height: 100vh;">
        <iframe src="Navigation.php" style="width: 19%; border: none; overflow: auto;"></iframe>
        <div class="seperator"></div>
        <iframe name="destination-page" src="assets/waiting.html" style="flex-grow: 1; border: none;"></iframe>
    </div>
</body>
</html>