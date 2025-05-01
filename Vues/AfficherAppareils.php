<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../DAO/AppareilDAO.php';
require_once '../DAO/ReparationDAO.php';
require_once '../DAO/UtilisateurDAO.php';
require_once '../Metier/Appareil.php';
require_once '../Metier/Reparation.php';
require_once '../Metier/Admin.php';
require_once '../Controlleur/AdminController.php';
require_once '../Controlleur/AppareilController.php';

session_start();
if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];
if (UtilisateurDAO::FetchRoleById($client) != Admin::$code) {
    echo "<img src='assets/dino.png'/>403: Vous n'avez pas le droit d'acceder a cette page";
    exit;
}

$reparations_tout = AppareilController::ListeToutesAppareils();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de reparation des ordinateurs</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<h1>Liste des appareils</h1>
<body class="admin_page_container global_coloring">
    <hr>
<h5><?php echo sizeof($reparations_tout); ?> appareils</h5>
    <table class="table table-bs-props table-responsive">
        <tr>
            <th>Type</th>
            <th>Marque</th>
            <th>Modele</th>
            <th>Numero Serie</th>
            <th>Client</th>
            <th>Actions</th>
        </tr>
        <?php if (sizeof($reparations_tout) == 0) {
            ?> <tr><td colspan="5">Aucun appareil trouve</td></tr> <?php
        } else {
            foreach ($reparations_tout as $appareil) {
                ?>
                <tr name="<?php echo $appareil->id; ?>">
                    <form action="../Controlleur/AdminController.php" method="post">
                        <input type="hidden" name="rep_id" value="<?php echo $appareil->id; ?>">
                        <td><?php echo $appareil->type; ?></td>
                        <td><?php echo $appareil->marque; ?></td>
                        <td><?php echo $appareil->modele; ?></td>
                        <td><?php echo $appareil->numSerie; ?></td>
                        <td><?php echo $appareil->client->login; ?></td>
                        <td>
                            <input type="submit" name="action_admin_app" class="btn btn-outline-danger" value="Supprimer"> <!-- supprimer -->
                            <input type="submit" name="action_admin_app" class="btn btn-outline-primary" value="Modifier">
                        </td>
                    </form>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <a class="btn btn-primary" href="../Vues/AjouterAppareil.php">Ajouter un appareil?</a>
</body>
</html>