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
/* SECTION - 
offre  un  formulaire  permettant  de  mettre  à  jour :  la  date  de  fin  de 
réparation (postérieur à la date début de  réparation), la panne, et le  coût de réparation. Le 
bouton « valider » permet d’enregistrer les informations dans la table Réparation dans la base 
de données avec l’état « Termine » et retourner à la page InterfaceTechnicien.
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser la reparation</title>
    <link rel="stylesheet" href="./Styles/style.css">
</head>
<body>
    <h1>Finaliser la reparation pour <?php echo $reparation->appareil->marque; ?></h1>
    <form action="" method="post">
        <label for="dateFinReelle">Date de fin réelle:</label>
        <input type="date" id="dateFinReelle" name="dateFinReelle" required><br><br>

        <label for="cout">Coût:</label>
        <input type="number" id="cout" name="cout" min="0" required><br><br>

        <label for="panne">Panne:</label>
        <input type="text" id="panne" name="panne" required><br><br>

        <input type="hidden" name="rep_id" value="<?php echo $reparation->id; ?>">
        <input type="submit" name="fin_rep" value="Valider">
    </form>
</body>
</html>