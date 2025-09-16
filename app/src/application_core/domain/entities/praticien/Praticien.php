<?php

namespace toubilib\core\domain\entities\praticien;


class Praticien
{
    private string nom;
    private string prenom;
    private string ville;
    private string specialite;
    private string email;

    public function __construct(string $nom, string $prenom, string $ville, string $specialite, string $email)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->specialite = $specialite;
        $this->email = $email;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function getSpecialite(): string
    {
        return $this->specialite;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    
}