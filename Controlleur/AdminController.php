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
    public static function VerifierAdmin($client) {
        $role = UtilisateurDAO::FetchRoleById($client);
        if ($role == Admin::$code) {
            return true;
        } else {
            return false;
        }
    }

    public static function filterByItem($appareils, $filter, $filter_option) {
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
        case 'login-client':
            $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                return stripos($appareil->appareil->client->login, $filter) !== false;
            });
            break;
        case 'tech-nom':
            $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                return stripos($appareil->technicien->nom, $filter) !== false;
            });
            break;
        }
        return $appareils;    
    }
}

if (isset($_POST['action_admin'])) {
    $action = $_POST['action_admin'];
    $reparation_id = $_POST['rep_id'];

    switch ($action) {
        case 'Annuler':
            ReparationController::SupprimerReparation($reparation_id);
            header('Location: ../Vues/Administration.php?status=true');
            break;
        case 'Modifier':
            header('Location: ../Vues/ModifierReparationAdmin.php?reparation_id=' . $reparation_id);
            break;
        default:
            echo "Action non reconnue.";
            break;
    }
}

// control admin technicien
if (isset($_POST['action_admin_tech'])) {
    $action = $_POST['action_admin_tech'];
    $technicien_id = $_POST['rep_id'];

    switch ($action) {
        case 'Supprimer':
            UtilisateurDAO::SupprimerUtilisateur($technicien_id);
            header('Location: ../Vues/AfficherTechniciens.php?status=true');
            break;
        case 'Modifier':
            header('Location: ../Vues/ModifierTechnicien.php?technicien_id=' . $technicien_id);
            break;
        default:
            echo "Action non reconnue.";
            break;
    }
}

// controle appareil admin
if (isset($_POST['action_admin_app'])) {
    $action = $_POST['action_admin_app'];
    $appareil_id = $_POST['rep_id'];

    switch ($action) {
        case 'Supprimer':
            AppareilDAO::SupprimerAppareil($appareil_id);
            header('Location: ../Vues/AfficherAppareils.php?status=true');
            break;
        case 'Modifier':
            header('Location: ../Vues/ModifierAppareil.php?appareil_id=' . $appareil_id);
            break;
        default:
            echo "Action non reconnue.";
            break;
    }
}
?>