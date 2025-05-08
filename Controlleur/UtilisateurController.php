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

class UtilisateurController {
    public static function filterByItem($appareils, $filter, $filter_option) {
        $filter = trim($filter);
        $filter = htmlspecialchars($filter);
        $filter = strip_tags($filter);
        $filter = stripslashes($filter);
        switch ($filter_option) {
            case 'login':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->login, $filter) !== false;
            });
            break;
        case 'nom':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->nom, $filter) !== false;
            });
            break;
            case 'email':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->email, $filter) !== false;
            });
            break;
            case 'adresse':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->adresse, $filter) !== false;
            });
            break;
            case 'tel':
                $appareils = array_filter($appareils, function ($appareil) use ($filter) {
                    return stripos($appareil->tel, $filter) !== false;
            });
            break;
        }
        return $appareils;    
    }

    public static function VerifierTechnicien($client) {
        $role = UtilisateurDAO::FetchRoleById($client);
        if ($role >= Technicien::$code) {
            return true;
        } else {
            return false;
        }
    }

    public static function ListeReparationsByClient($client_id) {
        $reparations_tout = [];
        $reparations = ReparationDAO::FindAll();
        foreach ($reparations as $reparation) {
            if ($reparation->appareil->client->id == $client_id) {
                $reparations_tout[] = $reparation;
            }
        }
        return $reparations_tout;
    }

    public static function ListeUtilisateurs() {
        $client = UtilisateurDAO::FindAll();
        $clients = [];
        foreach ($client as $user) {
            $clients[] = $user;
        }
        return $clients;
    }

    public static function ListeClients() {
        $client = UtilisateurDAO::FindAll();
        $clients = [];
        foreach ($client as $user) {
            if (UtilisateurDAO::FetchRoleById($user->id) == Client::$code) {
                $clients[] = $user;
            }
        }
        return $clients;
    }

    public static function ListeTechniciens() {
        $client = UtilisateurDAO::FindAll();
        $techniciens = [];
        foreach ($client as $user) {
            if (UtilisateurDAO::FetchRoleById($user->id) == Technicien::$code) {
                $techniciens[] = $user;
            }
        }
        return $techniciens;
    }

    public static function ListeTechniciensById($technicien_id) {
        $technicien = UtilisateurDAO::FindById($technicien_id);
        if ($technicien && UtilisateurDAO::FetchRoleById($technicien->id) == Technicien::$code) {
            return $technicien;
        }
        return null;
    }
}

// modifier technicien
if (isset($_POST['modifier_technicien_admin'])) {
    $technicien_id = $_POST['technicien_id'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $tel = $_POST['tel'];

    if (empty($login) || empty($password) || empty($nom) || empty($email) || empty($adresse) || empty($tel)) {
        header('Location: ../Vues/ModifierTechnicien.php?technicien_id=' . $technicien_id.'&status=false&errcode=3');
        exit;
    }

    UtilisateurDAO::ModifierUtilisateur($technicien_id, 
                                        $login, 
                                        $password, 
                                        $nom, 
                                        $email, 
                                        $adresse, 
                                        $tel);
    header('Location: ../Vues/AfficherTechniciens.php?status=true');
}

// ajouter technicien
if (isset($_POST['ajouter_technicien_admin'])) {
    echo $_POST['client'];
    if (isset($_POST['exist_select'])) {
    if (isset($_POST['client']) && $_POST['exist_select'] == 'existant') {
        $client_id = $_POST['client'];
        $client = UtilisateurDAO::FindById($client_id);
        UtilisateurDAO::ModifierUtilisateur($client->id, 
                                            $client->login,
                                            $client->password,
                                            $client->nom,
                                            $client->email,
                                            $client->adresse,
                                            $client->tel,
                                            Technicien::$code);
        header('Location: ../Vues/AfficherTechniciens.php?status=true');
    } else {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        $tel = $_POST['tel'];

        if (empty($login) || empty($password) || empty($nom) || empty($email) || empty($adresse) || empty($tel)) {
            header('Location: ../Vues/AjouterTechnicien.php?status=false&errcode=3');
            return;
        }

        UtilisateurDAO::AjouterUtilisateur($login, $password, $nom, $email, $adresse, $tel, Technicien::$code);
        header('Location: ../Vues/AfficherTechniciens.php?status=true');
    }} else {
        header('Location: ../Vues/AjouterTechnicien.php?status=false&errcode=6');
        exit;
    }
}

// retrogarder technicien
if (isset($_POST['derank_technicien'])) {
    $technicien_id = $_POST['technicien_id'];
    $technicien = UtilisateurDAO::FindById($technicien_id);
    UtilisateurDAO::ModifierUtilisateur($technicien->id, 
                                        $technicien->login,
                                        $technicien->password,
                                        $technicien->nom,
                                        $technicien->email,
                                        $technicien->adresse,
                                        $technicien->tel,
                                        Client::$code);
    header('Location: ../Vues/AfficherTechniciens.php?status=true');
}
?>