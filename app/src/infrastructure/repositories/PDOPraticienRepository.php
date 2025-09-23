<?php

namespace toubilib\infra\repositories;

use toubilib\core\domain\repositories\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;

class PDOPraticienRepository implements PraticienRepositoryInterface
{

    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }
 
    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT p.*, s.libelle FROM praticien p JOIN specialite s ON p.specialite_id = s.id');
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return new Praticien(
                $row['nom'],
                $row['prenom'],
                $row['ville'],
                $row['libelle'],
                $row['email']
            );
        }, $results);
    }

}