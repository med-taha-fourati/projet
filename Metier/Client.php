<?php
class Client extends Utilisateur {
    public static $code = 0;

    public function __construct($id, $login, $password, $nom, $email, $adresse, $tel) {
        parent::__construct($id, $login, $password, $nom, $email, $adresse, $tel);
    }

    public function __toString() {
        return parent::__toString() . " - Client";
    }
}
 ?>