<?php 
require_once '../DAO/ReparationDAO.php';
require_once '../DAO/AppareilDAO.php';

require_once '../Metier/Reparation.php';
require_once '../Metier/Appareil.php';
require_once '../Metier/Technicien.php';
require_once '../Metier/Admin.php';
require_once '../Metier/Client.php';

require_once '../Controlleur/ReparationController.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

class AdminController {
    public static function ListeReparationsToutClients() {
        $reparations_tout = [];
        $reparations = ReparationDAO::FindAll();
        foreach ($reparations as $reparation) {
            $reparations_tout[] = $reparation;
        }
        return $reparations_tout;
    }

    public static function SupprimerReparation($reparation_id) {
        try {
            $reparation = ReparationDAO::FindByReparationId($reparation_id);
            if ($reparation) {
                ReparationDAO::SupprimerReparation($reparation_id);
                header('Location: ../Vues/Administration.php');
                exit;
            } else {
                echo "Reparation non trouvée.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

    public static function ModifierReparation($reparation_id,
                                              $technicien_id,
                                              $client_id,
                                              $appareil_id,
                                              $panne,
                                              $cout,
                                              $statut,
                                              $date_depot,
                                              $date_fin_prevue,
                                              $date_fin_reelle) {
        echo "placeholder";
        try {
            $technicien = UtilisateurDAO::FindById($technicien_id);
            $client = UtilisateurDAO::FindById($client_id);
            $appareil = AppareilDAO::FindById($appareil_id);

            if ($technicien && $client && $appareil) {
                AppareilDAO::ModifierAppareil($appareil_id,
                                             $appareil->type,
                                             $appareil->marque,
                                             $appareil->modele,
                                             $appareil->numSerie,
                                             $client);

                $reparation = ReparationDAO::FindByReparationId($reparation_id);
                
                $reparation->technicien = $technicien;
                $reparation->appareil = $appareil;
                $reparation->panne = $panne;
                $reparation->cout = $cout;
                $reparation->statut = $statut;
                $reparation->dateDepot = $date_depot;
                $reparation->dateFinPrevue = $date_fin_prevue;
                $reparation->dateFinReelle = $date_fin_reelle;

                if (ReparationDAO::ModifierReparation($reparation)) {
                    header('Location: ../Vues/Administration.php');
                } else {
                    echo "Erreur lors de la modification de la réparation.";
                }
                
                exit;
            } else {
                echo "Erreur lors de la modification de la réparation: Client, Technicien ou Appareil non trouvé.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }
}

if (isset($_POST['action_admin'])) {
    $action = $_POST['action_admin'];
    $reparation_id = $_POST['rep_id'];

    switch ($action) {
        case 'Annuler':
            AdminController::SupprimerReparation($reparation_id);
            header('Location: ../Vues/Administration.php');
            break;
        case 'Modifier':
            header('Location: ../Vues/ModifierReparationAdmin.php?reparation_id=' . $reparation_id);
            break;
        default:
            echo "Action non reconnue.";
            break;
    }
}

if (isset($_POST['modifier_rep'])) {
    $reparation_id = $_POST['rep_id'];

    // ids
    $technicien_id = $_POST['technicien'];
    $client_id = $_POST['client'];
    $appareil_id = $_POST['appareil'];
    echo "technicien_id: " . $technicien_id;
    echo "client_id: " . $client_id;
    echo "appareil_id: " . $appareil_id;

    // other attribs
    $panne = $_POST['panne'];
    $cout = $_POST['cout'];
    $statut = $_POST['statut'];
    $date_depot = ($_POST['date_depot'] != "") ? $_POST['date_depot'] : null;
    $date_fin_prevue = ($_POST['date_fin_pr'] != "") ? $_POST['date_fin_pr'] : null;
    $date_fin_reelle = ($_POST['date_fin_re'] != "") ? $_POST['date_fin_re'] : null;

    switch ($_POST['modifier_rep']) {
        case 'Ajouter':
            $res = ReparationController::AjouterReparation(null,
                                        $date_depot,
                                        $date_fin_prevue,
                                        $date_fin_reelle,
                                        $panne,
                                        $cout,
                                        $statut,
                                        $appareil_id,
                                        $technicien_id);
            if ($res) {
                header('Location: ../Vues/Administration.php');
            } else {
                echo "Erreur lors de l'ajout de la réparation.";
            }
            break;
        case 'Modifier':
            AdminController::ModifierReparation($reparation_id,
                                        $technicien_id,
                                        $client_id,
                                        $appareil_id,
                                        $panne,
                                        $cout,
                                        $statut,
                                        $date_depot,
                                        $date_fin_prevue,
                                        $date_fin_reelle);
            header('Location: ../Vues/Administration.php');
            break;
        default:
            echo "Action non reconnue.";
            break;
    }
    
}
?>