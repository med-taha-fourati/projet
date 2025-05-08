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
    public static function filterByItem($appareils, $filter, $filter_option) {
        // trim spaces
        $filter = trim($filter);
        $filter = htmlspecialchars($filter);
        $filter = strip_tags($filter);
        $filter = stripslashes($filter);
        switch ($filter_option) {
            case 'type':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->appareil->type, $filter) !== false;
            });
            break;
        case 'modele':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->appareil->modele, $filter) !== false;
            });
            break;
            case 'marque':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->appareil->marque, $filter) !== false;
        });
        break;
        case 'numSerie':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->appareil->numSerie, $filter) !== false;
            });
            break;
        case 'statut':
            switch ($filter) {
                case 'En attente':
                    $filter_statut = 0;
                    break;
                case 'En reparation':
                    $filter_statut = 1;
                    break;
                case 'Termine':
                    $filter_statut = 2;
                    break;
            }
            if (!isset($filter_statut)) return $appareils;
                $appareils = array_filter($appareils, function ($appareil) use ($filter_statut) {
                    return stripos($appareil->statut, $filter_statut) !== false;
            });
            break;
        }
        return $appareils;    
    }

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
                header('Location: ../Vues/InterfaceTechnicien.php?status=true');
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
        

        if (date('Y-m-d', strtotime($date_fin_prevue)) < date('Y-m-d', strtotime($date_depot))) {
            header('Location: ../Vues/AjouterReparationAdmin.php?status=false&errcode=2');
            return;
        }

        if (empty($panne) || empty($cout)) {
            header('Location: ../Vues/AjouterReparationAdmin.php?status=false&errcode=3');
            return;
        }

        if (empty($appareil_id) || empty($technicien_id)) {
            header('Location: ../Vues/AjouterReparationAdmin.php?status=false&errcode=4');
            return;
        }
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
                header('Location: ../Vues/Administration.php?status=true');
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
                    header('Location: ../Vues/Administration.php?status=true');
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
            if (date('Y-m-d', strtotime($date_fin_prevue)) < date('Y-m-d', strtotime($date_depot))) {
                header('Location: ../Vues/AjouterReparationAdmin.php?reparation_id=' . $reparation_id . '&status=false&errcode=2');
                return;
            }
        
            if (empty($panne) || empty($cout)) {
                header('Location: ../Vues/AjouterReparationAdmin.php?reparation_id=' . $reparation_id . '&status=false&errcode=3');
                return;
            }
        
            if (empty($appareil_id) || empty($technicien_id)) {
                header('Location: ../Vues/AjouterReparationAdmin.php?reparation_id=' . $reparation_id . '&status=false&errcode=4');
                return;
            }
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
                header('Location: ../Vues/Administration.php?status=true');
            } else {
                echo "Erreur lors de l'ajout de la réparation.";
            }
            break;
        case 'Modifier':
            if (date('Y-m-d', strtotime($date_fin_prevue)) < date('Y-m-d', strtotime($date_depot))) {
                header('Location: ../Vues/ModifierReparationAdmin.php?reparation_id=' . $reparation_id . '&status=false&errcode=2');
                return;
            }
        
            if (empty($panne) || empty($cout)) {
                header('Location: ../Vues/ModifierReparationAdmin.php?reparation_id=' . $reparation_id . '&status=false&errcode=3');
                return;
            }
        
            if (empty($appareil_id) || empty($technicien_id)) {
                header('Location: ../Vues/ModifierReparationAdmin.php?reparation_id=' . $reparation_id . '&status=false&errcode=4');
                return;
            }

            ReparationController::ModifierReparation($reparation_id,
                                        $technicien_id,
                                        $client_id,
                                        $appareil_id,
                                        $panne,
                                        $cout,
                                        $statut,
                                        $date_depot,
                                        $date_fin_prevue,
                                        $date_fin_reelle);
            header('Location: ../Vues/Administration.php?status=true');
            break;
        default:
            echo "Action non reconnue.";
            break;
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
            header('Location: ../Vues/FinaliserReparation.php?id=' . $reparation_id . '&status=false&errcode=5');
            exit;
        }
        if ($reparation->statut != 1) {
            echo "Erreur: La réparation n'est pas en cours.";
            //header('Location: ../Vues/FinaliserReparation.php?id=' . $reparation_id);
            exit;
        }
        $reparation->dateFinReelle = $dateFinReelle;
        $reparation->cout = $cout;
        $reparation->panne = $panne;
        $reparation->statut = 2; // Terminé
        ReparationDAO::ModifierReparation($reparation);
        header('Location: ../Vues/InterfaceTechnicien.php?status=true');
        exit;
    } else {
        echo "Erreur: Reparation non trouvée.";
    }
}
?>