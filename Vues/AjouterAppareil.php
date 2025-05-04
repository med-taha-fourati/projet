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
    header('HTTP/1.0 403 Forbidden');
        $contents = file_get_contents('../Vues/assets/403.html');
        exit($contents);
}

//TODO - function that returns only clients in UserController.php
$clients = UtilisateurController::ListeUtilisateurs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un technicien</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <link rel="stylesheet" href="Styles/breadcrumb_style.css">
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
    <h1>Ajouter une nouvelle appareil</h1>
    <hr>
    <form action="../Controlleur/AdminController.php" method="post">
        <fieldset>
            <legend>Formulaire de creation</legend>
            <label for="type">Type:</label>
            <select name="type" id="type" class="form-select">
                <option value="PC Portable">PC Portable</option>
                <option value="PC Bureau">PC Bureau</option>
            </select>
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
    <input type="submit" value="Ajouter" name="ajouter_appareil_admin" class="btn btn-primary">
    </form>
</body>
</html>