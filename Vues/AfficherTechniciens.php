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
require_once '../Controlleur/UtilisateurController.php';

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

$reparations_tout = UtilisateurController::ListeTechniciens();
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
<h1>Liste des techniciens</h1>
<body class="admin_page_container global_coloring">
    <table class="table table-bs-props table-responsive">
        <tr>
            <th>Login</th>
            <th>Password</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Adresse</th>
            <th>Tel</th>
            <th>Actions</th>
        </tr>
        <?php if (sizeof($reparations_tout) == 0) {
            ?> <tr><td colspan="6">Aucun appareil trouve</td></tr> <?php
        } else {
            foreach ($reparations_tout as $appareil) {
                ?>
                <tr name="<?php echo $appareil->id; ?>">
                    <form action="../Controlleur/AdminController.php" method="post">
                        <input type="hidden" name="rep_id" value="<?php echo $appareil->id; ?>">
                        <td><?php echo $appareil->login; ?></td>
                        <td><?php echo $appareil->password; ?></td>
                        <td><?php echo $appareil->nom; ?></td>
                        <td><?php echo $appareil->email; ?></td>
                        <td><?php echo $appareil->adresse; ?></td>
                        <td><?php echo $appareil->tel; ?></td>
                        <td>
                            <input type="submit" class="btn btn-outline-danger" name="action_admin_tech" value="Supprimer"> <!-- supprimer -->
                            <input type="submit" class="btn btn-outline-primary" name="action_admin_tech" value="Modifier">
                        </td>
                    </form>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <a class="btn btn-link" href="../Vues/AjouterTechnicien.php">Ajouter un technicien?</a>
</body>
</html>