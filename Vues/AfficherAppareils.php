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
if (!AdminController::VerifierAdmin($client)) {
    include_once '../Connexion/Connection.php';
    header('HTTP/1.0 403 Forbidden');
        $contents = file_get_contents('../Vues/assets/403.html');
        exit($contents);
}

$reparations_tout = AppareilController::ListeToutesAppareils();
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    $reparations_tout = AppareilController::filterByItem($reparations_tout, $_GET['filter'], $_GET['filter_option']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de reparation des ordinateurs</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="Styles/breadcrumb_style.css">
</head>
<?php 
if (isset($_GET['status']) && $_GET['status'] == "true") {
    ?>
    <div class="breadcrumb-success">
    <span>Operation terminee avec succees</span>
    <button class="breadcrumb-button" onClick="closeBreakcrumb();">x</button>
    <script>
        function closeBreakcrumb() {
            document.querySelector('.breadcrumb-success').style.display = 'none';
        }
    </script>
</div>
    <?php
} ?>

<h1>Liste des appareils</h1>
<body class="admin_page_container global_coloring">
    <hr>
<h5><?php echo sizeof($reparations_tout); ?> appareils</h5>
<hr>
<form action="AfficherAppareils.php" method="get">
        <div class="px-5 row">
            <div class="col-2">
                <select name="filter_option" id="filter_option" class="form-select w-100">
                    <option value="type" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "type") echo "selected"; else ''; ?>>Par Type</option>
                    <option value="marque" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "marque") echo "selected"; else ''; ?>>Par Marque</option>
                    <option value="modele" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "modele") echo "selected"; else ''; ?>>Par Modele</option>
                    <option value="numSerie" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "numSerie") echo "selected"; else ''; ?>>Par Num serie</option>
                    <option value="login-client" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "login-client") echo "selected"; else ''; ?>>Par Client</option>
                </select>
            </div>
            <div class="col-8">
                <input type="text" name="filter" class="form-control w-100" value="<?php echo $filter ?? ''; ?>" placeholder="Rechercher">
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary w-100">Rechercher</button>
            </div>
        </div>
    </form>
    <hr>
<style>
        .table-shadow {
            margin: 1rem 0;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
    <table class="table table-hover table-striped table-responsive table-shadow">
        <tr>
            <th>Type</th>
            <th>Marque</th>
            <th>Modele</th>
            <th>Numero Serie</th>
            <th>Client</th>
            <th>Actions</th>
        </tr>
        <?php if (sizeof($reparations_tout) == 0) {
            ?> <tr><td colspan="5">Aucun appareil trouve ¯\_(ツ)_/¯</td></tr> <?php
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