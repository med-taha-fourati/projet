<?php 
class Appareil {
    private $id;
    private $type;
    private $marque;
    private $modele;
    private $numSerie;
    private $client;

    public function __construct($id, $type, $marque, $modele, $numSerie, $client) {
        $this->id = $id;
        $this->type = $type;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->numSerie = $numSerie;
        $this->client = $client;
    }

    public function __get($value) {
        return $this->$value;
    }

    public function __set($value, $newValue) {
        $this->$value = $newValue;
    }

    public function __toString() {
        return "Appareil: [id: $this->id, type: $this->type, marque: $this->marque, modele: $this->modele, numSerie: $this->numSerie, client: $this->client]";
    }
}
?>