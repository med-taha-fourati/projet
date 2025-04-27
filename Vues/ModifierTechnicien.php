<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../DAO/AppareilDAO.php';
require_once '../DAO/ReparationDAO.php';
require_once '../DAO/UtilisateurDAO.php';
require_once '../Metier/Admin.php';
require_once '../Controlleur/UtilisateurController.php';

if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];
if (UtilisateurDAO::FetchRoleById($client) != Admin::$code) {
    echo "<img src='assets/dino.png'/>403: Vous n'avez pas le droit d'acceder a cette page";
    exit;
}

//TODO - function that returns only clients in UserController.php
$clients = UtilisateurController::ListeClients();

//NOTE - you would be getting the technicien id from the get method
//TODO - add element that lets you derank the technicien to a client
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un technicien</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body class="admin_page_container global_coloring">
    <h1>Modifier Technicien</h1>
    
    <form action="../Controlleur/UtilisateurController.php" method="post">
        <fieldset>
            <legend>Formulaire de modification</legend>
                <label for="login">Login:</label>
                <input type="text" name="login" id="login" class="form-control" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <label for="nom">Nom:</label>
                <input type="text" name="nom" id="nom" class="form-control" required>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control">
                <label for="adresse">Adresse:</label>
                <input type="text" name="adresse" id="adresse" class="form-control">
                <label for="tel">Tel:</label>
                <input type="tel" name="tel" id="tel" class="form-control">
        </fieldset>
    
    <hr>
    <input type="submit" value="Modifier" name="modifier_technicien_admin" class="primary-btn-bs-props">
    </form>
</body>
</html>