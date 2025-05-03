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

function filterByItem($appareils) {
    $filter = $_GET['filter'];
    $filter_option = $_GET['filter_option'];
    switch ($filter_option) {
        case 'login':
            $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                return stripos($appareil->login, $filter) !== false;
        });
        break;
    case 'nom':
            $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                return stripos($appareil->nom, $filter) !== false;
        });
        break;
        case 'email':
            $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                return stripos($appareil->email, $filter) !== false;
        });
        break;
        case 'adresse':
            $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                return stripos($appareil->adresse, $filter) !== false;
        });
        break;
        case 'tel':
            $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                return stripos($appareil->tel, $filter) !== false;
        });
        break;
    }
    return $appareils;    
}

if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];
if (UtilisateurDAO::FetchRoleById($client) != Admin::$code) {
    include_once '../Connexion/Connection.php';
    header('HTTP/1.0 403 Forbidden');
        $contents = file_get_contents('../Vues/assets/403.html');
        exit($contents);
}

$reparations_tout = UtilisateurController::ListeTechniciens();

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    $reparations_tout = filterByItem($reparations_tout);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de reparation des ordinateurs</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <link rel="stylesheet" href="Styles/breadcrumb_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<h1>Liste des techniciens</h1>
<?php 
if (isset($_GET['status']) && $_GET['status'] == true) {
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
} else if (isset($_GET['status']) && $_GET['status'] == false) {
    ?>
    <div class="breadcrumb-failure">
    <span>Echec ajout</span>
    <button class="breadcrumb-button" onClick="closeBreakcrumb();">x</button>
    <script>
        function closeBreakcrumb() {
            document.querySelector('.breadcrumb-success').style.display = 'none';
        }
    </script>
    <?php
}
?>
<hr>
<body class="admin_page_container global_coloring">
<h5><?php echo sizeof($reparations_tout); ?> Techniciens</h5>
<hr>
<form action="AfficherTechniciens.php" method="get">
        <div class="px-5 row">
            <div class="col-2">
                <select name="filter_option" id="filter_option" class="form-select w-100">
                    <option value="login" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "login") echo "selected"; else ''; ?>>Par Login</option>
                    <option value="nom" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "nom") echo "selected"; else ''; ?>>Par Nom</option>
                    <option value="email" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "email") echo "selected"; else ''; ?>>Par Email</option>
                    <option value="adresse" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "adresse") echo "selected"; else ''; ?>>Par Adresse</option>
                    <option value="tel" <?php if (isset($_GET['filter_option']) && $_GET['filter_option'] == "tel") echo "selected"; else ''; ?>>Par Tel</option>
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
            <th>Login</th>
            <th>Password</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Adresse</th>
            <th>Tel</th>
            <th>Actions</th>
        </tr>
        <?php if (sizeof($reparations_tout) == 0) {
            ?> <tr><td colspan="6">Aucun technicien trouve ¯\_(ツ)_/¯</td></tr> <?php
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
    <a class="btn btn-primary" href="../Vues/AjouterTechnicien.php">Ajouter un technicien?</a>
    
</body>
</html>