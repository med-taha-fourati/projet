<?php
class Technicien extends Utilisateur {
    public static $code = 1;

    public function __construct($id, $login, $password, $nom, $email, $adresse, $tel) {
        parent::__construct($id, $login, $password, $nom, $email, $adresse, $tel);
    }

    public function __toString() {
        return parent::__toString() . " - Technicien";
    }
}
 ?>