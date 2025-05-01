<?php 
require_once '../Controlleur/ReparationController.php';
require_once '../Metier/Utilisateur.php';
require_once '../DAO/UtilisateurDAO.php';
require_once '../Metier/Technicien.php';
require_once '../DAO/ReparationDAO.php';

//NOTE - Verifications pour securite
session_start();
if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];

if (UtilisateurDAO::FetchRoleById($client) != Technicien::$code) {
    echo "403: Vous n'avez pas le droit d'acceder a cette page";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Erreur: ID de reparation non fourni";
    exit;
}


$reparation = ReparationDAO::FindByReparationId($_GET['id']);
if ($reparation == null) {
    echo "Erreur: Reparation non trouvee";
    exit;
}
if ($reparation->statut != 1) {
    echo "Erreur: Pas en reparation";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser la reparation</title>
    <link rel="stylesheet" href="./Styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body class="admin_page_container global_coloring">
    <h1>Finaliser la reparation pour <?php echo $reparation->appareil->marque; ?></h1>
    <form action="../Controlleur/ReparationController.php" method="post">
        <label for="dateFinReelle">Date de fin réelle:</label>
        <input type="date" id="dateFinReelle" class="form-control" name="dateFinReelle" required>

        <label for="cout">Coût:</label>
        <input type="number" id="cout" class="form-control" name="cout" min="0" required>

        <label for="panne">Panne:</label>
        <input type="text" id="panne" class="form-control" name="panne" required>

        <input type="hidden" name="rep_id" value="<?php echo $reparation->id; ?>">
        
        <hr>
        <input type="submit" class="btn btn-primary" name="fin_rep" value="Valider">
    </form>
</body>
</html>