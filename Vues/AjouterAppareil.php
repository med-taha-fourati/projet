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
    <h1>Ajouter une nouvelle appareil</h1>
    <br><br>
    <form action="../Controlleur/AdminController.php" method="post">
        <fieldset>
            <legend>Formulaire de creation</legend>
            <label for="type">Type:</label>
            <input type="text" name="type" id="type" class="form-control" required>
            <label for="marque">Marque:</label>
            <input type="text" name="marque" id="marque" class="form-control" required>
            <label for="modele">Modèle:</label>
            <input type="text" name="modele" id="modele" class="form-control" required>
            <label for="numSerie">Numéro de Série:</label>
            <input type="text" name="numSerie" id="numSerie" class="form-control" required>
            <label for="client">Client:</label>
            <select name="client" id="client" class="form-select">
                <?php foreach ($clients as $client_r) { ?>
                    <option value="<?php echo $client_r->id; ?>"><?php echo $client_r->login; ?></option>
                <?php } ?>
            </select>
        </fieldset>
    
    <hr>
    <input type="submit" value="Ajouter" name="ajouter_appareil_admin" class="primary-btn-bs-props">
    </form>
</body>
</html>