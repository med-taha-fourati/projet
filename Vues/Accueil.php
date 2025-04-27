<!-- 
 - Type du PC 
- Marque  
- Modèle 
- N° série 
- Date de dépôt pour réparation 
- date fin prévue  
- date fin Réelle,  
- panne,  
- cout,  
- statut 
 -->
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../DAO/AppareilDAO.php';
require_once '../DAO/ReparationDAO.php';
require_once '../DAO/UtilisateurDAO.php';
require_once '../Metier/Appareil.php';
require_once '../Controlleur/UtilisateurController.php';

session_start();
if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];
$appareils = UtilisateurController::ListeReparationsByClient($client); //AppareilController::ListeAppareilsByClient($client);
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
<h1>Historique pour client <?php echo $_SESSION['login']; ?></h1>
<body class="admin_page_container global_coloring">
    <table class="table table-bs-props table-responsive">
        <tr>
            <th>Type</th>
            <th>Marque</th>
            <th>Modele</th>
            <th>Num serie</th>
            <th>Date de depot pour reparation</th>
            <th>Date fin prevue</th>
            <th>Date fin reelle</th>
            <th>Panne</th>
            <th>Cout</th>
            <th>Statut</th>
        </tr>
        <?php if (sizeof($appareils) == 0) {
            ?> <tr><td colspan="10">Aucun reparation trouve</td></tr> <?php
        } else {
            foreach ($appareils as $appareil) {
                ?>
                <tr>
                    <td><?php echo $appareil->appareil->type; ?></td>
                    <td><?php echo $appareil->appareil->marque; ?></td>
                    <td><?php echo $appareil->appareil->modele; ?></td>
                    <td><?php echo $appareil->appareil->numSerie; ?></td>
                    <td><?php echo $appareil->dateDepot ?? "Pas confirme"; ?></td>
                    <td><?php echo $appareil->dateFinPrevue ?? "Pas confirme"; ?></td>
                    <td><?php echo $appareil->dateFinReelle ?? "Pas confirme"; ?></td>
                    <td><?php echo $appareil->panne; ?></td>
                    <td><?php echo $appareil->cout; ?>dt</td>
                    <td><?php switch ($appareil->statut) {
                        case 0:
                            echo "En attente";
                            break;
                        case 1:
                            echo "En reparation";
                            break;
                        case 2:
                            echo "Terminé";
                            break;
                    } ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</body>
</html>