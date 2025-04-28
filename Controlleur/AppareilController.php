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
}
 ?>