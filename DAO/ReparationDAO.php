<?php
require_once '../Metier/Reparation.php';
require_once '../Metier/Appareil.php';
require_once '../Metier/Utilisateur.php';
require_once '../Metier/Client.php';
require_once '../Metier/Technicien.php';
require_once '../Metier/Admin.php';

class ReparationDAO {
    //FIXME - appareil should look using the id_appareil attribute from the table
    public static function FindAll() {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("SELECT * FROM reparation");
        $res->execute();
        $reparations = [];
        $rows = $res->fetchAll();
        foreach ($rows as $row) {
            $technicien = UtilisateurDAO::FindById($row['id_technicien']);
            $appareil = AppareilDAO::FindById($row['id_appareil']);
            $reparation = new Reparation($row['id'], 
                                         $row['dateDepot'],
                                         $row['dateDebut'], 
                                         $row['dateFinPrevue'], 
                                         $row['dateFinReelle'],
                                         $row['panne'],
                                         $row['cout'],
                                         $row['statut'],
                                         $technicien,
                                         $appareil
                                    );
                $reparations[] = $reparation;
        }
        return $reparations;
    }

    public static function FindByReparationId($reparation_id) {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("SELECT * FROM reparation WHERE id = ?");
        $res->bindParam(1, $reparation_id);
        $res->execute();
        $row = $res->fetchAll();

        if (!$row) {
            throw new Exception("Reparation non trouvée");
        }
            $technicien = UtilisateurDAO::FindById($row[0]['id_technicien']);
            
            $appareil = AppareilDAO::FindById($row[0]['id_appareil']);
                $reparation = new Reparation($row[0]['id'], 
                                         $row[0]['dateDepot'],
                                         $row[0]['dateDebut'], 
                                         $row[0]['dateFinPrevue'], 
                                         $row[0]['dateFinReelle'],
                                         $row[0]['panne'],
                                         $row[0]['cout'],
                                         $row[0]['statut'],
                                         $technicien,
                                         $appareil
                                    );
            
        return $reparation;
    }
    public static function FindByForeignKeyId($appareil_id, $technicien_id) {
        try {
            include '../Connexion/Connection.php';
            $sql = "SELECT * FROM reparation WHERE 1=1";
            if ($appareil_id != null) {
                $sql .= " AND id_appareil = ?";
            }
            if ($technicien_id != null) {
                $sql .= " AND id_technicien = ?";
            }
            $res = $conn->prepare($sql);
            $paramIndex = 1;
            if ($appareil_id != null) {
                $res->bindParam($paramIndex++, $appareil_id);
            }
            if ($technicien_id != null) {
                $res->bindParam($paramIndex++, $technicien_id);
            }
            $res->execute();

            $reparations = [];
            $rows = $res->fetchAll();
            foreach ($rows as $row) {
                $technicien = UtilisateurDAO::FindById($row['id_technicien']);
                $appareil = AppareilDAO::FindById($row['id_appareil']);
                $reparation = new Reparation($row['id'], 
                                             $row['dateDepot'],
                                             $row['dateDebut'], 
                                             $row['dateFinPrevue'], 
                                             $row['dateFinReelle'],
                                             $row['panne'],
                                             $row['cout'],
                                             $row['statut'],
                                             $technicien,
                                             $appareil
                                        );
                    $reparations[] = $reparation;
                
            }
            return $reparations;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

    public static function AjouterReparation($dateDebut, $dateDepot, $dateFinPrevue, $dateFinReelle, $panne, $cout, $statut, $appareil, $technicien) {
        include '../Connexion/Connection.php';
        $sql = "INSERT INTO reparation (dateDebut, dateDepot, dateFinPrevue, dateFinReelle, panne, cout, statut, id_appareil, id_technicien) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $res = $conn->prepare($sql);

        if ($technicien::$code != 1 
        || get_class($technicien) != 'Technicien') {
            return false;
        }
        
        $paramIndex = 1;
        $res->bindParam($paramIndex++, $dateDebut);
        $res->bindParam($paramIndex++, $dateDepot);
        $res->bindParam($paramIndex++, $dateFinPrevue);
        $res->bindParam($paramIndex++, $dateFinReelle);
        $res->bindParam($paramIndex++, $panne);
        $res->bindParam($paramIndex++, $cout);
        $res->bindParam($paramIndex++, $statut);
        $res->bindParam($paramIndex++, $appareil->id);
        $res->bindParam($paramIndex++, $technicien->id);

        $res->execute();


        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public static function SupprimerReparation($reparation_id) {
        include '../Connexion/Connection.php';
        $res = $conn->prepare("DELETE FROM reparation WHERE id = ?");
        $res->bindParam(1, $reparation_id);
        $req = $res->execute();
        if ($req) {
            return true;
        } else {
            throw new Exception("Erreur lors de la suppression de la réparation");
        }
    }

    public static function ModifierReparation($reparation) {
        include '../Connexion/Connection.php';
        echo "Reparation from DAO: ". $reparation->__toString();
        $sql = "UPDATE reparation 
                SET dateDepot = ?, 
                    dateDebut = ?, 
                    dateFinPrevue = ?, 
                    dateFinReelle = ?, 
                    panne = ?, 
                    cout = ?, 
                    statut = ?,
                    id_technicien = ?,
                    id_appareil = ?
                WHERE id = ?";
        $res = $conn->prepare($sql);

        $res->bindParam(1, $reparation->dateDepot);
        $res->bindParam(2, $reparation->dateDebut);
        $res->bindParam(3, $reparation->dateFinPrevue);
        $res->bindParam(4, $reparation->dateFinReelle);
        $res->bindParam(5, $reparation->panne);
        $res->bindParam(6, $reparation->cout);
        $res->bindParam(7, $reparation->statut);
        $res->bindParam(8, $reparation->technicien->id);
        $res->bindParam(9, $reparation->appareil->id);
        $res->bindParam(10, $reparation->id);
        
        $res->execute();
        if ($res) {
            return true;
        } else {
            throw new Exception("Erreur lors de la modification de la réparation");
        }
    }
}
?>