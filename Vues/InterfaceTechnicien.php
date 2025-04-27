<?php
/*- Type du PC 
- Marque  
- Modèle 
- N° série 
- Date de dépôt pour réparation 
- date fin prévue  
- panne,  
- cout,  

- Action Technicien : cette colonne affiche l’une des possibilités suivantes :  
▪ Lancer  réparation  : Lien hypertexte affiché lorsque l’état de la réparation  est 
« en attente » et permet de changer l’état à « en reparation » , d’insérer la date 
de début de réparation dans la table Reparation , et de réafficher la page. 
▪ Finaliser réparation: Lien hypertexte affiché lorsque l’état de la réparation est 
« en reparation » et permet d’afficher la page finaliserReparation  */

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../DAO/AppareilDAO.php';
require_once '../DAO/ReparationDAO.php';
require_once '../DAO/UtilisateurDAO.php';
require_once '../Metier/Appareil.php';
require_once '../Metier/Reparation.php';
require_once '../Metier/Technicien.php';
require_once '../Controlleur/ReparationController.php';

session_start();
if (!isset($_SESSION['client'])) {
    header('Location: ../Vues/Authentification.php');
    exit();
}
$client = $_SESSION['client'];
if (UtilisateurDAO::FetchRoleById($client) < Technicien::$code) {
    echo "<img src='assets/dino.png'/>403: Vous n'avez pas le droit d'acceder a cette page";
    exit;
}

//NOTE - Client de notre session
$appareils = ReparationController::ListeReparationsByClient($client); //AppareilController::ListeAppareilsByClient($client);
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
<h1>Liste des appareils a reparer pour technicien <?php echo $_SESSION['login']; ?></h1>
<body class="admin_page_container global_coloring">
    <table class="table table-bs-props table-responsive">
        <tr>
            <th>Type</th>
            <th>Marque</th>
            <th>Modele</th>
            <th>Num serie</th>
            <th>Date de depot pour reparation</th>
            <th>Date fin prevue</th>
            <th>Panne</th>
            <th>Cout</th>
            <th>Actions</th>
        </tr>
        <?php if (sizeof($appareils) == 0) {
            ?> <tr><td colspan="9">Aucun appareil trouve</td></tr> <?php
        } else {
            foreach ($appareils as $appareil) {
                ?>
                <tr name="<?php echo $appareil->id; ?>">
                    <form action="../Controlleur/ReparationController.php" method="post">
                        <input type="hidden" name="rep_id" value="<?php echo $appareil->id; ?>">
                        <td><?php echo $appareil->appareil->type; ?></td>
                        <td><?php echo $appareil->appareil->marque; ?></td>
                        <td><?php echo $appareil->appareil->modele; ?></td>
                        <td><?php echo $appareil->appareil->numSerie; ?></td>
                        <td><?php echo $appareil->dateDepot ?? "Pas confirme"; ?></td>
                        <td><?php echo $appareil->dateFinPrevue ?? "Pas confirme"; ?></td>
                        <td><?php echo $appareil->panne; ?></td>
                        <td><?php echo $appareil->cout; ?></td>
                        <td>
                            <?php 
                            switch ($appareil->statut) {
                                case 0:
                                    ?> <input type="submit" class="btn btn-outline-primary" name="action_tech" value="Lancer une reparation"> <?php
                                    break;
                                case 1:
                                    ?> <input type="submit" class="btn btn-outline-success" name="action_tech" value="Finaliser la reparation"> <?php
                                    break;
                                case 2:
                                    echo "Reparation Termine";
                                    break;
                            }
                            ?>
                        </td>
                    </form>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</body>
</html>