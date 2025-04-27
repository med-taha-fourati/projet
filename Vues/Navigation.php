<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../Metier/Utilisateur.php';
require_once '../Metier/Client.php';
require_once '../Metier/Technicien.php';
require_once '../Metier/Admin.php';
require_once '../Metier/Appareil.php';

require_once '../DAO/UtilisateurDAO.php';

$liste_destinations_client = [
    "Accueil" => ["../Vues/Accueil.php", "fas fa-house"],
];

$liste_destinations_technicien = [
    "Accueil" => ["../Vues/Accueil.php", "fas fa-house"],
    "InterfaceTechnicien" => ["../Vues/InterfaceTechnicien.php", "fas fa-cog"],
];

$liste_destinations_admin = [
    "Accueil" => ["../Vues/Accueil.php", "fas fa-house"],
    "Administration" => ["../Vues/Administration.php", "fas fa-user"],
    "InterfaceTechnicien" => ["../Vues/InterfaceTechnicien.php", "fas fa-cog"],
    "Afficher Techniciens" => ["../Vues/AfficherTechniciens.php", "fas fa-wrench"],
    "Afficher Appareils" => ["../Vues/AfficherAppareils.php", "fas fa-desktop"],
];

$liste_actuelle = [];
$client_icon = "";

session_start();
if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];

switch (UtilisateurDAO::FetchRoleById($client)) {
    case Client::$code:
        $liste_actuelle = $liste_destinations_client;
        $client_icon = "fas fa-circle-user";
        break;
    case Technicien::$code:
        $liste_actuelle = $liste_destinations_technicien;
        $client_icon = "fas fa-toolbox";
        break;
    case Admin::$code:
        $liste_actuelle = $liste_destinations_admin;
        $client_icon = "fas fa-user-tie";
        break;
    default:
        echo "<img src='assets/dino.png'/>403: Vous n'avez pas le droit d'acceder a cette page";
        exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion reparation des ordinateurs</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <script src="https://kit.fontawesome.com/49243277f6.js" crossorigin="anonymous"></script>
</head>
<body class="navigation_container">
    <h1 class="username"><i class="<?php echo $client_icon; ?>"></i>&nbsp;&nbsp;&nbsp;<?php echo $_SESSION['login']; ?></h1>
    <hr style="margin: 0 10px 10px 10px;">
    <ul>
        <?php foreach ($liste_actuelle as $nom => $lien){ ?>
            <li class="navigation_item_container"><a href="<?php echo $lien[0]; ?>" class="navigation_btn" target="destination-page"><i class="<?php echo $lien[1];?>"></i>&nbsp;&nbsp;&nbsp;<?php echo $nom; ?></a></li>
        <?php } ?>
        <li><a href="../Controlleur/AuthController.php" class="navigation_btn"><i class="fas fa-power-off"></i>&nbsp;&nbsp;&nbsp;Deconnexion</a></li>
    </ul>
</body>
</html>