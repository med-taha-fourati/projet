<?php 
require_once '../DAO/ReparationDAO.php';
require_once '../DAO/AppareilDAO.php';

require_once '../Metier/Reparation.php';
require_once '../Metier/Appareil.php';
require_once '../Metier/Technicien.php';
require_once '../Metier/Admin.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

class ReparationController {
    public static function ListeReparations() {
        try {
            $reparations = ReparationDAO::FindAll();
            return $reparations;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

    public static function ListeReparationsByClient($client) {
        try {
            $reparations = ReparationDAO::FindByForeignKeyId(null, $client);
            return $reparations;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

    public static function LancerReparation($reparation_id) {
        try {
            $date = date("Y-m-d");
            echo $reparation_id;
            $reparation_trouvee = ReparationDAO::FindByReparationId($reparation_id);
            if ($reparation_trouvee->statut == 0) {
                $reparation_trouvee->statut = 1;
                $reparation_trouvee->dateDebut = $date;
                ReparationDAO::ModifierReparation($reparation_trouvee);
                header('Location: ../Vues/InterfaceTechnicien.php');
                exit;
            }
        } catch (Exception $e) {
            echo "Error Lance: " . $e->getMessage();
            exit;
        }
    }

    // p1 finalisation du reparation
    public static function FinaliserReparation($reparation_id) {
        try {
            $reparation_trouvee = ReparationDAO::FindByReparationId($reparation_id);
            if ($reparation_trouvee->statut == 1) {
                header('Location: ../Vues/FinaliserReparation.php?id=' . $reparation_id);
                exit;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

    public static function AjouterReparation($date_debut,
                                             $date_depot,
                                             $date_fin_prevue,
                                             $date_fin_reelle,
                                             $panne,
                                             $cout,
                                             $statut,
                                             $appareil_id,
                                             $technicien_id) {

        // if ($date_fin_prevue < $date_depot) {
        //     header('Location: ../Vues/AjouterReparationAdmin.php');
        //     return;
        // }

        // if (empty($panne) || empty($cout) || empty($statut)) {
        //     header('Location: ../Vues/AjouterReparationAdmin.php');
        //     return;
        // }

        // if (empty($appareil_id) || empty($technicien_id)) {
        //     header('Location: ../Vues/AjouterReparationAdmin.php');
        //     return;
        // }
        return ReparationDAO::AjouterReparation($date_debut, 
                                        $date_depot,
                                        $date_fin_prevue,
                                        $date_fin_reelle,
                                        $panne,
                                        $cout,
                                        $statut,
                                        AppareilDAO::FindById($appareil_id),
                                        UtilisateurDAO::FindById($technicien_id));
    }
}

//NOTE - Formulaire control
if (isset($_POST['action_tech'])) {
    $commande = $_POST['action_tech'];
    $id = $_POST['rep_id'];
    switch ($commande) {
        case 'Lancer une reparation':
            ReparationController::LancerReparation($id);
            break;
        case 'Finaliser la reparation':
            ReparationController::FinaliserReparation($id);
            break;
        default:
            echo "Action non reconnue.";
            break;
    }
}

// p2 finalisation du reparation
if (isset($_POST['fin_rep'])) {
    $reparation_id = $_POST['rep_id'];
    $dateFinReelle = $_POST['dateFinReelle'] ?? date("Y-m-d");
    $cout = $_POST['cout'];
    $panne = $_POST['panne'];

    $reparation = ReparationDAO::FindByReparationId($reparation_id);
    if ($reparation) {
        // checks
        if ($dateFinReelle < $reparation->dateDebut) {
            header('Location: ../Vues/FinaliserReparation.php?id=' . $reparation_id);
            exit;
        }
        if ($reparation->statut != 1) {
            echo "Erreur: La réparation n'est pas en cours.";
            header('Location: ../Vues/FinaliserReparation.php?id=' . $reparation_id);
            exit;
        }
        $reparation->dateFinReelle = $dateFinReelle;
        $reparation->cout = $cout;
        $reparation->panne = $panne;
        $reparation->statut = 2; // Terminé
        ReparationDAO::ModifierReparation($reparation);
        header('Location: ../Vues/InterfaceTechnicien.php');
        exit;
    } else {
        echo "Erreur: Reparation non trouvée.";
    }
}
?>