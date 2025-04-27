<?php 
class Admin extends Utilisateur {
    public static $code = 2;

    public function __construct($id, $login, $password, $nom, $email, $adresse, $tel) {
        parent::__construct($id, $login, $password, $nom, $email, $adresse, $tel);
    }

    public function __toString() {
        return parent::__toString() . " - Admin";
    }
}
?>