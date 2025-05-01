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
    include_once '../Connexion/Connection.php';
    error_403();
}

$appareil = AppareilDAO::FindAll();

//TODO - function that returns only techniciens in UserController.php
$techniciens = UtilisateurController::ListeTechniciens();

//TODO - function that returns only clients in UserController.php
$clients = UtilisateurController::ListeClients();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter reparation</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body class="admin_page_container global_coloring">
    <h1>Ajouter une nouvelle Reparation</h1>
    <form action="../Controlleur/AdminController.php" method="post">
    <fieldset>
            <legend>Section Selection Client/Technicien</legend>

            <div class="row">
                <div class="col">
            <label for="technicien">Technicien:</label>
            <select name="technicien" id="technicien" class="form-select">
                <?php foreach ($techniciens as $technicien) { ?>
                    <option value="<?php echo $technicien->id; ?>"><?php echo $technicien->login; ?></option>
                <?php } ?>
            </select>
        </div>
                    
        <div class="col">
            <label for="client">Client:</label>
            <select name="client" id="client" class="form-select">
                <?php foreach ($clients as $client_r) { ?>
                    <option value="<?php echo $client_r->id; ?>"><?php echo $client_r->login; ?></option>
                <?php } ?>
            </select>
        </div>
            </div>
        </fieldset>
        <hr>
    
        <fieldset>
            <legend>Section Reparation</legend>

            <div class="row">
                <div class="col">
            <label for="date_depot">Date de depot:</label>
            <input class="form-control" type="date" name="date_depot" id="date_depot">
            </div>
                    
            <div class="col">
            <label for="date_fin_pr">Date de fin prevue:</label>
            <input class="form-control" type="date" name="date_fin_pr" id="date_fin_pr">
                </div>

            <div class="col">
            <label for="date_fin_re">Date de fin reelle:</label>
            <input class="form-control" type="date" name="date_fin_re" id="date_fin_re">
            </div>
            </div>

            <label for="panne">Panne:</label>
            <textarea class="form-control" name="panne" id="panne"></textarea>
            

            <label for="cout">Cout:</label>
            <input class="form-control" type="number" min="0" name="cout" id="cout>
            

            <label for="statut">Statut:</label>
            <select name="statut" id="statut" class="form-select">
                <option value="0" >En attente</option>
                <option value="1" >En reparation</option>
                <option value="2">Terminé</option>
            </select>
            
        </fieldset>
        <hr>

        <fieldset>
            <legend>Section Selection Appareil</legend>

            <label for="appareil">Appareil:</label>
            <select name="appareil" id="appareil" class="form-select">
                <?php foreach ($appareil as $app) { ?>
                    <option value="<?php echo $app->id; ?>"><?php echo $app->marque; ?></option>
                <?php } ?>
            </select>
        </fieldset>
        <hr>

        <input type="submit" class="btn btn-primary" name="modifier_rep" value="Ajouter">
    </form>
</body>
</html>