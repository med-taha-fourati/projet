<?php 
require_once '../Metier/Appareil.php';
require_once '../DAO/UtilisateurDAO.php';
require_once '../Metier/Utilisateur.php';
require_once '../Metier/Admin.php';
require_once '../Metier/Client.php';
require_once '../Metier/Technicien.php';

class AppareilDAO {
    public static function AjouterAppareil($type, $marque, $modele, $numSerie, $client) {
        include_once '../Connexion/Connection.php';

        $res = $conn->prepare("INSERT INTO appareil (type, marque, modele, numSerie, client) VALUES (?, ?, ?, ?, ?)");
        $res->bindParam(1, $type);
        $res->bindParam(2, $marque);
        $res->bindParam(3, $modele);
        $res->bindParam(4, $numSerie);
        $res->bindParam(5, $client->id);
        $req = $res->execute();
        if ($req) {
            return $appareil;
        } else {
            throw new Exception("Erreur lors de l'ajout de l'appareil");
        }
    }

    public static function FindByClientId($client_id) {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("SELECT * FROM appareil WHERE id_client = ?");
        $res->bindParam(1, $client_id);
        $res->execute();

        $client = UtilisateurDAO::FindById($client_id);
        if (!$client) {
            throw new Exception("Client non trouvé");
        }

        $appareils = [];
        $rows = $res->fetchAll();
        foreach ($rows as $row) {
            $appareil = new Appareil($row['id'], $row['type'], $row['marque'], $row['modele'], $row['numserie'], $client);
            $appareils[] = $appareil;
        }
        return $appareils;
    }

    public static function FindById($id) {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("SELECT * FROM appareil WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $row = $res->fetchAll();

        $client = UtilisateurDAO::FindById($row[0]['id_client']);
        
        if (!$row) {
            throw new Exception("Appareil non trouvé");
            //FIXME - 
            // this should be handled in the controller and instead return null instead of throwing an exception;
        }

        return new Appareil($row[0]['id'], $row[0]['type'], $row[0]['marque'], $row[0]['modele'], $row[0]['numserie'], $client);
    }

    public static function FindAll() {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("SELECT * FROM appareil");
        $res->execute();
        $appareils = [];
        $rows = $res->fetchAll();

        foreach ($rows as $row) {
            $client = UtilisateurDAO::FindById($row['id_client']);
            if (!$client) {
                echo "Client non trouvé pour l'appareil ID: " . $row['id'];
                continue;
            }
            $appareil = new Appareil($row['id'], $row['type'], $row['marque'], $row['modele'], $row['numserie'], $client);
            $appareils[] = $appareil;
        }
        return $appareils;
    }

    public static function SupprimerAppareil($id) {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("DELETE FROM appareil WHERE id = ?");
        $res->bindParam(1, $id);
        $req = $res->execute();
        if ($req) {
            return true;
        } else {
            throw new Exception("Erreur lors de la suppression de l'appareil");
        }
    }

    public static function ModifierAppareil($id, $type, $marque, $modele, $numSerie, $client) {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("UPDATE appareil SET type = ?, marque = ?, modele = ?, numserie = ?, id_client = ? WHERE id = ?");
        
        $res->bindParam(1, $type);
        $res->bindParam(2, $marque);
        $res->bindParam(3, $modele);
        $res->bindParam(4, $numSerie);
        $res->bindParam(5, $client->id);
        $res->bindParam(6, $id);
        
        $req = $res->execute();
        
        if ($req) {
            return true;
        } else {
            throw new Exception("Erreur lors de la modification de l'appareil");
        }
    }
}
?>