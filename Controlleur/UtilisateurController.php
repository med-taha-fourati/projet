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
?>