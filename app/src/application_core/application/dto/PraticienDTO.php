<?php

namespace toubilib\core\application\dto;

class PraticienDTO {
    public string $nom;
    public string $prenom;
    public string $ville;
    public string $specialite;
    public string $email;

    public function __construct(string $nom, string $prenom, string $ville, string $specialite, string $email) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->specialite = $specialite;
        $this->email = $email;
    }
}