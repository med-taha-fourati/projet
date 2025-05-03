<?php 
require_once '../Metier/Utilisateur.php';
require_once '../Metier/Client.php';
require_once '../Metier/Technicien.php';
require_once '../Metier/Admin.php';

class UtilisateurDAO {
    public static function AjouterUtilisateur($login, $password, $nom, $email, $adresse, $tel, $type) {
        include '../Connexion/Connection.php';

        $res = $conn->prepare("INSERT INTO utilisateur (login, password, nom, email, adresse, tel, role) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $res->bindParam(1, $login);
        $res->bindParam(2, $password);
        $res->bindParam(3, $nom);
        $res->bindParam(4, $email);
        $res->bindParam(5, $adresse);
        $res->bindParam(6, $tel);
        $res->bindParam(7, $type);

        $req = $res->execute();

        if ($req) {
            return true;
        } else {
            throw new Exception("Erreur lors de l'ajout de l'utilisateur");
        }
    }

    public static function FindById($id) {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("SELECT * FROM utilisateur WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $row = $res->fetchAll();

        if (!$row) {
            throw new Exception("Utilisateur non trouvé");
        }

        switch ($row[0]['role']) {
            case Client::$code:
                $utilisateur = new Client($row[0]['id'], 
                                          $row[0]['login'], 
                                          $row[0]['password'], 
                                          $row[0]['nom'], 
                                          $row[0]['email'], 
                                          $row[0]['adresse'], 
                                          $row[0]['tel']);
                break;
            case Technicien::$code:
                $utilisateur = new Technicien($row[0]['id'], 
                                          $row[0]['login'], 
                                          $row[0]['password'], 
                                          $row[0]['nom'], 
                                          $row[0]['email'], 
                                          $row[0]['adresse'], 
                                          $row[0]['tel']);
                break;
            case Admin::$code:
                $utilisateur = new Admin($row[0]['id'],
                                         $row[0]['login'],
                                         $row[0]['password'],
                                         $row[0]['nom'],
                                         $row[0]['email'],
                                         $row[0]['adresse'],
                                         $row[0]['tel']);
                break;
            default:
                throw new Exception("Type d'utilisateur inconnu");
        }
        return $utilisateur;
    }

    public static function FetchRoleById($client_id) {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("SELECT role FROM utilisateur WHERE id = ?");
        $res->bindParam(1, $client_id);
        $res->execute();
        $row = $res->fetchAll();

        if (!$row) {
            throw new Exception("Utilisateur non trouvé");
        }

        return $row[0]['role'];
    }

    public static function FindAll() {
        include '../Connexion/Connection.php';
        $res = $conn->query("SELECT * FROM utilisateur");
        $rows = $res->fetchAll();
        $utilisateurs = [];
        foreach ($rows as $row) {
            switch ($row['role']) {
                case Client::$code:
                    $utilisateur = new Client($row['id'], $row['login'], $row['password'], $row['nom'], $row['email'], $row['adresse'], $row['tel']);
                    break;
                case Technicien::$code:
                    $utilisateur = new Technicien($row['id'], $row['login'], $row['password'], $row['nom'], $row['email'], $row['adresse'], $row['tel']);
                    break;
                case Admin::$code:
                    $utilisateur = new Admin($row['id'], $row['login'], $row['password'], $row['nom'], $row['email'], $row['adresse'], $row['tel']);
                    break;
                default:
                    throw new Exception("Type d'utilisateur inconnu");
            }
            $utilisateurs[] = $utilisateur;
        }
        return $utilisateurs;
    }

    public static function SupprimerUtilisateur($id) {
        include_once '../Connexion/Connection.php';

        $res = $conn->prepare("DELETE FROM utilisateur WHERE id = ?");
        $res->bindParam(1, $id);
        $req = $res->execute();

        if ($req) {
            return true;
        } else {
            throw new Exception("Erreur lors de la suppression de l'utilisateur");
        }
    }

    public static function ModifierUtilisateur($id, $login, $password, $nom, $email, $adresse, $tel, $role) {
        $conn = new PDO('mysql:host=localhost;dbname=gestion_reparation', 'root', '');

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $res = $conn->prepare("UPDATE utilisateur SET login = ?, password = ?, nom = ?, email = ?, adresse = ?, tel = ?, role = ? WHERE id = ?");
        
        $res->bindParam(1, $login);
        $res->bindParam(2, $password);
        $res->bindParam(3, $nom);
        $res->bindParam(4, $email);
        $res->bindParam(5, $adresse);
        $res->bindParam(6, $tel);
        $res->bindParam(7, $role);
        $res->bindParam(8, $id);

        if ($res->execute()) {
            return true;
        } else {
            throw new Exception("Erreur lors de la modification de l'utilisateur");
        }
    }

    public static function MiseAJourDeUserATechnicien($id) {
        include_once '../Connexion/Connection.php';

        $res = $conn->prepare("UPDATE utilisateur SET type = 1 WHERE id = ? AND type = 0");
        $res->bindParam(1, $id);

        if ($res->execute()) {
            return true;
        } else {
            throw new Exception("Erreur lors de la mise à jour du type d'utilisateur");
        }
    }
}
?>