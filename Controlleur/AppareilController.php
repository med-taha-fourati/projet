<?php
require_once '../DAO/AppareilDAO.php';
require_once '../Metier/Appareil.php';
require_once '../Controlleur/UtilisateurController.php';

class AppareilController {
    public static function ListeAppareilsByClient($client_id) {
        try {
            $appareils = AppareilDAO::FindByClientId($client_id);
            $appareilsClient = [];
            foreach ($appareils as $appareil) {
                echo $appareil->client->id;
                if ($appareil->client->id == $client_id) {
                    $appareilsClient[] = $appareil;
                }
            }
            return $appareilsClient;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

    public static function ListeToutesAppareils() {
        try {
            $appareils = AppareilDAO::FindAll();
            return $appareils;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

    public static function ListeAppareilById($appareil_id) {
        try {
            $appareil = AppareilDAO::FindById($appareil_id);
            if ($appareil) {
                return $appareil;
            } else {
                echo "Appareil non trouvé.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
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
                    return stripos($appareil->type, $filter) !== false;
            });
            break;
        case 'modele':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->modele, $filter) !== false;
            });
            break;
            case 'marque':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->marque, $filter) !== false;
        });
        break;
        case 'numSerie':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->numSerie, $filter) !== false;
            });
            break;
        case 'login-client':
            $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                return stripos($appareil->client->login, $filter) !== false;
            });
            break;
        }
        return $appareils;    
    }
}

// modifier appareil
if (isset($_POST['modifier_appareil_admin'])) {
    $appareil_id = $_POST['appareil_id'];
    $type = $_POST['type'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $numSerie = $_POST['numSerie'];
    $client_id = $_POST['client'];

    if (empty($type) || empty($marque) || empty($modele) || empty($numSerie)) {
        header('Location: ../Vues/ModifierAppareil.php?appareil_id=' . $appareil_id.'&status=false&errcode=3');
        exit;
    }

    if ($type != "PC Portable" && $type != "PC Bureau") {
        header('Location: ../Vues/ModifierAppareil.php?appareil_id=' . $appareil_id.'&status=false&errcode=5');
        exit;
    }

    AppareilDAO::ModifierAppareil($appareil_id, $type, $marque, $modele, $numSerie, $client_id);
    header('Location: ../Vues/AfficherAppareils.php?status=true');
}

// ajouter appareil
if (isset($_POST['ajouter_appareil_admin'])) {
    $type = $_POST['type'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $numSerie = $_POST['numSerie'];
    $client_id = $_POST['client'];

    if (empty($type) || empty($marque) || empty($modele) || empty($numSerie)) {
        header('Location: ../Vues/AjouterAppareil.php?appareil_id=' . $appareil_id.'&status=false&errcode=3');
        exit;
    }

    if ($type != "PC Portable" && $type != "PC Bureau") {
        header('Location: ../Vues/AjouterAppareil.php?appareil_id=' . $appareil_id.'&status=false&errcode=5');
        exit;
    }

    AppareilDAO::AjouterAppareil($type, $marque, $modele, $numSerie, $client_id);
    header('Location: ../Vues/AfficherAppareils.php?status=true');
}
 ?>

 