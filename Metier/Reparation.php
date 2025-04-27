<?php
class Reparation {
    private $id;
    private $dateDepot;
    private $dateDebut;
    private $dateFinPrevue;
    private $dateFinReelle;
    private $panne;
    private $cout;
    private $statut;
    private $technicien;
    private $appareil;

    /*
    Status:
    - 0: En attente
    - 1: En reparation
    - 2: Terminé
    */

    public function __construct($id, $dateDepot, $dateDebut, $dateFinPrevue, $dateFinReelle, $panne, $cout, $statut, $client, $appareil) {
        $this->id = $id;
        $this->dateDepot = $dateDepot;
        $this->dateDebut = $dateDebut;
        $this->dateFinPrevue = $dateFinPrevue;
        $this->dateFinReelle = $dateFinReelle;
        $this->panne = $panne;
        $this->cout = $cout;
        $this->technicien = $client;
        $this->appareil = $appareil;
        $this->statut = $statut;
    }

    public function __get($value) {
        return $this->$value;
    }

    public function __set($value, $newValue) {
        $this->$value = $newValue;
    }

    public function __toString() {
        return "Reparation: [id: $this->id, dateDepot: $this->dateDepot, dateDebut: $this->dateDebut, dateFinPrevue: $this->dateFinPrevue, dateFinReelle: $this->dateFinReelle, panne: $this->panne, cout: $this->cout, statut: $this->statut, <br>technicien: $this->technicien, <br>appareil: $this->appareil]";
    }
}
 ?>
