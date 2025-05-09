<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../DAO/AppareilDAO.php';
require_once '../DAO/ReparationDAO.php';
require_once '../DAO/UtilisateurDAO.php';
require_once '../Metier/Admin.php';
require_once '../Controlleur/UtilisateurController.php';
require_once '../Controlleur/ReparationController.php';
require_once '../Controlleur/AdminController.php';
require_once '../Controlleur/AppareilController.php';

if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];
if (!AdminController::VerifierAdmin($client)) {
    header('HTTP/1.0 403 Forbidden');
        $contents = file_get_contents('../Vues/assets/403.html');
        exit($contents);
}

//TODO - function that returns only clients in UserController.php
$clients = UtilisateurController::ListeUtilisateurs();

//NOTE - you would be getting the technicien id from the get method
$technicien_id = $_GET['technicien_id'];
$technicien = UtilisateurController::ListeTechniciensById($technicien_id);
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
<?php
if (isset($_GET['status']) && $_GET['status'] == "false") {
    ?>
    <div class="breadcrumb-failure">
        <?php
        switch ($_GET['errcode']) {
            case 3:
                echo "<span>Verifier champs vides</span>";
                break;
            default:
                echo "<span>Erreur inconnu</span>";
                break;
        }
        ?>
    <button class="breadcrumb-button" onClick="closeBreakcrumb();">x</button>
    <script>
        function closeBreakcrumb() {
            document.querySelector('.breadcrumb-failure').style.display = 'none';
        }
    </script>
    </div>
    <?php
}
?>
<body class="admin_page_container global_coloring">
    <h1>Modifier Technicien</h1>
    <fieldset>
        <legend>Retrogarder le technicien</legend>
        <form action="../Controlleur/UtilisateurController.php" method="post">
            <input type="hidden" name="technicien_id" value="<?php echo $technicien->id; ?>">
            <input type="submit" value="Rétrograder le technicien en client" name="derank_technicien" class="btn btn-warning">
        </form>
    </fieldset>
    <hr>
    <form action="../Controlleur/AdminController.php" method="post">
        <input type="hidden" name="technicien_id" value="<?php echo $technicien->id; ?>">
        <fieldset>
            <legend>Formulaire de modification</legend>
                <label for="login">Login:</label>
                <input type="text" name="login" id="login" value="<?php echo $technicien->login ?>" class="form-control" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="<?php echo $technicien->password ?>" class="form-control" required>
                <label for="nom">Nom:</label>
                <input type="text" name="nom" id="nom" value="<?php echo $technicien->nom ?>"class="form-control" required>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $technicien->email ?>" class="form-control">
                <label for="adresse">Adresse:</label>
                <input type="text" name="adresse" id="adresse" value="<?php echo $technicien->adresse ?>" class="form-control">
                <label for="tel">Tel:</label>
                <input type="tel" name="tel" id="tel" value="<?php echo $technicien->tel; ?>" class="form-control">
        </fieldset>
    <hr>
    <input type="submit" value="Modifier" name="modifier_technicien_admin" class="btn btn-primary primary-btn-bs-props">
    </form>
</body>
</html>