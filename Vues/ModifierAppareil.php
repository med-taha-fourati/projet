<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../DAO/AppareilDAO.php';
require_once '../DAO/ReparationDAO.php';
require_once '../DAO/UtilisateurDAO.php';
require_once '../Metier/Admin.php';
require_once '../Controlleur/AppareilController.php';

if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];
if (UtilisateurDAO::FetchRoleById($client) != Admin::$code) {
    include_once '../Connexion/Connection.php';
    error_403();
}

//TODO - function that returns only clients in UserController.php
$clients = UtilisateurController::ListeClients();

$appareil_id = $_GET['appareil_id'];
$appareil = AppareilController::ListeAppareilById($appareil_id);
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
    <h1>Modifier l'appareil</h1>
    <br><br>
    <form action="../Controlleur/AdminController.php" method="post">
        <input type="hidden" name="appareil_id" value="<?php echo $appareil->id; ?>">
        <fieldset>
            <legend>Formulaire de modification</legend>
            <label for="type">Type:</label>
            <input type="text" name="type" id="type" class="form-control" value="<?php echo $appareil->type ?>" required>
            <label for="marque">Marque:</label>
            <input type="text" name="marque" id="marque" class="form-control" value="<?php echo $appareil->marque ?>" required>
            <label for="modele">Modèle:</label>
            <input type="text" name="modele" id="modele" class="form-control" value="<?php echo $appareil->modele ?>" required>
            <label for="numSerie">Numéro de Série:</label>
            <input type="text" name="numSerie" id="numSerie" class="form-control" value="<?php echo $appareil->numSerie ?>" required>
            <label for="client">Client:</label>
            <select name="client" id="client" class="form-select">
                <?php foreach ($clients as $client_r) { ?>
                    <option value="<?php echo $client_r->id; ?>" <?php if ($client_r->id == $appareil->client->id) echo "selected"; ?>><?php echo $client_r->login; ?></option>
                <?php } ?>
            </select>
        </fieldset>
    
    <hr>
    <input type="submit" value="Modifier" name="modifier_appareil_admin" class="btn btn-primary primary-btn-bs-props">
    </form>
</body>
</html>