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
    include_once '../Connexion/Connection.php';
    header('HTTP/1.0 403 Forbidden');
        $contents = file_get_contents('../Vues/assets/403.html');
        exit($contents);
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
            case 6:
                echo "<span>Veuiller selectionner une option</span>";
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
    <h1>Ajouter un nouveau Technicien</h1>
    <fieldset>
        <legend>Mode d'Insertion</legend>
    
    <form action="../Controlleur/UtilisateurController.php" method="post">
    <div class="existant_select">
        <!-- <select name="exist_select" id="exist_select" class="form-select">
            <option value="none">-----------------------------</option>
            <option value="existant">Depuis un client existant</option>
            <option value="nouveau">Ajouter un nouveau technicien</option>
        </select> -->
        <input type="radio" class="form-check-input" name="exist_select" id="existant_r" value="existant"> <label for="existant">Depuis un client existant</label> <br>
        <input type="radio" class="form-check-input" name="exist_select" id="nouveau_r" value="nouveau"> <label for="nouveau">Ajouter un nouveau technicien</label>
    </div>
    </fieldset>
    <br><br>
        <div class="existant" style="display: none;">
            <hr>
            <label for="client">Client:</label>
            <select name="client" id="client" class="form-select">
                <?php foreach ($clients as $client_r) { ?>
                    <option value="<?php echo $client_r->id; ?>"><?php echo $client_r->login; ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="nouveau" style="display: none;">
            <hr>
            <fieldset>
                <legend>Formulaire de creation</legend>
                <label for="login">Login:</label>
                <input type="text" name="login" id="login" class="form-control">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control">
                <label for="nom">Nom:</label>
                <input type="text" name="nom" id="nom" class="form-control">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control">
                <label for="adresse">Adresse:</label>
                <input type="text" name="adresse" id="adresse" class="form-control">
                <label for="tel">Tel:</label>
                <input type="tel" name="tel" id="tel" class="form-control">
            </fieldset>
        </div>
    
        <hr>
        <input type="submit" value="Ajouter" name="ajouter_technicien_admin" class="btn btn-primary">
    </form>
</body>
<script>
    // divs
    let existant = document.querySelector('.existant');
    let nouveau = document.querySelector('.nouveau');

    // radios
    let nouveau_r = document.querySelector('#nouveau_r');
    let existant_r = document.querySelector('#existant_r');

    existant_r.addEventListener("change", () => {
        if (existant_r.checked && !nouveau_r.checked) {
            existant.style.display = "block";
            nouveau.style.display = "none";
        } else if (nouveau_r.checked && !existant_r.checked) {
            existant.style.display = "none";
            nouveau.style.display = "block";
        } else {
            existant.style.display = "none";
            nouveau.style.display = "none";
        }
    });

    nouveau_r.addEventListener("change", () => {
        if (existant_r.checked && !nouveau_r.checked) {
            existant.style.display = "block";
            nouveau.style.display = "none";
        } else if (nouveau_r.checked && !existant_r.checked) {
            existant.style.display = "none";
            nouveau.style.display = "block";
        } else {
            existant.style.display = "none";
            nouveau.style.display = "none";
        }
    });
    
</script>
</html>