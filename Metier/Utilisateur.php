<?php
class Utilisateur {
    private $id;
    private $login;
    private $password;
    private $nom;
    private $email;
    private $adresse;
    private $tel;
    
    public function __construct($id, $login, $password, $nom, $email, $adresse, $tel) {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->nom = $nom;
        $this->email = $email;
        $this->adresse = $adresse;
        $this->tel = $tel;
    }

    public function __get($value) {
        return $this->$value;
    }

    public function __set($value, $newValue) {
        $this->$value = $newValue;
    }

    public function __toString() {
        return "Utilisateur: [id: $this->id, login: $this->login, password: $this->password, nom: $this->nom, email: $this->email, adresse: $this->adresse, tel: $this->tel]";
    }
}
?>